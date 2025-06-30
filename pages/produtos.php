<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../pages/login.php');
    exit();
}
require_once '../php/conexao.php';

?>


<div class="container">
    <h2 class="my-4">Produtos</h2>

    <!-- Filtro de busca -->
    <div class="mb-4 d-flex gap-2">
        <input type="text" id="filtro" class="form-control" placeholder="Buscar produto...">
        <button class="btn btn-outline-secondary" id="btnLimparPesquisa" style="display: none;">Limpar</button>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <ul class="pagination">

        </ul>
    </div>

    <!-- Produtos em Grid -->
    <div class="row g-4" id="areaProdutos">

    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        <ul class="pagination">

        </ul>
    </div>
</div>

<script src="../js/script-paginacao-produtos.js"></script>

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
                    <div class="d-flex align-items-center gap-1">
                        <button class="btn btn-sm btn-outline-secondary" id="btnQtdMenos" type="button">−</button>
                        <span class="px-2" id="modalQtdValor">1</span>
                        <button class="btn btn-sm btn-outline-secondary" id="btnQtdMais" type="button">+</button>
                    </div>
                    <button type="button" class="btn btn-outline-success" id="btnAddModalCarrinho">
                        <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="../js/script-paginacao-produtos.js"></script>


<script>
    function verProduto(produto) {
        document.getElementById("modalProdutoTitulo").textContent = produto.nome;
        document.getElementById("modalProdutoImagem").src = produto.imagem;
        document.getElementById("modalProdutoDescricao").textContent = produto.descricao;
        document.getElementById("modalProdutoPreco").textContent = parseFloat(produto.preco).toFixed(2).replace('.', ',');

        // Inicializa quantidade
        let qtd = 1;
        const maxEstoque = produto.estoque;

        const spanQtd = document.getElementById("modalQtdValor");
        const btnMenos = document.getElementById("btnQtdMenos");
        const btnMais = document.getElementById("btnQtdMais");
        const btnAdd = document.getElementById("btnAddModalCarrinho");

        spanQtd.textContent = qtd;

        if (maxEstoque <= 0) {
            btnMais.disabled = true;
            btnMenos.disabled = true;
            btnAdd.disabled = true;
            btnAdd.innerHTML = `<i class="bi bi-x-circle"></i> Esgotado`;
            spanQtd.textContent = 0;
        } else {
            btnMais.disabled = false;
            btnMenos.disabled = false;
            btnAdd.disabled = false;
            btnAdd.innerHTML = `<i class="bi bi-cart-plus"></i> Adicionar ao Carrinho`;

            // Função para atualizar estado dos botões
            function atualizarEstadoBotoes() {
                btnMenos.disabled = qtd <= 1;
                btnMais.disabled = qtd >= maxEstoque;
            }

            atualizarEstadoBotoes();

            // Botão diminuir
            btnMenos.onclick = function() {
                if (qtd > 1) {
                    qtd--;
                    spanQtd.textContent = qtd;
                    atualizarEstadoBotoes();
                }
            };

            // Botão aumentar (com controle de estoque)
            btnMais.onclick = function() {
                if (qtd < maxEstoque) {
                    qtd++;
                    spanQtd.textContent = qtd;
                    atualizarEstadoBotoes();
                }
            };

            // Adicionar ao carrinho
            btnAdd.onclick = function() {
                adicionarAoCarrinho(produto.codigo, produto, qtd);
            };
        }

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalVerProduto"));
        modal.show();
    }
</script>