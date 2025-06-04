<?php

    header('Content-Type: application/json');
    session_start();
    require_once "conexao.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $product_code = $_POST['id'] ?? '';

        if (empty($product_code)) {
            echo json_encode(['error' => 'Código do produto não fornecido.']);
            exit;
        }

        $sql = "DELETE FROM product WHERE product_code = :product_code";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_code', $product_code, PDO::PARAM_STR);
        try {
            if ($stmt->execute()) {
                echo json_encode(['success' => 'Produto excluído com sucesso.']);
            } else {
                echo json_encode(['error' => 'Erro ao excluir o produto.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erro: ' . $e->getMessage()]);
        }

    }

?>