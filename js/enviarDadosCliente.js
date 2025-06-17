$(document).ready(function () {
    var $formCliente = $("#formCliente");
    var isSending = false; // Flag para controle de envio

    if ($formCliente.length) {
        $formCliente.on("submit", function (e) {
            e.preventDefault();

            if (isSending) return; // Impede envio duplicado

            isSending = true;
            $("#btn").prop("disabled", true);

            var formData = new FormData(this);

            $.ajax({
                url: "../php/cadastrar_clientes.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    isSending = false;
                    $("#btn").prop("disabled", false);

                    if (response.success) {
                        $("#message").removeClass("error").addClass("success")
                            .text(response.message).fadeIn();

                        setTimeout(() => {
                            $("#message").fadeOut();
                            $("#formCliente")[0].reset();
                            $("#preview-container").empty();
                        }, 2000);

                    } else {
                        $("#message").removeClass("success").addClass("error")
                            .text(response.error || response.message).fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function (xhr, status, error) {
                    isSending = false;
                    $("#btn").prop("disabled", false);
                    console.error("Erro AJAX", xhr, status, error);
                    $("#message").removeClass("success").addClass("error")
                        .text("Erro ao cadastrar cliente: " + error).fadeIn().delay(3000).fadeOut();
                }
            });
        });
    }
});
