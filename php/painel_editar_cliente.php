<?php

if (isset($_POST['id_customer'])) {
    require_once '../php/conexao.php';

    $id_customer = $_POST['id_customer'];

    $sql = "SELECT * FROM customers WHERE id_customer = :id_customer LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_customer', $id_customer, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($cliente) {
        echo json_encode($cliente);
    } 
}
?>