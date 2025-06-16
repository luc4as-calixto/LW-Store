<?php
require_once '../php/conexao.php';

try {
    $limite = 10;
    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $limite;
    $termo = isset($_GET['termo']) ? $_GET['termo'] : '';

    $sql = "SELECT * FROM product WHERE amount > 0";
    
    if (!empty($termo)) {
        $sql .= " AND (name LIKE :termo OR product_code LIKE :termo)";
    }
    
    $sql .= " ORDER BY product_code ASC LIMIT :limite OFFSET :offset";

    $stmt = $conn->prepare($sql);
    
    if (!empty($termo)) {
        $termoBusca = '%' . $termo . '%';
        $stmt->bindValue(':termo', $termoBusca);
    }
    
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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
        echo "<tr id='mensagem-vazio'><td colspan='7' class='text-center'>Nenhum produto encontrado.</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='7'>Erro: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>