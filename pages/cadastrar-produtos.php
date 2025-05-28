<<<<<<< HEAD
    <div class="container mt-4">
        <h1>Cadastro de Produtos</h1>
        <form id="formProduto" action="formProduto" method="POST" enctype="multipart/form-data">

            <div class="row mb-3">
                <!-- Coluna da esquerda -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome do produto*" required>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Preço do Produto</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Digite o preço do produto*" required>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Digite a quantidade*" required>
                    </div>

                    <div class="mb-3">
                        <label for="prouct_code" class="form-label">Código</label>
                        <input type="text" class="form-control" id="product_code" name="product_code" placeholder="Digite o código do produto*" required>
                    </div>

                    <div class="mb-4">
                        <label for="type_packaging" class="form-label">Tipo da embalagem</label>
                        <select name="type_packaging" id="type_packaging" style="width: 365px; height: 40px; border-radius: 5px; border: 1px solid #ced4da;" class="form-select" required>
                            <option value="" disabled selected>Selecione o tipo de embalagem</option>
                            <option value="Caixa">Caixa</option>
                            <option value="Saco">Saco</option>
                            <option value="Lata">Lata</option>
                            <option value="Pacote">Pacote</option>
                            <option value="Garrafa">Garrafa</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>

                </div>

                <!-- Coluna da direita -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto do Produto</label>
                        <div class="d-flex align-items-center gap-3">
                            <!-- Input de arquivo -->
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" style="max-width: 250px;">

                            <!-- Pré-visualização da imagem -->
                            <div id="preview-container">
                                <?php if (!empty($_SESSION['photo']) && isset($_SESSION['photo'])): ?>
                                    <img src="../uploads/<?php echo $_SESSION['photo']; ?>" alt="Foto do Produto"
                                        width="100" height="100"
                                        style="object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                                <?php else: ?>
                                    <!-- Imagem padrão caso não tenha upload -->
                                    <img src="../uploads/produto-sem-imagem.webp" alt="Foto do Produto"
                                        width="100" height="100"
                                        style="object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea name="description" id="description" class="form-control" style="resize: none;" rows="11" placeholder="Descreva o produto aqui..." required></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-normal"><i class="bi bi-tags"></i> Cadastrar Produto</button>

            <div id="message" style="display: none;"></div>

        </form>
        <script src="../js/enviardados.js"></script>
    </div>
    <script>
        document.getElementById('photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const preview = document.createElement('img');
            preview.width = 100;
            preview.height = 100;
            preview.style.objectFit = 'cover';
            preview.style.borderRadius = '8px';
            preview.style.border = '1px solid #ccc';

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;

                const container = document.getElementById('preview-container');
                container.innerHTML = '';
                container.appendChild(preview);
            };
            reader.readAsDataURL(file);
        });
    </script>
=======
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

<div class="container my-4 pt-5">
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
>>>>>>> 20ac2e6b2499a3ebeeafd58f222a9160b3c45113
