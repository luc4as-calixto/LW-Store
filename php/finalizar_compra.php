<?php
header('Content-Type: application/json');
session_start();
require_once 'conexao.php';

try {
    // Recebe o JSON do carrinho
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!isset($data['itens']) || count($data['itens']) === 0) {
        throw new Exception("Carrinho vazio.");
    }

    if (!isset($data['id_cliente']) || empty($data['id_cliente'])) {
        throw new Exception("Cliente não selecionado.");
    }

    $id_cliente = $data['id_cliente'];
    $data_pedido = date("Y-m-d H:i:s");

    // 1. Cria o pedido
    $sql = "INSERT INTO pedidos (id_cliente, data_pedido) VALUES (:id_cliente, :data_pedido)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->bindParam(':data_pedido', $data_pedido);
    $stmt->execute();

    $id_pedido = $conn->lastInsertId();

    // 2. Insere os itens do pedido
    $sql_item = "INSERT INTO pedido_itens (id_pedido, nome_produto, preco, quantidade) 
                 VALUES (:id_pedido, :nome, :preco, :qtd)";
    $stmt_item = $conn->prepare($sql_item);

    foreach ($data['itens'] as $item) {
        $stmt_item->execute([
            ':id_pedido' => $id_pedido,
            ':nome'      => $item['nome'],
            ':preco'     => $item['preco'],
            ':qtd'       => $item['qtd']
        ]);
    }

    echo json_encode(['success' => true, 'message' => 'Pedido finalizado com sucesso!']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
