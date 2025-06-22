<?php
require_once '../php/conexao.php';

try {
    $limite = 10;
    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $limite;
    $termo = isset($_GET['termo']) ? trim($_GET['termo']) : '';

    // Consulta base
    $sql = "SELECT * FROM customers WHERE 1=1";

    // Adiciona filtros se houver termo de busca
    if (!empty($termo)) {
        $sql .= " AND (LOWER(name) LIKE LOWER(:termo) 
                      OR cpf LIKE :termo 
                      OR LOWER(email) LIKE LOWER(:termo)
                      OR telephone LIKE :termo)";
    }

    $sql .= " ORDER BY name ASC LIMIT :limite OFFSET :offset";

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
            echo "<td>" . htmlspecialchars($row['id_customer']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cpf']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telephone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['birthdate'])) . "</td>";
            echo "<td class='text-center'>
                <a style='color: black; cursor: pointer;' href='#' 
                class='editar-btn view_data' id='" . $row['id_customer'] . "' 
                data-id='" . $row['id_customer'] . "' data-nome='" . htmlspecialchars($row['name']) . "'>
                    <i class='bi bi-pencil'></i>
                </a>
                &nbsp;&nbsp;
                <a style='color: black; cursor: pointer;' href='#' 
                class='excluir-btn' data-id='" . $row['id_customer'] . "' 
                data-nome='" . htmlspecialchars($row['name']) . "'>
                    <i class='bi bi-trash'></i>
                </a>
            </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr id='mensagem-vazio'><td colspan='9' class='text-center'>Nenhum cliente encontrado.</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='9'>Erro na consulta: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
