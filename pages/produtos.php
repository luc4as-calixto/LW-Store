<?php
require_once '../php/conexao.php';


// Quantidade de produtos por página
$limite = 15;

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

<div class="container">
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
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-primary btn-sm"
                                onclick='verProduto({
                                                      nome: "<?php echo addslashes($nome); ?>",
                                                      preco: <?php echo floatval($produto["price"]); ?>,
                                                      imagem: "<?php echo $imagem; ?>",
                                                      descricao: "<?php echo addslashes($descricao); ?>"
                                                    })'>Ver Produto</button>

                            <a class="btn btn-success"
                                onclick='adicionarAoCarrinho({ nome: "<?php echo addslashes($nome); ?>", preco: <?php echo floatval($produto["price"]); ?> });'>
                                <i class="bi bi-cart-plus"></i>
                            </a>
                        </div>
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

<!-- Modal de Produto -->
<div class="modal fade" id="modalVerProduto" tabindex="-1" aria-labelledby="modalVerProdutoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content user-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProdutoTitulo">Detalhes do Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body d-flex flex-column flex-md-row gap-4 align-items-center">
                <img id="modalProdutoImagem" src="" alt="Imagem" class="img-fluid" style="max-width: 250px; border-radius: 8px;">
                <div>
                    <p id="modalProdutoDescricao"></p>
                    <p class="h5">Preço: R$ <span id="modalProdutoPreco"></span></p>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <div class="d-flex align-items-center gap-3 w-100 justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <label for="modalQtd" class="mb-0">Qtd:</label>
                        <input type="number" id="modalQtd" value="1" min="1" class="form-control form-control-sm" style="width: 70px;">
                    </div>
                    <button type="button" class="btn btn-outline-success" id="btnAddModalCarrinho">
                        <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    function verProduto(produto) {
        document.getElementById("modalProdutoTitulo").textContent = produto.nome;
        document.getElementById("modalProdutoImagem").src = produto.imagem;
        document.getElementById("modalProdutoDescricao").textContent = produto.descricao;
        document.getElementById("modalProdutoPreco").textContent = parseFloat(produto.preco).toFixed(2).replace('.', ',');

        // Armazena o produto para adicionar depois
        document.getElementById("btnAddModalCarrinho").onclick = function() {
            const qtd = parseInt(document.getElementById("modalQtd").value) || 1;
            adicionarAoCarrinho(produto, qtd);

        };


        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalVerProduto"));
        modal.show();
    }
</script>