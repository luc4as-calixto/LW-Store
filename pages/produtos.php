<?php
require_once '../php/conexao.php';


// Quantidade de produtos por página
$limite = 16;

// Página atual (padrão: 1)
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Cálculo do OFFSET
$offset = ($pagina - 1) * $limite;

// Total de produtos (para calcular o número de páginas)
$totalProdutos = $conn->query("SELECT COUNT(*) FROM product")->fetchColumn();
$totalPaginas = ceil($totalProdutos / $limite);

// Buscar produtos da página atual
$stmt = $conn->prepare("SELECT * FROM product WHERE amount > 0 ORDER BY product_code DESC LIMIT :limite OFFSET :offset");

$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-4 pt-5" id="pagina-produtos">
    <div class="row g-4">
        <?php foreach ($produtos as $produto): ?>
            <?php
                $id = $produto['product_code'];
                $nome = htmlspecialchars($produto['name']);
                $preco = number_format($produto['price'], 2, ',', '.');
                $descricao = htmlspecialchars($produto['description']);
                $imagem = htmlspecialchars($produto['photo']);
            ?>
            <div class="col-sm-6 col-md-3 produto"><!-- col-md-3 = 4 colunas por linha -->
                <div class="card h-100 shadow-sm produto-card">
                    <img src="<?php echo $imagem; ?>" class="card-img-top" alt="<?php echo $nome; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $nome; ?></h5>
                        <p class="card-text"><?php echo $descricao; ?></p>
                        <p class="card-text"><strong>R$ <?php echo $preco; ?></strong></p>
                        <a href="produto.php?id=<?php echo $id; ?>" class="btn btn-primary">Ver Produto</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Paginação -->
    <nav aria-label="Navegação de página" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($pagina > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?pagina=<?php echo $pagina - 1; ?>">Anterior</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?php if ($i == $pagina) echo 'active'; ?>">
                    <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($pagina < $totalPaginas): ?>
                <li class="page-item">
                    <a class="page-link" href="?pagina=<?php echo $pagina + 1; ?>">Próxima</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
