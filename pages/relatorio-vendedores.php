    <?php

    session_start();
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header('Location: ../pages/login.php');
        exit();
    }
    require_once "../php/conexao.php";

    ?>

    <div class="container mt-4" id="pagina">
        <h1>Relatórios de vendedores</h1>

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
                    <th>Login</th>
                    <th>CPF</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>Gênero</th>
                    <th>Aniversário</th>
                    <th>Ações</th>
                </tr>
            </thead>


            <tbody id="corpoTabelaVendedores">
                <?php include '../php/tabela_vendedores.php'; ?>
                <tr id="mensagem-vazio" style="display: none;">
                    <td colspan="10" class="text-center">Nenhum Vendedor encontrado.</td>
                </tr>
            </tbody>

        </table>

        <!-- Paginação -->
        <div class="d-flex justify-content-center mt-3">
            <?php require_once "../php/paginacao_vendedor.php"; ?>
        </div>

    </div>

    <script src="../js/script-relatorio-vendedores.js"></script>

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
                    <h4>Tem certeza que deseja excluir este vendedor?</h4>
                    <h5>Nome do vendedor: <strong id="vendedorExcluirNome"></strong></h5>
                    <br>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarExclusao">Excluir</button>
                    <div id="message" style="display: none;"></div>
                </div>
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
                <div class="modal-body">
                    <h5>Nome do vendedor: <strong id="vendedorEditar"></strong></h5>

                    <form id="formVendedorEditar" method="POST" enctype="multipart/form-data">

                        <div class="row mb-3">
                            <!-- Coluna da esquerda -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label style="text-align:left" for="name" class="form-label">Nome do vendedor</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome do vendedor*" value="<?php  ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label style="text-align:left" for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite o CPF do vendedor*" value="<?php  ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label style="text-align:left" for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite o email do vendedor*" value="<?php  ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label style="text-align:left" for="telephone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Digite o telefone do vendedor*" value="<?php  ?>" required>
                                </div>

                            </div>

                            <!-- Coluna da direita -->
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label style="text-align:left" for="gender" class="form-label">Gênero</label>
                                    <select name="gender" id="gender" class="form-select" required>
                                        <option value="" disabled selected>Selecione o gênero do vendedor*</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Feminino</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label style="text-align:left" for="birthdate" class="form-label">Aniversário</label>
                                    <input type="date" class="form-control mt-1" id="birthdate" name="birthdate" value="<?php  ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label style="text-align:left" for="address" class="form-label">Endereço</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Digite o endereço do vendedor*" value="<?php  ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label style="text-align:left ; margin-top: 6px" for="photo" class="form-label">Foto do Vendedor</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Input de arquivo -->
                                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    </div>
                                    <!-- Pré-visualização da imagem -->
                                    <div class="mt-3" id="preview-container">
                                        <img id="imagemAtual" src="../uploads/sem-foto.webp" alt="Foto do Vendedor"
                                            width="100" height="100"
                                            style="object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                                    </div>
                                </div>
                                <input type="hidden" name="id_seller" id="id_seller" value="">
                            </div>
                        </div>
                        <button id="btn-editar" type="submit" class="btn-normal"><i class="bi bi-tags"></i> Editar Vendedor</button>

                        <div id="message-modal-editar" style="display: none;"></div>

                    </form>
                    <script src="../js/enviardados.js"></script>
                </div>
                <!-- <div class="modal-footer justify-content-center">
                </div> -->

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