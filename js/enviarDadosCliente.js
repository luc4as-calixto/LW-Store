$(document).ready(function () {
    var $formCliente = $("#formCliente");
    if ($formCliente) {
        $("#formCliente").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: "../php/cadastrar_clientes.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#message").removeClass("error").addClass("success")
                            .text(response.message).fadeIn();

                        setTimeout(() => {
                            $("#message").fadeOut();
                            $("#formCliente")[0].reset();
                        }, 2000);

                    } else {
                        $("#message").removeClass("success").addClass("error")
                            .text(response.error || response.message).fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Erro AJAX", xhr, status, error);
                    $("#message").removeClass("success").addClass("error")
                        .text("Erro ao cadastrar cliente: " + error).fadeIn().delay(3000).fadeOut();
                }
            });
        });
    }
});