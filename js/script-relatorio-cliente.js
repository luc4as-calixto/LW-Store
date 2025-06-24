$(document).ready(function () {
    let clienteIdExcluir = null;

    $('#corpoTabelaClientes').on('click', 'a.excluir-btn', function (e) {
        e.preventDefault();

        clienteIdExcluir = $(this).data('id');
        const nomeCliente = $(this).data('nome');

        $("#clienteExcluirNome").text(nomeCliente);

        const modal = new bootstrap.Modal(document.getElementById('modalConfirmExclusao'));
        modal.show();

        $("#message").hide().removeClass("success error").text("");
    });

    // Confirma exclusão
    $("#btnConfirmarExclusao").on("click", function () {
        if (!clienteIdExcluir) return;

        $.ajax({
            url: "../php/excluir_cliente.php",
            type: "POST",
            data: {
                id_customer: clienteIdExcluir
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#message").removeClass("error").addClass("success")
                        .text("Cliente excluído com sucesso!").fadeIn();

                    setTimeout(function () {
                        // Fecha o modal
                        const modalEl = document.getElementById('modalConfirmExclusao');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();
                        
                        atualizarTabelaClientes();

                    }, 2000);
                } else {
                    $("#message").removeClass("success").addClass("error")
                        .text(response.error || "Erro ao excluir o cliente.").fadeIn();
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
            $.post('../php/painel_editar_cliente.php', { id_customer: id }, function (data) {
                var response = JSON.parse(data);
                // Preenche os campos do modal com os dados do cliente
                $("#id_customer").val(response.id_customer);
                $("#name").val(response.name);
                $("#email").val(response.email);
                $("#cpf").val(response.cpf);
                $("#telephone").val(response.telephone);
                $("#address").val(response.address);
                $("#gender").val(response.gender);
                $("#birthdate").val(response.birthdate);

                if (response.photo && response.photo !== '') {
                    $('#imagemAtual').attr('src', response.photo);
                } else {
                    $('#imagemAtual').attr('src', '../uploads/sem-foto.webp');
                }

                var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
                modal.show();

                var $formClienteEditar = $('#formClienteEditar');
                if ($formClienteEditar) {
                    $formClienteEditar.off('submit').on('submit', function (e) {
                        e.preventDefault();

                        var formData = new FormData(this);

                        $.ajax({
                            url: "../php/editar_cliente.php",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    $("#message-modal-editar").removeClass("error").addClass("success")
                                        .text(response.message).fadeIn();

                                    setTimeout(() => {
                                        $("#message-modal-editar").fadeOut();
                                        modal.hide();
                                        atualizarTabelaClientes();
                                    }, 2000);
                                } else {
                                    $("#message-modal-editar").removeClass("success").addClass("error")
                                        .text(response.error || response.message).fadeIn().delay(3000).fadeOut();
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Erro AJAX", xhr, status, error);
                                $("#message-modal-editar").removeClass("success").addClass("error")
                                    .text("Erro ao editar Cliente: " + error).fadeIn().delay(3000).fadeOut();
                            }
                        });
                    });
                }
            });
        }
    });

    // Função para pesquisar Clientes
    function pesquisarClientes(termo, pagina = 1) {
        $.ajax({
            url: '../php/pesquisa_clientes.php',
            method: 'GET',
            data: {
                termo: termo,
                pagina: pagina
            },
            success: function (html) {
                $('#corpoTabelaClientes').html(html);

                // Atualiza a paginação para a pesquisa
                $.ajax({
                    url: '../php/paginacao_clientes.php',
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
                alert('Erro ao pesquisar clientes.');
            }
        });
    }

    // Evento de digitação no campo de pesquisa
    $('#filtro').on("keyup", function () {
        var termo = $(this).val().trim();
        $('#btnLimparPesquisa').toggle(termo.length > 0);
        
        if (termo.length > 0) {
            pesquisarClientes(termo);
        } else {
            atualizarTabelaClientes();
        }
    })

    // Evento para limpar a pesquisa
    $("#btnLimparPesquisa").on("click", function () {
        $("#filtro").val("");
        $(this).hide();
        atualizarTabelaClientes();
    });

    // Evento de clique nos links de paginação
    $(document).on('click', '.pagination-link', function (e) {
        e.preventDefault();
        const pagina = $(this).data('pagina');
        const termo = $(this).data('termo') || '';

        if (termo) {
            pesquisarClientes(termo, pagina);
        } else {
            atualizarTabelaClientes(pagina);
        }
    });

    // Função para atualizar a tabela de clientes
    function atualizarTabelaClientes(pagina = 1) {
        $.ajax({
            url: '../php/tabela_clientes.php',
            method: 'GET',
            data: { pagina: pagina },
            success: function (html) {
                $('#corpoTabelaClientes').html(html);

                // Atualiza a paginação
                $.ajax({
                    url: '../php/paginacao_clientes.php',
                    method: 'GET',
                    data: { pagina: pagina },
                    success: function (html) {
                        $('.pagination').html(html);
                    }
                });
            },
            error: function () {
                alert('Erro ao atualizar a tabela de clientes.');
            }
        });
    }
});