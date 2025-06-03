<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../pages/login.php');
    exit();
}
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
    <!-- <link rel="stylesheet" href="../css/styles_bootstrap.css"> -->
</head>

<body style="padding-top: 65px !important;">

    <!-- Barra superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div id="logo" class="text-center fw-bold py-3">
            <a href="../pages/index.php"><i class="bi bi-speedometer2 me-2"></i> LW Store</a>
        </div>
        <div id="user-menu" class="container-fluid d-flex justify-content-end" style="margin-top: -6px;">
            <div style="margin-right: 15px;" class="ms-auto dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?php echo $_SESSION['name'];  ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalConfigUsuario">
                            <i class="bi bi-person me-2"></i> Editar Perfil
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="../php/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Menu Lateral -->
    <div id="sidebar">

        <nav class="nav flex-column pt-2">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#cadastros" role="button" aria-expanded="false" aria-controls="cadastros">
                <span><i class="bi bi-folder"></i> Cadastros</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="cadastros">
                <a href="#" class="nav-link submenu" data-page="clientes">Clientes</a>
                <a href="#" class="nav-link submenu" data-page="cadastro-vendedores">Vendedores</a>
                <a href="#" class="nav-link submenu" data-page="cadastro-produtos">Produtos</a>
            </div>
            <!--Movimentos-->
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#movimentacoes" role="button" aria-expanded="false" aria-controls="movimentacao">
                <span><i class="bi-cart"></i> Movimentações</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="movimentacoes">
                <a href="#" class="nav-link submenu" data-page="vendas">Vendas</a>
                <a href="#" class="nav-link submenu" data-page="historico-vendas">Histórico de Vendas</a>
            </div>
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#relatorios" role="button" aria-expanded="false" aria-controls="relatorios">
                <span><i class="bi bi-graph-up"></i> Relatórios</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="relatorios">
                <a href="#" class="nav-link submenu" data-page="relatoriocliente">Clientes</a>
                <a href="#" class="nav-link submenu" data-page="relatoriovendedores">Vendedores</a>
                <a href="#" class="nav-link submenu" data-page="relatorioproduto">Produtos</a>
            </div>
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#administracao" role="button" aria-expanded="false" aria-controls="administracao">
                <span><i class="bi bi-person-gear"></i>Administração</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="administracao">
                <a href="#" class="nav-link submenu" data-page="usuarios">Usuários</a>
                <a href="#" class="nav-link submenu" data-page="permissoes">Permissões</a>
                <a href="#" class="nav-link submenu" data-page="configuracoes">Configurações</a>
            </div>
        </nav>
    </div>

    <!-- Conteúdo principal -->
    <div id="main">
        <div class="dashboard-grid">
            <a href="#" class="dashboard-item submenu" data-page="vendas">
                <i class="bi bi-cart-plus"></i>
                <p>Vendas</p>
            </a>
            <a href="#" class="dashboard-item submenu" data-page="produtos">
                <i class="bi bi-box-seam"></i>
                <p>Produtos</p>
            </a>
            <a href="#" class="dashboard-item submenu" data-page="clientes">
                <i class="bi bi-people"></i>
                <p>Clientes</p>
            </a>
            <a href="#" class="dashboard-item submenu" data-page="relatorios">
                <i class="bi bi-bar-chart-line"></i>
                <p>Relatórios</p>
            </a>
            <a href="#" class="dashboard-item submenu" data-page="historico">
                <i class="bi bi-clock-history"></i>
                <p>Histórico</p>
            </a>
            <a href="#" class="dashboard-item submenu" data-page="financeiro">
                <i class="bi bi-cash-coin"></i>
                <p>Financeiro</p>
            </a>
            <a href="#" class="dashboard-item" data-bs-toggle="modal" data-bs-target="#modalConfigUsuario">
                <i class="bi bi-gear"></i>
                <p>Configurações</p>
            </a>
            <a href="#" class="dashboard-item submenu" data-page="suporte">
                <i class="bi bi-headset"></i>
                <p>Suporte</p>
            </a>
        </div>
    </div>





    <!-- Modal de dados do  usuário -->
    <div class="modal fade" id="modalConfigUsuario" tabindex="-1" aria-labelledby="modalConfigUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content user-modal-content">
                <form id="formConfigUsuario" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Dados do Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nm_nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nm_nome" name="name" value="<?php echo $_SESSION['name'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="login" class="form-label">Login</label>
                                <input type="text" class="form-control" id="login" name="login" value="<?php echo $_SESSION['login'] ?? ''; ?>">
                            </div>
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['email'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="ds_password" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="col-md-6">
                                <label for="ds_password" class="form-label">Confirme Sua Senha</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                            
                            <!-- Foto de Perfil e Upload -->
                            <div class="col-md-12 d-flex flex-column flex-md-row align-items-center justify-content-center gap-4">
                                <div class="w-100">
                                    <label for="foto_perfil" class="form-label">Foto de Perfil</label>
                                    <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*">
                                </div>
                                <div id="preview-container" class="text-center">
                                    <?php if (!empty($_SESSION['photo']) && isset($_SESSION['photo'])): ?>
                                        <img src="../uploads/<?php echo $_SESSION['photo']; ?>" alt="Foto de Perfil"
                                            class="preview-img">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="inadmin" name="inadmin" <?php echo ($_SESSION['type_user'] == "admin") ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="inadmin">Administrador</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Editar dados</button>
                    </div>
                    <div id="message" style="display: none;"></div>
                </form>
            </div>
        </div>
    </div>

    <!--Fim do modal de Cadastro de usuários-->
    <script src="js/enviardados.js"></script>
    <script src="js/script.js"></script>

</body>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

</script>

<script src="../js/script.js"></script>
<script src="../js/enviardados.js"></script>

</html>


<script>
    const toggleButton = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('show');
    });

    document.getElementById('foto_perfil').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const preview = document.createElement('img');
        preview.width = 240;
        // preview.classList.add('rounded-circle', 'mb-2');

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;

            // Remove pré-visualização anterior se houver
            const container = document.getElementById('preview-container');
            container.innerHTML = '';
            container.appendChild(preview);
        };
        reader.readAsDataURL(file);
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const navbar = document.querySelector(".navbar");
        if (navbar) {
            console.log("Altura da navbar:", navbar.offsetHeight + "px");
        }
    });
</script>