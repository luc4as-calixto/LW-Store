<?php
    session_start();
    $_SESSION['logado'] = false;
?>



<!doctype html>
<html lang="pt-br">

<head>
    <title>LW Store</title>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style-painel.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/styles_bootstrap.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body style="background: linear-gradient(135deg, #fdfcfb, #d4f5f5, #89c4c4);"> 
    <div class="container-painel painel-login">
        <div class="row">
            <div class="col-12">
                <h1>Acessar conta</h1>
                <form id="FormLogin" method="post">
                    <div class="label-input">
                        <label for="login">Login</label>
                        <input type="text" id="login" name="login" autocomplete="username" placeholder="Insira seu login" required>
                    </div>

                    <div class="label-input">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" autocomplete="current-password" placeholder="Insira sua senha" required>
                    </div>

                    <button type="submit" class="btn-normal"><i class="bi bi-box-arrow-in-right"></i> Login</button>

                    <div id="message" style="display: none;"></div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<style>
    .erro {
        border: 1px solid red !important;
        box-shadow: 0 0 50px #f1424233 !important;
    }
</style>

<script>
    const login = document.getElementById('login');
    const password = document.getElementById('password');

    login.addEventListener('blur', (event) => {
        if (login.value === '') {
            login.classList.add('erro');
        } else {
            login.classList.remove('erro');
        }
    });

    password.addEventListener('blur', (event) => {
        if (password.value === '') {
            password.classList.add('erro');
        } else {
            password.classList.remove('erro');
        }
    });

</script>

<script src="../js/enviarDados.js"></script>