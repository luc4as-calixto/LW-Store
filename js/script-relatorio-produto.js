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
            data: {
                id: produtoIdExcluir
            },
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



    $(document).on('click', '.view_data', function () {
        var id = $(this).attr("id");
        // alert("ID do produto: " + id);
        // verifica se há valor na variável user_id
        if (id !== '') {
            var dados = {
                product_code: id
            }
            $.post('../php/painel_editar.php', dados, function (retorno) {
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
    });

    $("#filtro").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        var encontrou = false;

        // Mostra o botão "Limpar" se houver texto
        $("#btnLimparPesquisa").toggle(value.length > 0);

        $("#corpoTabelaProdutos tr").each(function () {
            if ($(this).attr("id") === "mensagem-vazio") return;

            var codigo = $(this).find("td:eq(0)").text().toLowerCase();
            var nome = $(this).find("td:eq(1)").text().toLowerCase();
            var corresponde = codigo.indexOf(value) > -1 || nome.indexOf(value) > -1;

            $(this).toggle(corresponde);
            if (corresponde) encontrou = true;
        });

        $("#mensagem-vazio").toggle(!encontrou);
    });

    // Funcionalidade do botão "Limpar"
    $("#btnLimparPesquisa").on("click", function () {
        $("#filtro").val(""); // limpa o input
        $("#btnLimparPesquisa").hide(); // esconde o botão

        // Mostra todas as linhas (menos a de "nenhum produto")
        $("#corpoTabelaProdutos tr").each(function () {
            if ($(this).attr("id") === "mensagem-vazio") {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });


    // Função para atualizar a tabela de produtos e a paginação
    function atualizarTabelaProdutos() {
        $.ajax({
            url: '../php/tabela_produtos.php',
            success: function (html) {
                $('#corpoTabelaProdutos').html(html);

                // Verifica se há alguma linha visível (sem contar a linha de mensagem)
                let encontrouProduto = $('#corpoTabelaProdutos tr').length > 0;

                if (!encontrouProduto) {
                    $('#corpoTabelaProdutos').html(`
                    <tr id="mensagem-vazio">
                        <td colspan="7" class="text-center">Nenhum produto encontrado.</td>
                    </tr>
                `);
                }
            },
            error: function () {
                alert('Erro ao atualizar a tabela de produtos.');
            }
        });
    }
});