$(document).ready(function() {
    var $FormLogin = $("#FormLogin")
    if ($FormLogin) {
        $("#FormLogin").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url:"../php/valida_login.php",
                type: "POST",
                data: $(this).serialize(),
                dataType:"json",
                success: function(response) {
                    if (response.success) {
                        $("#message").removeClass("error").addClass("success")
                            .text("Login realizado com sucesso").fadeIn();
                        
                        setTimeout(() =>{
                            window.location.href = response.redirect || "../pages/index.php";
                        }, 1000);
                    } else {
                        $("#message").removeClass("success").addClass("error")
                            .text(response.message).fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro AJAX", xhr, status, error);
                    $("#message").removeClass("success").addClass("error")
                        .text("Erro ao processar o login: " + error).fadeIn().delay(3000).fadeOut();
                }
            })
        })
    };

    
})