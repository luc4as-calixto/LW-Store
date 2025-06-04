<div class="container mt-4" id="pagina">
    <h1>Relatórios de produtos</h1>

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
            <!-- linhas geradas pelo PHP atual (ou pode deixar vazio, vai preencher via AJAX) -->
            <?php include '../php/tabela_produtos.php'; ?>
        </tbody>

    </table>

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


<script>

    $(document).ready(function () {
    let produtoIdExcluir = null;

    $('#corpoTabelaProdutos').on('click', 'a.excluir-btn', function (e) {
        e.preventDefault();

        produtoIdExcluir = $(this).data('id');
        const nomeProduto = $(this).data('nome');

        $("#produtoExcluirNome").text(nomeProduto);

        const modal = new bootstrap.Modal(document.getElementById('modalConfirmExclusao'));
        modal.show();

        $("#message").hide().removeClass("success error").text("");
    });

    // Confirma exclusão
    $("#btnConfirmarExclusao").on("click", function () {
        if (!produtoIdExcluir) return;

        $.ajax({
            url: "../php/excluir_produto.php",
            type: "POST",
            data: { id: produtoIdExcluir },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#message").removeClass("error").addClass("success")
                        .text("Produto excluído com sucesso!").fadeIn();

                    setTimeout(function () {
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
            error: function (xhr, status, error) {
                console.error("Erro AJAX:", xhr, status, error);
                $("#message").removeClass("success").addClass("error")
                    .text("Erro na comunicação com o servidor.").fadeIn();
            }
        });
    });
});

// Função para atualizar a tabela sem recarregar a página
function atualizarTabelaProdutos() {
    $.ajax({
        url: '../php/tabela_produtos.php', // arquivo PHP que gera só as linhas da tabela (tbody)
        method: 'GET',
        success: function (html) {
            $('#corpoTabelaProdutos').html(html);
        },
        error: function () {
            alert('Erro ao atualizar a tabela de produtos.');
        }
    });
}




    // function excluirProduto(id, elemento) {
    //     if (confirm('Tem certeza que deseja excluir?')) {
    //         fetch('excluir_produto.php?id=' + id, {
    //                 method: 'GET'
    //             })
    //             .then(response => response.text())
    //             .then(data => {
    //                 const linha = elemento.closest('tr');
    //                 linha.remove();
    //                 alert('Produto excluído com sucesso!');

    //                 const tabela = document.querySelector('#form table');
    //                 const linhasRestantes = tabela.querySelectorAll('tr').length;

    //                 if (linhasRestantes === 1) { // só o cabeçalho
    //                     const novaLinha = document.createElement('tr');
    //                     novaLinha.innerHTML = "<td colspan='7'>Nenhum produto encontrado.</td>";
    //                     tabela.appendChild(novaLinha);
    //                 }

    //             })
    //             .catch(error => {
    //                 alert('Erro ao excluir o produto.');
    //                 console.error(error);
    //             });
    //     }
    // }

    // function carregar_pagina_editar(pagina, id) {
    //     const main = document.getElementById('main');

    //     fetch(`${pagina}.php?id=${id}`)
    //         .then(response => response.text())
    //         .then(html => {
    //             main.innerHTML = html;
    //         })
    //         .catch(error => {
    //             main.innerHTML = `<div class="alert alert-danger">Erro ao carregar a página.</div>`;
    //             console.error('Erro:', error);
    //         });
    // }
</script>