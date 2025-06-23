<?php
require_once '../php/conexao.php';

try {
    $limite = 10;
    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $limite;

    $stmt = $conn->prepare("
        SELECT 
            si.id_sale,
            si.product_id,
            p.name,
            si.quantity,
            si.price_unit
        FROM sale_items si
        JOIN product p ON si.product_id = p.product_id
        ORDER BY si.id_sale ASC, si.id_item ASC
    ");
    $stmt->execute();

    $vendas = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_sale = $row['id_sale'];
        if (!isset($vendas[$id_sale])) {
            $vendas[$id_sale] = [
                'produtos' => [],
                'quantidade_total' => 0,
                'preco_total' => 0.0
            ];
        }

        $nomeProduto = $row['name'];
        $qtd = (int)$row['quantity'];
        $precoUnit = (float)$row['price_unit'];
        $precoTotalItem = $qtd * $precoUnit;

        $vendas[$id_sale]['produtos'][] = "{$nomeProduto} ({$qtd}x - R$ " . number_format($precoUnit, 2, ',', '.') . ")";
        $vendas[$id_sale]['quantidade_total'] += $qtd;
        $vendas[$id_sale]['preco_total'] += $precoTotalItem;
    }

    if (count($vendas) > 0) {
        $codigo = 1;
        foreach ($vendas as $id_sale => $dados) {
            echo "<tr class='linha-venda' onclick=\"window.open('comprovante.php?id={$id_sale}', '_blank')\">";
            // echo "<td>" . $codigo++ . "</td>";
            echo "<td>" . htmlspecialchars($id_sale) . "</td>";
            echo "<td class='descricao'>" . implode(', ', $dados['produtos']) . "</td>";
            echo "<td>" . $dados['quantidade_total'] . "</td>";
            echo "<td>R$ " . number_format($dados['preco_total'], 2, ',', '.') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Nenhum produto encontrado.</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='5'>Erro: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>
