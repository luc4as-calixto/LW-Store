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

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/styles_bootstrap.css">

    <link rel="icon" type="image/png" href="../assets/lwstore-cart-only.png">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body style="background: linear-gradient(135deg, #fdfcfb, #d4f5f5, #89c4c4); align-items:center; height:100vh; justify-content:center;">
    <div class="container-painel painel-login">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Acessar conta</h1>
                <form id="FormLogin" method="post">
                    <div class="label-input">
                        <label for="login-painel">Login</label>
                        <div class="input">
                            <i class="bi bi-person"></i>
                            <input type="text" id="login-painel" name="login" autocomplete="username" placeholder="Insira seu login" required>
                        </div>
                    </div>

                    <div class="label-input">
                        <label for="password-painel-login">Senha</label>
                        <div class="input">
                            <i class="bi bi-lock"></i>
                            <input type="password" id="password-painel-login" name="password" autocomplete="current-password" placeholder="Insira sua senha" required>

                            <button type="button" id="togglePassword" tabindex="-1">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button id="btn" type="submit" class="btn-normal"><i class="bi bi-box-arrow-in-right"></i> Entrar</button>

                </form>

                <div id="message" style="display: none;"></div>
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
    // Deixa vermelho o campo de login e senha quando estiverem vazios
    // e remove o vermelho quando o usuário digitar algo

    const login = document.getElementById('login-painel');
    const password = document.getElementById('password-painel-login');

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

    // Olho da senha para mostrar/esconder a senha

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password-painel-login');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });

</script>

<script src="../js/enviarDados.js"></script>