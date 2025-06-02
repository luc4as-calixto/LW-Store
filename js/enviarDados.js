$(document).ready(function () {
    var $FormLogin = $("#FormLogin")
    if ($FormLogin) {
        $("#FormLogin").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: "../php/valida_login.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#message").removeClass("error").addClass("success")
                            .text("Login realizado com sucesso").fadeIn();

                        setTimeout(() => {
                            window.location.href = response.redirect || "../pages/index.php";
                        }, 1000);
                    } else {
                        $("#message").removeClass("success").addClass("error")
                            .text(response.message).fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Erro AJAX", xhr, status, error);
                    $("#message").removeClass("success").addClass("error")
                        .text("Erro ao processar o login: " + error).fadeIn().delay(3000).fadeOut();
                }
            })
        })
    };
    
    var $formProduto = $("#formProduto");
    if ($formProduto)
    $("#formProduto").on("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: "../php/cadastrar_produto.php",
            type: "POST",
            data: formData,
            contentType: false,  // Necessário para enviar arquivos
            processData: false,  // Necessário para enviar arquivos
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#message").removeClass("error").addClass("success")
                        .text(response.message).fadeIn();

                    setTimeout(() => {
                        $("#message").fadeOut();
                        $("#formProduto")[0].reset();
                        $('#preview-container').html('<img src="../uploads/produto-sem-imagem.webp" width="100" height="100">');
                    }, 2000);

                } else {
                    $("#message").removeClass("success").addClass("error")
                        .text(response.error || response.message).fadeIn().delay(3000).fadeOut();
                }
            },
            error: function (xhr, status, error) {
                console.error("Erro AJAX", xhr, status, error);
                $("#message").removeClass("success").addClass("error")
                    .text("Erro ao cadastrar produto: " + error).fadeIn().delay(3000).fadeOut();
            }
        });
    });

    var $formVendedor = $("#formVendedor");
    if ($formVendedor) {
        $("#formVendedor").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: "../php/cadastrar_vendedor.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#message").removeClass("error").addClass("success")
                            .text(response.message).fadeIn();

                        setTimeout(() => {
                            $("#message").fadeOut();
                            $("#formVendedor")[0].reset();
                        }, 2000);

                    } else {
                        $("#message").removeClass("success").addClass("error")
                            .text(response.error || response.message).fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Erro AJAX", xhr, status, error);
                    $("#message").removeClass("success").addClass("error")
                        .text("Erro ao cadastrar vendedor: " + error).fadeIn().delay(3000).fadeOut();
                }
            });
        });
    }



})