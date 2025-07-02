<?php
require_once '../php/conexao.php';

$idUser = $_SESSION['id_user'];

try {
    $limite = 10;
    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $limite;

    if ($_SESSION['type_user'] != 'admin') {
        $stmt = $conn->prepare("
            SELECT 
                si.id_sale,
                si.product_id,
                p.name,
                si.quantity,
                si.price_unit,
                s.id_user AS sale_user_id
            FROM sale_items si
            JOIN product p ON si.product_id = p.product_id
            JOIN sales s ON si.id_sale = s.id_sale
            WHERE s.id_user = :id_user
            ORDER BY si.id_sale ASC, si.id_item ASC
            LIMIT :limite OFFSET :offset
        ");
        $stmt->bindValue(':id_user', $idUser, PDO::PARAM_INT);
    } else {
        $stmt = $conn->prepare("
            SELECT 
                si.id_sale,
                si.product_id,
                p.name,
                si.quantity,
                si.price_unit,
                s.id_user AS sale_user_id
            FROM sale_items si
            JOIN product p ON si.product_id = p.product_id
            JOIN sales s ON si.id_sale = s.id_sale
            ORDER BY si.id_sale ASC, si.id_item ASC
            LIMIT :limite OFFSET :offset
        ");
    }

    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $vendas = [];
    $usuariosVenda = []; // Cache para nomes de usuários

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_sale = $row['id_sale'];
        $sale_user_id = $row['sale_user_id'];

        if (!isset($vendas[$id_sale])) {
            $vendas[$id_sale] = [
                'produtos' => [],
                'quantidade_total' => 0,
                'preco_total' => 0.0,
                'sale_user_id' => $sale_user_id
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
            echo "<td>" . htmlspecialchars($id_sale) . "</td>";

            if ($_SESSION['type_user'] == 'admin') {
                $sale_user_id = $dados['sale_user_id'];

                // Verifica se já buscamos esse nome
                if (!isset($usuariosVenda[$sale_user_id])) {
                    $stmtUser = $conn->prepare("SELECT name FROM sellers WHERE fk_id_user = :id_user");
                    $stmtUser->bindValue(':id_user', $sale_user_id, PDO::PARAM_INT);
                    $stmtUser->execute();
                    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
                    $usuariosVenda[$sale_user_id] = $user['name'] ?? 'Desconhecido';
                }

                echo "<td>" . htmlspecialchars($usuariosVenda[$sale_user_id]) . "</td>";
            }

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
