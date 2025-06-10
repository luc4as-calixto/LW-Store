<?php

session_start();
require_once "../php/conexao.php";

// Quantidade de produtos por página
$limite = 5;

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

<div class="container mt-4" id="pagina">
    <h1>Relatórios de produtos</h1>

    <!-- Campo de pesquisa -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="pesquisaProduto" class="form-control" placeholder="Pesquisar produtos...">
                <button class="btn btn-primary" type="button" id="btnPesquisar">
                    <i class="bi bi-search"></i> Pesquisar
                </button>
                <button class="btn btn-secondary" type="button" id="btnLimparPesquisa" style="display:none;">
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
            <?php include '../php/tabela_produtos.php'; ?>
        </tbody>

    </table>

    <!-- Div da paginação -->
    <nav aria-label="Navegação de página" class="mt-4">
        <div id="paginacaoProdutos">
            <!-- Paginação será carregada aqui -->
        </div>
    </nav>


</div>

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

<!-- modal de edição so falta mostra os dados e atualizar ele -->

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

                    <div id="message" style="display: none;"></div>

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


<script>
    $(document).ready(function() {
        let produtoIdExcluir = null;

        $('#corpoTabelaProdutos').on('click', 'a.excluir-btn', function(e) {
            e.preventDefault();

            produtoIdExcluir = $(this).data('id');
            const nomeProduto = $(this).data('nome');

            $("#produtoExcluirNome").text(nomeProduto);

            const modal = new bootstrap.Modal(document.getElementById('modalConfirmExclusao'));
            modal.show();

            $("#message").hide().removeClass("success error").text("");
        });

        // Confirma exclusão
        $("#btnConfirmarExclusao").on("click", function() {
            if (!produtoIdExcluir) return;

            $.ajax({
                url: "../php/excluir_produto.php",
                type: "POST",
                data: {
                    id: produtoIdExcluir
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#message").removeClass("error").addClass("success")
                            .text("Produto excluído com sucesso!").fadeIn();

                        setTimeout(function() {
                            // Fecha o modal
                            const modalEl = document.getElementById('modalConfirmExclusao');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            modal.hide();

                            // Atualiza só a tabela para refletir a exclusão
                            atualizarTabelaProdutos();

                        }, 2000);
                    } else {
                        $("#message").removeClass("success").addClass("error")
                            .text(response.error || "Erro ao excluir o produto.").fadeIn();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro AJAX:", xhr, status, error);
                    $("#message").removeClass("success").addClass("error")
                        .text("Erro na comunicação com o servidor.").fadeIn();
                }
            });
        });
    });

    //modal de edição
    $(document).ready(function() {
        $(document).on('click', '.view_data', function() {
            var id = $(this).attr("id");
            // alert("ID do produto: " + id);
            // verifica se há valor na variável user_id
            if (id !== '') {
                var dados = {
                    product_code: id
                }
                $.post('../php/painel_editar.php', dados, function(retorno) {
                    var produto = JSON.parse(retorno);

                    $('#name').val(produto.name);
                    $('#price').val(produto.price);
                    $('#amount').val(produto.amount);
                    $('#type_packaging').val(produto.type_packaging);
                    $('#description').val(produto.description);
                    $('#product_code').val(produto.product_code);

                    if (produto.photo && produto.photo !== '') {
                        $('#imagemAtual').attr('src', '../uploads/' + produto.photo);
                    } else {
                        $('#imagemAtual').attr('src', '../uploads/produto-sem-imagem.webp');
                    }

                    var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    modal.show();
                });
            }
        })
    })

    // Função para atualizar a tabela de produtos e a paginação
    function atualizarTabelaProdutos(pagina = 1) {
        const limite = <?php echo $limite; ?>;

        // Atualiza tabela
        $.ajax({
            url: '../php/tabela_produtos.php',
            method: 'GET',
            data: {
                pagina,
                limite
            },
            success: function(html) {
                $('#corpoTabelaProdutos').html(html);
            },
            error: function() {
                alert('Erro ao atualizar a tabela de produtos.');
            }
        });

        // Atualiza paginação
        $.ajax({
            url: '../php/paginacao_produtos.php',
            method: 'GET',
            data: {
                pagina,
                limite
            },
            success: function(html) {
                $('#paginacaoProdutos').html(html);
            },
            error: function() {
                alert('Erro ao atualizar a paginação.');
            }
        });
    }

    // Função para pesquisar produtos
    function pesquisarProdutos(termo, pagina = 1) {
        const limite = <?php echo $limite; ?>;

        $.ajax({
            url: '../php/pesquisa_produtos.php',
            method: 'GET',
            data: {
                termo: termo,
                pagina: pagina,
                limite: limite
            },
            success: function(html) {
                $('#corpoTabelaProdutos').html(html);

                // Atualiza a paginação para a pesquisa
                $.ajax({
                    url: '../php/paginacao_produtos.php',
                    method: 'GET',
                    data: {
                        termo: termo,
                        pagina: pagina,
                        limite: limite
                    },
                    success: function(html) {
                        $('#paginacaoProdutos').html(html);
                    }
                });
            },
            error: function() {
                alert('Erro ao pesquisar produtos.');
            }
        });
    }

    // Evento de clique no botão pesquisar
    $(document).on('click', '#btnPesquisar', function() {
        const termo = $('#pesquisaProduto').val().trim();
        if (termo) {
            pesquisarProdutos(termo);
            $('#btnLimparPesquisa').show();
        }
    });

    // Evento de pressionar Enter no campo de pesquisa
    $(document).on('keypress', '#pesquisaProduto', function(e) {
        if (e.which === 13) { // Tecla Enter
            const termo = $('#pesquisaProduto').val().trim();
            if (termo) {
                pesquisarProdutos(termo);
                $('#btnLimparPesquisa').show();
            }
        }
    });

    // Evento para limpar a pesquisa
    $(document).on('click', '#btnLimparPesquisa', function() {
        $('#pesquisaProduto').val('');
        $(this).hide();
        atualizarTabelaProdutos(1); // Volta para a primeira página
    });

    // Chama a paginação ao carregar a página
    $(document).ready(function() {
        atualizarTabelaProdutos(<?php echo $pagina; ?>);
    });
</script>