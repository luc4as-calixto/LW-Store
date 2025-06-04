<?php
header('Content-Type: application/json');
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $product_code = $_POST['product_code'] ?? '';
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $type_packaging = $_POST['type_packaging'] ?? '';
        $description = $_POST['description'] ?? '';
        $photo = $_FILES['photo'] ?? null;


    }
    catch (PDOException $e) {
        echo json_encode(['error' => 'Erro ao atualizar o produto: ' . $e->getMessage()]);
        exit;
    }
}

?>