$(document).ready(function () {
    var $formCliente = $("#formCliente");
    if ($formCliente.length) {
        $("#formCliente").on("submit", function (e) {
            e.preventDefault();
            
            // Desabilita o botão para evitar múltiplos cliques
            $("#btn").prop("disabled", true);
            
            // Cria FormData para enviar arquivos e dados
            var formData = new FormData(this);

            $.ajax({
                url: "../php/cadastrar_clientes.php",
                type: "POST",
                data: formData,
                processData: false,  // Importante para FormData
                contentType: false,   // Importante para FormData
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#message").removeClass("error").addClass("success")
                            .text(response.message).fadeIn();

                        setTimeout(() => {
                            $("#message").fadeOut();
                            $("#formCliente")[0].reset();
                            $("#preview-container").empty(); // Limpa a pré-visualização
                        }, 2000);

                    } else {
                        $("#message").removeClass("success").addClass("error")
                            .text(response.error || response.message).fadeIn().delay(3000).fadeOut();
                    }
                    $("#btn").prop("disabled", false); // Reabilita o botão
                },
                error: function (xhr, status, error) {
                    console.error("Erro AJAX", xhr, status, error);
                    $("#message").removeClass("success").addClass("error")
                        .text("Erro ao cadastrar cliente: " + error).fadeIn().delay(3000).fadeOut();
                    $("#btn").prop("disabled", false); // Reabilita o botão
                }
            });
        });
    }
});