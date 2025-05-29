    <div class="container mt-4" id="pagina">
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
            <button id="btn" type="submit" class="btn-normal"><i class="bi bi-tags"></i> Cadastrar Produto</button>

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