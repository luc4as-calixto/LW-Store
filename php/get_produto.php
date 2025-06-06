<!--  nao sei se via precisar disso aqui
   era para mostrar no modal -->


<?php
require_once '../php/conexao.php'; // ou seu arquivo de conexão

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM product WHERE product_code = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        echo json_encode(['success' => true, 'data' => $produto]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Produto não encontrado.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID inválido.']);
}
?>
