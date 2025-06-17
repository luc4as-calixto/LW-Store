<?php

// Inclua ou inicialize a conexão PDO antes de usar $conn
require_once 'conexao.php'; // ajuste o caminho conforme necessário

$id_seller = isset($_POST['id_seller']) ? $_POST['id_seller'] : null;

if ($id_seller === null) {
    echo json_encode(['erro' => 'ID do vendedor não informado.']);
    exit;
}

$sql = "SELECT * FROM sellers WHERE id_seller = :id_seller LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_seller', $id_seller, PDO::PARAM_INT);
$stmt->execute();
$vendedor = $stmt->fetch(PDO::FETCH_ASSOC);

if ($vendedor) {
    echo json_encode(['tipo' => 'vendedor', 'dados' => $vendedor]);
    exit;
} else {
    echo json_encode(['erro' => 'Vendedor não encontrado.']);
    exit;
}
