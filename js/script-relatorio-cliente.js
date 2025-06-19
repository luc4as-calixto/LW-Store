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

                        // Atualiza só a tabela para refletir a exclusão
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
                        formData.append('id_customer', response.id_customer); // Adiciona o ID do cliente

                        $.ajax({
                            url: '../php/editar_cliente.php',
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    $("#message").removeClass("error").addClass("success")
                                        .text(response.message).fadeIn();

                                    setTimeout(() => {
                                        $("#message").fadeOut();
                                        modal.hide();
                                        atualizarTabelaClientes();
                                    }, 2000);
                                } else {
                                    $("#message").removeClass("success").addClass("error")
                                        .text(response.error || response.message).fadeIn().delay(3000).fadeOut();
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Erro AJAX", xhr, status, error);
                                $("#message").removeClass("success").addClass("error")
                                    .text("Erro ao editar Cliente: " + error).fadeIn().delay(3000).fadeOut();
                            }
                        });
                    });
                }
            });
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


            },
            error: function () {
                alert('Erro ao atualizar a tabela de clientes.');
            }
        });
    }
});