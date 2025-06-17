<?php
header('Content-Type: application/json');
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_seller = $_POST['id'] ?? '';

    if (empty($id_seller)) {
        echo json_encode(['error' => 'ID do vendedor não fornecido.']);
        exit;
    }

    try {
        // Busca o ID do usuário vinculado ao vendedor
        $stmt = $conn->prepare("SELECT fk_id_user FROM sellers WHERE id_seller = :id_seller");
        $stmt->bindParam(':id_seller', $id_seller, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Vendedor não encontrado.']);
            exit;
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $fk_id_user = $result['fk_id_user'];

        // Verifica se o usuário é administrador
        $stmtAdmin = $conn->prepare("SELECT type_user FROM users WHERE id_user = :id_user");
        $stmtAdmin->bindParam(':id_user', $fk_id_user, PDO::PARAM_INT);
        $stmtAdmin->execute();

        $userData = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
        if ($userData && strtolower($userData['type_user']) === 'admin') {
            echo json_encode(['error' => 'Não é permitido excluir um administrador.']);
            exit;
        }

        // Começa a transação
        $conn->beginTransaction();

        // Exclui o vendedor
        $stmtDeleteSeller = $conn->prepare("DELETE FROM sellers WHERE id_seller = :id_seller");
        $stmtDeleteSeller->bindParam(':id_seller', $id_seller, PDO::PARAM_INT);
        $stmtDeleteSeller->execute();

        // Exclui o usuário
        $stmtDeleteUser = $conn->prepare("DELETE FROM users WHERE id_user = :id_user");
        $stmtDeleteUser->bindParam(':id_user', $fk_id_user, PDO::PARAM_INT);
        $stmtDeleteUser->execute();

        // Confirma a transação
        $conn->commit();

        // Verifica se o usuário logado é o que foi excluído
        if (isset($_SESSION['id_user']) && $_SESSION['id_user'] == $fk_id_user) {
            session_destroy();
            echo json_encode(['success' => 'Vendedor excluído com sucesso.', 'redirect' => true]);
        } else {
            echo json_encode(['success' => 'Vendedor excluído com sucesso.']);
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo json_encode(['error' => 'Erro ao excluir: ' . $e->getMessage()]);
    }
}
