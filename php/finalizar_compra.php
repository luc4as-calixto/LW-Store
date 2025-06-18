<?php
header('Content-Type: application/json');
session_start();
require_once "conexao.php";

try {
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

    $conn->beginTransaction();

    // 👉 Inserir na tabela sales
    $stmt = $conn->prepare("INSERT INTO sales (id_customer, id_user, date_sale) VALUES (:id_customer, :id_user, NOW())");
    $stmt->execute([
        ':id_customer' => $id_cliente,
        ':id_user' => $id_vendedor
    ]);

    $id_pedido = $conn->lastInsertId();

    if (!$id_pedido) {
        throw new Exception("Erro ao obter ID do pedido.");
    }

    // 👉 Inserir itens do pedido
    foreach ($itens as $item) {
        $codigo_produto = $item['codigo'];
        $quantidade = intval($item['qtd']);
        $preco_unitario = floatval($item['preco']);

        // 🔥 Verifica o estoque atual
        $stmtEstoque = $conn->prepare("SELECT amount FROM product WHERE product_id = :codigo");
        $stmtEstoque->bindParam(':codigo', $codigo_produto);
        $stmtEstoque->execute();
        $estoque = $stmtEstoque->fetchColumn();

        if ($estoque === false) {
            throw new Exception("Produto código {$codigo_produto} não encontrado.");
        }

        if ($estoque < $quantidade) {
            throw new Exception("Estoque insuficiente para o produto código {$codigo_produto}. Disponível: {$estoque}");
        }

        // 👉 Inserir na tabela sale_items
        $stmt = $conn->prepare("INSERT INTO sale_items (id_sale, product_id, quantity, price_unit)
                                VALUES (:id_sale, :product_id, :quantity, :price_unit)");
        $stmt->execute([
            ':id_sale' => $id_pedido,
            ':product_id' => $codigo_produto,
            ':quantity' => $quantidade,
            ':price_unit' => $preco_unitario
        ]);

        // 👉 Atualizar estoque
        $stmt = $conn->prepare("UPDATE product SET amount = amount - :qtd WHERE product_id = :codigo");
        $stmt->execute([
            ':qtd' => $quantidade,
            ':codigo' => $codigo_produto
        ]);
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Pedido finalizado com sucesso!',
        'id_pedido' => $id_pedido
    ]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}
?>
