<?php
header('Content-Type: application/json');
session_start();
require_once "conexao.php";

try {
    // Lê os dados enviados em JSON
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Dados inválidos.");
    }

    $id_cliente = intval($data['id_cliente']);
    $id_vendedor = intval($data['id_vendedor']);
    $itens = $data['itens'];

    if (empty($id_cliente) || empty($id_vendedor) || empty($itens)) {
        throw new Exception("Dados incompletos.");
    }

    // Inicia transação
    $conn->beginTransaction();

    // Inserir na tabela de pedidos (vendas)
    $stmt = $conn->prepare("INSERT INTO sales (id_customer, id_user, date_sale) VALUES (:id_customer, :id_user, NOW())");
    $stmt->bindParam(':id_customer', $id_cliente);
    $stmt->bindParam(':id_user', $id_vendedor);
    $stmt->execute();

    $id_pedido = $conn->lastInsertId();

    // Inserir os itens do pedido
    foreach ($itens as $item) {
        $codigo_produto = intval($item['codigo']);
        $quantidade = intval($item['qtd']);
        $preco_unitario = floatval($item['preco']);

        // 🔥 Verifica o estoque atual
        $stmtEstoque = $conn->prepare("SELECT amount FROM product WHERE product_code = :codigo");
        $stmtEstoque->bindParam(':codigo', $codigo_produto);
        $stmtEstoque->execute();
        $estoque = $stmtEstoque->fetchColumn();

        if ($estoque === false) {
            throw new Exception("Produto código {$codigo_produto} não encontrado.");
        }

        if ($estoque < $quantidade) {
            throw new Exception("Estoque insuficiente para o produto código {$codigo_produto}. Disponível: {$estoque}");
        }

        // 🔥 Inserir item na tabela de itens do pedido
        $stmtItem = $conn->prepare("
            INSERT INTO sale_items (id_sale, product_code, quantity, price_unit)
            VALUES (:id_sale, :product_code, :quantity, :price_unit)
        ");
        $stmtItem->execute([
            ':id_sale' => $id_pedido,
            ':product_code' => $codigo_produto,
            ':quantity' => $quantidade,
            ':price_unit' => $preco_unitario
        ]);

        // 🔥 Abater estoque
        $stmtUpdate = $conn->prepare("UPDATE product SET amount = amount - :qtd WHERE product_code = :codigo");
        $stmtUpdate->execute([
            ':qtd' => $quantidade,
            ':codigo' => $codigo_produto
        ]);
    }

    // Commit da transação
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Pedido finalizado com sucesso!',
        'id_pedido' => $id_pedido
    ]);

} catch (Exception $e) {
    // Rollback em caso de erro
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}
?>
