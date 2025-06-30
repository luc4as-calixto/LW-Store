<?php

session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../pages/login.php');
    exit();
}

?>

<div class="container mt-4" id="pagina">
    <h1>Históricos de Vendas</h1>

    <!-- Campo de pesquisa -->
    <!-- <div class="row align-items-center mb-4">
        <div class="col-md-6 col-sm-12 mb-2 mb-md-0">
            <div class="input-group">
                <input type="text" id="filtro" class="form-control mb-3" placeholder="Buscar na tabela...">
                <span style="width: 10px;"></span>
                <button class="btn btn-outline-secondary" id="btnLimparPesquisa" type="button" style="display: none;">
                    <i class="bi bi-x-circle"></i> Limpar
                </button>
            </div>
        </div>
    </div> -->

    <table>
        <thead>
            <tr>
                <!-- <th>Código</th> -->
                <th>Código Vendas</th>
                <th>Nome dos Produtos</th>
                <th>Quantidade</th>
                <th>Preço total</th>
            </tr>
        </thead>
        <tbody id="corpoTabelaProdutos">
            <?php require_once "../php/tabela_historico_vendas.php"; ?>
            <tr id="mensagem-vazio" style="display: none;">
                <td colspan="5" class="text-center">Nenhum produto encontrado.</td>
            </tr>
        </tbody>
    </table>

    <!-- Paginação -->
    <!-- <div class="d-flex justify-content-center mt-3">
        <?php require_once "../php/paginacao_produtos.php"; ?>
    </div> -->

</div>
