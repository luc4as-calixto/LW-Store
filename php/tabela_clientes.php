<?php
require_once '../php/conexao.php';

try {
    $limite = 10;
    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $limite;

    $stmt = $conn->prepare("SELECT * FROM customers ORDER BY id_customer ASC LIMIT :limite OFFSET :offset");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id_customer']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cpf']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telephone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
            echo "<td>" . htmlspecialchars($row['birthdate']) . "</td>";
            echo "<td style='display: flex; justify-content: center; gap: 40px;'>
                <a style='color: black; cursor: pointer;' href='#' 
                class='editar-btn view_data' id='" . htmlspecialchars($row['id_customer']) . "' data-id='" . htmlspecialchars($row['id_customer']) . "' data-nome='" . htmlspecialchars($row['name']) . "'> 
                <i class='bi bi-pencil'></i>
                </a>
                <a style='color: black; cursor: pointer;' href='#' class='excluir-btn' data-id='" . htmlspecialchars($row['id_customer']) . "' data-nome='" . htmlspecialchars($row['name']) . "'>
                <i class='bi bi-trash'></i>
                </a>
            </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nenhum cliente cadastrado.</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='7'>Erro: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>