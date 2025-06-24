<?php
require_once '../php/conexao.php';

try {
    $limite = 10;
    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $limite;

    // Removido o filtro WHERE amount > 0
    $stmt = $conn->prepare("SELECT * FROM product ORDER BY (amount = 0), product_code ASC LIMIT :limite OFFSET :offset");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $classe = ($row['amount'] == 0) ? "class='esgotado'" : "";

            echo "<tr $classe>";
            echo "<td>" . htmlspecialchars($row['product_code']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>R$ " . number_format($row['price'], 2, ',', '.') . "</td>";
            echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
            echo "<td>" . htmlspecialchars($row['type_packaging']) . "</td>";
            echo "<td class='descricao'>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td class='text-center'>
                <a style='color: black; cursor: pointer;' href='#' 
                class='acoes editar-btn view_data' id='" . htmlspecialchars($row['product_code']) . "' data-id='" . htmlspecialchars($row['product_code']) . "' data-nome='" . htmlspecialchars($row['name']) . "'> 
                <i class='bi bi-pencil'></i>
                </a>
                &nbsp;&nbsp;
                <a style='color: black; cursor: pointer;' href='#' class='acoes excluir-btn' data-id='" . htmlspecialchars($row['product_code']) . "' data-nome='" . htmlspecialchars($row['name']) . "'>
                <i class='bi bi-trash'></i>
                </a>
            </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nenhum produto cadastrado.</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='7'>Erro: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
