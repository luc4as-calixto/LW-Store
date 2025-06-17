$(document).ready(function () {
    let vendedorExcluir = null;

    // exibe modal de confirmação de exclusão
    $("#corpoTabelaVendedores").on('click', 'a.excluir-btn', function (e) {
        alert($(this).data('id'));
        e.preventDefault();

        vendedorIdExcluir = $(this).data('id')
        const nomeVendedor = $(this).data('nome');

        $('#vendedorExcluirNome').text(nomeVendedor);


        const modal = new bootstrap.Modal(document.getElementById('modalConfirmExclusao'));
        modal.show();

        if (nomeVendedor == "administrador") {
            $('#message').removeClass('success error').addClass('error')
                .text('Não é possível excluir o usuário administrador.').fadeIn();
            return;
        }

        $('#message').hide().removeClass('success error').text('');
    });

    // Confirma exclusão
    $("#btnConfirmarExclusao").on("click", function () {
        if (!vendedorIdExcluir) return;

        $.ajax({
            url: "../php/excluir_vendedor.php",
            type: "POST",
            data: {
                id: vendedorIdExcluir
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#message").removeClass("error").addClass("success")
                        .text(response.success).fadeIn();

                    if (response.redirect) {
                        setTimeout(function () {
                            window.location.href = "../pages/login.php";
                        }, 2000);
                        return;
                    }

                    setTimeout(function () {
                        const modalEl = document.getElementById('modalConfirmExclusao');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();

                        atualizarTabelaVendedores();
                    }, 2000);
                } else {
                    $("#message").removeClass("success").addClass("error")
                        .text(response.error || "Erro ao excluir o vendedor.").fadeIn();
                }
            }

        })

    });

    let fotoOriginal = '';

    $(document).on('click', '.editar-btn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (id !== "") {
            $.ajax({
                url: '../php/painel_editar_vendedor.php',
                type: 'POST',
                data: { id_seller: id },
                dataType: 'json',
                success: function (retorno) {
                    if (retorno.erro) {
                        console.error(retorno.erro);
                        return;
                    }

                    var vendedor = retorno.dados;
                    fotoOriginal = vendedor.photo || '../uploads/sem-foto.webp';

                    var vendedor = retorno.dados;

                    $('#id_seller').val(vendedor.id_seller);
                    $('#vendedorEditar').text(vendedor.name);
                    $('#name').val(vendedor.name);
                    $('#email').val(vendedor.email);
                    $('#cpf').val(vendedor.cpf);
                    $('#telephone').val(vendedor.telephone);
                    $('#address').val(vendedor.address);
                    $('#gender').val(vendedor.gender);
                    $('#birthdate').val(vendedor.birthdate);

                    // Atualiza a imagem
                    if (vendedor.photo && vendedor.photo !== '') {
                        $('#imagemAtual').attr('src', vendedor.photo);
                    } else {
                        $('#imagemAtual').attr('src', '../uploads/sem-foto.webp');
                    }

                    // Mostra o modal
                    var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
                    modal.show();

                    var $formVendedorEditar = $('#formVendedorEditar');
                    if ($formVendedorEditar) {
                        $formVendedorEditar.off('submit').on('submit', function (e) {
                            e.preventDefault();
                            
                            var formData = new FormData(this);

                            $.ajax({
                                url: '../php/editar_vendedor.php',
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success) {
                                        console.log(response);

                                        $("#message-modal-editar").removeClass("error").addClass("success")
                                            .text(response.message).fadeIn();

                                        setTimeout(() => {
                                            $("#message-modal-editar").fadeOut();
                                            modal.hide();
                                            atualizarTabelaVendedores(); // Corrigido para vendedores
                                        }, 2000);
                                    } else {
                                        $("#message-modal-editar").removeClass("success").addClass("error")
                                            .text(response.error || response.message).fadeIn().delay(3000).fadeOut();
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error("Erro AJAX:", xhr, status, error);
                                    $("#message").removeClass("success").addClass("error")
                                        .text("Erro na comunicação com o servidor.").fadeIn();
                                }
                            });
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Erro ao carregar dados do vendedor:", error);
                }
            });
        }
    });

    // Quando o modal é fechado
    $('#modalEditar').on('hidden.bs.modal', function () {
        // Limpa o input de arquivo
        $('#photo').val('');

        // Restaura a imagem original do vendedor
        const container = document.getElementById('preview-container');
        container.innerHTML = `
        <img id="imagemAtual" src="${fotoOriginal}" alt="Foto do Vendedor"
            width="100" height="100"
            style="object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
    `;
    });


    function atualizarTabelaVendedores(pagina = 1) {
        $.ajax({
            url: '../php/tabela_vendedores.php',
            type: 'GET',
            data: {
                pagina: pagina
            },
            dataType: 'html',
            success: function (data) {
                $('#corpoTabelaVendedores').html(data);
            },
            error: function (xhr, status, error) {
                console.error("Erro ao atualizar tabela de vendedores:", xhr, status, error);
                $("#message").removeClass("success").addClass("error")
                    .text("Erro ao carregar a tabela de vendedores.").fadeIn();
            }
        })

    }
});