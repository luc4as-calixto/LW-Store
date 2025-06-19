<?php

    header('Content-Type: application/json');
    session_start();
    require_once "../php/conexao.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id_customer = $_POST['id_customer'] ?? '';

        if (empty($id_customer)) {
            echo json_encode(['error' => 'Código do cliente não fornecido.']);
            exit;
        }

        $sql = "DELETE FROM customers WHERE id_customer = :id_customer";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_customer', $id_customer, PDO::PARAM_STR);
        try {
            if ($stmt->execute()) {
                echo json_encode(['success' => 'Cliente excluído com sucesso.']);
            } else {
                echo json_encode(['error' => 'Erro ao excluir o cliente.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erro: ' . $e->getMessage()]);
        }

    }

?>