<?php

session_start();
require_once "../php/conexao.php";

?>

<div class="container mt-4" id="pagina">
    <h1>Relatórios de produtos</h1>

    <!-- Campo de pesquisa -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-sm-12 mb-2 mb-md-0">
            <div class="input-group">
                <input type="text" id="filtro" class="form-control mb-3" placeholder="Buscar na tabela...">
                <span style="width: 10px;"></span>
                <button class="btn btn-outline-secondary" id="btnLimparPesquisa" type="button" style="display: none;">
                    <i class="bi bi-x-circle"></i> Limpar
                </button>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Tipo de embalagem</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="corpoTabelaProdutos">
            <?php require_once "../php/tabela_produtos.php"; ?>
            <tr id="mensagem-vazio" style="display: none;">
                <td colspan="7" class="text-center">Nenhum produto encontrado.</td>
            </tr>
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-3">
        <?php require_once "../php/paginacao_produtos.php"; ?>
    </div>

</div>

<script src="../js/script-relatorio-produto.js"></script>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="modalConfirmExclusao" tabindex="-1" aria-labelledby="modalConfirmExclusaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content user-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmExclusaoLabel">Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center">
                <br>
                <h4>Tem certeza que deseja excluir este produto?</h4>
                <h5>Nome do produto: <strong id="produtoExcluirNome"></strong></h5>
                <br>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarExclusao">Excluir</button>
                <div id="message" style="display: none;"></div>
            </div>
            <!-- <div class="modal-footer justify-content-center">
            </div> -->
        </div>
    </div>
</div>

<!-- Modal de Editar-->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content user-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar</h5>
            </div>
            <div class="modal-body text-center">
                <h5>Nome do produto: <strong id="produtoEditar"></strong></h5>

                <form id="formProdutoEditar" method="POST" enctype="multipart/form-data">

                    <div class="row mb-3">
                        <!-- Coluna da esquerda -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label style="text-align:left" for="name" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome do produto*" value="<?php  ?>" required>
                            </div>

                            <div class="mb-3">
                                <label style="text-align:left" for="price" class="form-label">Preço do Produto</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Digite o preço do produto*" required>
                            </div>

                            <div class="mb-3">
                                <label style="text-align:left" for="amount" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Digite a quantidade*" required>
                            </div>

                            <div class="mb-3">
                                <label style="text-align:left" for="product_code" class="form-label">Código</label>
                                <input type="text" class="form-control" id="product_code" name="product_code" placeholder="Digite o código do produto*" required>
                            </div>

                            <!-- Adicione este campo oculto ao seu formulário -->
                            <input type="hidden" id="id" name="id" value="">


                            <div class="mb-3">
                                <label style="text-align:left" for="type_packaging" class="form-label">Tipo da embalagem</label>
                                <select name="type_packaging" id="type_packaging" class="form-select" required>
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
                                <label style="text-align:left" for="photo" class="form-label">Foto do Produto</label>
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Input de arquivo -->
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">

                                    <!-- Pré-visualização da imagem -->
                                    <div id="preview-container">
                                        <img id="imagemAtual" src="../uploads/produto-sem-imagem.webp" alt="Foto do Produto"
                                            width="100" height="100"
                                            style="object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label style="text-align:left" for="description" class="form-label">Descrição</label>
                                <textarea name="description" id="description" class="form-control" style="resize: none;" rows="9" placeholder="Descreva o produto aqui..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <button id="btn-editar" type="submit" class="btn-normal"><i class="bi bi-tags"></i> Editar Produto</button>

                    <div id="message-modal-editar" style="display: none;"></div>

                </form>
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

        </div>
    </div>
</div>