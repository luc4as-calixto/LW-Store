<?php

session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../pages/login.php');
    exit();
}

?>

<div class="container mt-4" id="pagina">
    <h1>Históricos de Vendas</h1>

    <table>
        <thead>
            <tr>
                <!-- <th>Código</th> -->
                <th>Código Vendas</th>
                <?php if ($_SESSION['type_user'] == 'admin') { ?>
                    <th>Vendedor</th>
                <?php } ?>
                <th>Nome dos Produtos</th>
                <th>Quantidade Total</th>
                <th>Preço Total</th>
            </tr>
        </thead>
        <tbody id="corpoTabelaProdutos">
            <?php require_once "../php/tabela_historico_vendas.php"; ?>
            <tr id="mensagem-vazio" style="display: none;">
                <td colspan="5" class="text-center">Nenhum produto encontrado.</td>
            </tr>
        </tbody>
    </table>

</div>
