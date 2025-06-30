<?php
require_once 'conexao.php';

$limite = 9;
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina - 1) * $limite;
$termo = isset($_GET['termo']) ? trim($_GET['termo']) : '';

$where = '';
$params = [];
if ($termo !== '') {
    $where = "WHERE name LIKE :termo OR description LIKE :termo";
    $params[':termo'] = "%$termo%";
}

$totalStmt = $conn->prepare("SELECT COUNT(*) FROM product $where");
$totalStmt->execute($params);
$total = $totalStmt->fetchColumn();
$totalPaginas = ceil($total / $limite);

$stmt = $conn->prepare("SELECT * FROM product $where ORDER BY product_code DESC LIMIT :limite OFFSET :offset");
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (count($produtos) > 0): ?>
    <?php foreach ($produtos as $produto): ?>
        <div class="col-sm-6 col-md-3 produto">
            <div class="card h-100 shadow-sm produto-card">
                <img src="../uploads/<?= htmlspecialchars($produto['photo']); ?>" class="card-img-top" alt="<?= htmlspecialchars($produto['name']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($produto['name']); ?></h5>
                    <p class="card-text"><?= htmlspecialchars($produto['description']); ?></p>
                    <p class="card-text"><strong>R$ <?= number_format($produto['price'], 2, ',', '.'); ?></strong></p>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary btn-sm"
                            onclick='verProduto({
                                codigo: "<?= $produto['product_id']; ?>",
                                nome: "<?= addslashes($produto['name']); ?>",
                                preco: <?= floatval($produto['price']); ?>,
                                imagem: "<?= htmlspecialchars($produto['photo']); ?>",
                                descricao: "<?= addslashes($produto['description']); ?>",
                                estoque: <?= intval($produto['amount']); ?>
                            })'>
                            Ver Produto
                        </button>


                        <?php if ($produto['amount'] > 0): ?>
                            <a class="btn btn-success"
                                onclick='adicionarAoCarrinho("<?= $produto['product_id'] ?>", { 
                                nome: "<?= addslashes($produto['name']); ?>", 
                                preco: <?= floatval($produto['price']); ?>,
                                estoque: <?= intval($produto['amount']); ?>
                            })'>
                                <i class="bi bi-cart-plus"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-x-circle"></i> Esgotado
                            </button>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-12">
        <p class="text-center">Nenhum produto encontrado.</p>
    </div>
<?php endif; ?>