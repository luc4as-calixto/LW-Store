<?php
require_once '../php/conexao.php';

try {
    $limite = 10;
    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $limite;
    $termo = isset($_GET['termo']) ? trim($_GET['termo']) : '';

    // SELECT com JOIN entre sellers e users
    $sql = "
        SELECT sellers.*, users.login 
        FROM sellers 
        LEFT JOIN users ON sellers.fk_id_user = users.id_user
    ";

    if (!empty($termo)) {
        $sql .= " WHERE sellers.name LIKE :termo 
                  OR sellers.id_seller LIKE :termo 
                  OR users.login LIKE :termo ";
    }

    $sql .= " ORDER BY sellers.id_seller ASC LIMIT :limite OFFSET :offset";

    $stmt = $conn->prepare($sql);

    if (!empty($termo)) {
        $termoBusca = "%$termo%";
        $stmt->bindValue(':termo', $termoBusca, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id_seller']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['login']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cpf']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telephone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
            echo "<td>" . htmlspecialchars($row['birthdate']) . "</td>";
            echo "<td class='text-center'>
                <a style='color: black; cursor: pointer;' href='#' 
                class='editar-btn view_data' id='" . htmlspecialchars($row['id_seller']) . "' data-id='" . htmlspecialchars($row['id_seller']) . "' data-nome='" . htmlspecialchars($row['name']) . "'> 
                <i class='bi bi-pencil'></i>
                </a>
                &nbsp &nbsp;
                <a style='color: black; cursor: pointer;' href='#' class='excluir-btn' data-id='" . htmlspecialchars($row['id_seller']) . "' data-nome='" . htmlspecialchars($row['name']) . "'>
                <i class='bi bi-trash'></i>
                </a>
            </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr id='mensagem-vazio'><td colspan='10' class='text-center'>Nenhum vendedor encontrado.</td></tr>";
    }

} catch (PDOException $e) {
    echo "<tr><td colspan='10'>Erro: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
