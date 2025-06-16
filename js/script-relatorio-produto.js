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
                $('#id').val(produto.product_id); // Campo oculto com o ID

                if (produto.photo && produto.photo !== '') {
                    $('#imagemAtual').attr('src', '../uploads/' + produto.photo);
                } else {
                    $('#imagemAtual').attr('src', '../uploads/produto-sem-imagem.webp');
                }

                var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
                modal.show();

                var $formProdutoEditar = $("#formProdutoEditar");
                if ($formProdutoEditar) {
                    $formProdutoEditar.off("submit").on("submit", function (e) {
                        e.preventDefault();

                        // Cria FormData para enviar arquivos
                        var formData = new FormData(this);

                        $.ajax({
                            url: "../php/editar_produto.php",
                            type: "POST",
                            data: formData,
                            processData: false, // Necessário para FormData
                            contentType: false, // Necessário para FormData
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    $("#message-modal-editar").removeClass("error").addClass("success")
                                        .text(response.message).fadeIn();

                                    setTimeout(() => {
                                        $("#message-modal-editar").fadeOut();
                                        modal.hide();
                                        atualizarTabelaProdutos();
                                    }, 2000);
                                } else {
                                    $("#message-modal-editar").removeClass("success").addClass("error")
                                        .text(response.error || response.message).fadeIn().delay(3000).fadeOut();
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Erro AJAX", xhr, status, error);
                                $("#message-modal-editar").removeClass("success").addClass("error")
                                    .text("Erro ao editar produto: " + error).fadeIn().delay(3000).fadeOut();
                            }
                        });
                    });
                }
            });
        }
    });

    // Função para pesquisar produtos
    function pesquisarProdutos(termo, pagina = 1) {
        $.ajax({
            url: '../php/pesquisa_produtos.php',
            method: 'GET',
            data: {
                termo: termo,
                pagina: pagina
            },
            success: function (html) {
                $('#corpoTabelaProdutos').html(html);

                // Atualiza a paginação para a pesquisa
                $.ajax({
                    url: '../php/paginacao_produtos.php',
                    method: 'GET',
                    data: {
                        termo: termo,
                        pagina: pagina
                    },
                    success: function (html) {
                        $('.pagination').html(html);
                    }
                });
            },
            error: function () {
                alert('Erro ao pesquisar produtos.');
            }
        });
    }

    // Evento de digitação no campo de pesquisa
    $("#filtro").on("keyup", function () {
        var termo = $(this).val().trim();
        $("#btnLimparPesquisa").toggle(termo.length > 0);

        if (termo.length > 0) {
            pesquisarProdutos(termo);
        } else {
            atualizarTabelaProdutos();
        }
    });

    // Evento para limpar a pesquisa
    $("#btnLimparPesquisa").on("click", function () {
        $("#filtro").val("");
        $(this).hide();
        atualizarTabelaProdutos();
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


    // Evento de clique nos links de paginação
    $(document).on('click', '.pagination-link', function (e) {
        e.preventDefault();
        const pagina = $(this).data('pagina');
        const termo = $(this).data('termo') || '';

        if (termo) {
            pesquisarProdutos(termo, pagina);
        } else {
            atualizarTabelaProdutos(pagina);
        }
    });

    // Modifique a função atualizarTabelaProdutos para aceitar página
    function atualizarTabelaProdutos(pagina = 1) {
        $.ajax({
            url: '../php/tabela_produtos.php',
            method: 'GET',
            data: { pagina: pagina },
            success: function (html) {
                $('#corpoTabelaProdutos').html(html);

                // Atualiza a paginação
                $.ajax({
                    url: '../php/paginacao_produtos.php',
                    method: 'GET',
                    data: { pagina: pagina },
                    success: function (html) {
                        $('.pagination').html(html);
                    }
                });
            },
            error: function () {
                alert('Erro ao atualizar a tabela de produtos.');
            }
        });
    }
});