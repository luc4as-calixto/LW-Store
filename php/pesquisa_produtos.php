<?php
require_once "conexao.php";

$termo = isset($_GET['termo']) ? $_GET['termo'] : '';
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = isset($_GET['limite']) && is_numeric($_GET['limite']) ? (int)$_GET['limite'] : 5;
$offset = ($pagina - 1) * $limite;

try {
    if (!empty($termo)) {
        // Pesquisa com termo
        $stmt = $conn->prepare("SELECT * FROM product 
                              WHERE (name LIKE :termo OR 
                                     product_code LIKE :termo OR 
                                     description LIKE :termo)
                              AND amount > 0
                              ORDER BY product_code DESC 
                              LIMIT :limite OFFSET :offset");

        $termoLike = "%$termo%";
        $stmt->bindValue(':termo', $termoLike);
    } else {
        // Sem termo de pesquisa
        $stmt = $conn->prepare("SELECT * FROM product 
                              WHERE amount > 0
                              ORDER BY product_code DESC 
                              LIMIT :limite OFFSET :offset");
    }

    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($produtos)) {
        foreach ($produtos as $produto) {
            // Seu código existente para exibir cada linha da tabela
            echo "<tr>";
            echo "<td>" . htmlspecialchars($produto['product_code']) . "</td>";
            echo "<td>" . htmlspecialchars($produto['name']) . "</td>";
            echo "<td>R$ " . number_format($produto['price'], 2, ',', '.') . "</td>";
            echo "<td>" . htmlspecialchars($produto['amount']) . "</td>";
            echo "<td>" . htmlspecialchars($produto['type_packaging']) . "</td>";
            echo "<td>" . htmlspecialchars($produto['description']) . "</td>";
            echo "<td style='display: flex; justify-content: center; gap: 40px;'>
                    <a style='color: black; cursor: pointer;' href='#' 
                    class='editar-btn view_data' id='" . htmlspecialchars($row['product_code']) . "' data-id='" . htmlspecialchars($row['product_code']) . "' data-nome='" . htmlspecialchars($row['name']) . "'> 
                        <i class='bi bi-pencil'></i>
                    </a>
                    <a style='color: black; cursor: pointer;' href='#' class='excluir-btn' data-id='" . htmlspecialchars($row['product_code']) . "' data-nome='" . htmlspecialchars($row['name']) . "'>
                        <i class='bi bi-trash'></i>
                    </a>
                </td>";
            echo "</tr>";

            require_once "excluirEditar.js";
        }
    } else {
        echo '<tr><td colspan="7" class="text-center">Nenhum produto encontrado</td></tr>';
    }
} catch (PDOException $e) {
    echo '<tr><td colspan="7" class="text-center">Erro ao buscar produtos: ' . $e->getMessage() . '</td></tr>';
}


