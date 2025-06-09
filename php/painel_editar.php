<?php

if (isset($_POST['product_code'])) {
    require_once '../php/conexao.php';

    $product_code = $_POST['product_code'];

    $sql = "SELECT * FROM product WHERE product_code = :product_code LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_code', $product_code, PDO::PARAM_STR);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($produto) {
        echo json_encode($produto);
    }
}
