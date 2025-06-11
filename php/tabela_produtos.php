<?php
require_once '../php/conexao.php';

try {
    // Buscar todos os produtos com amount > 0, ordenados pelo código crescente
    $sql = "SELECT * FROM product WHERE amount > 0 ORDER BY CAST(product_code AS UNSIGNED) ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['product_code']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>R$ " . number_format($row['price'], 2, ',', '.') . "</td>";
            echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
            echo "<td>" . htmlspecialchars($row['type_packaging']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
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
        }
    } else {
        echo "<tr><td colspan='7'>Nenhum produto cadastrado.</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='7'>Erro: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>
