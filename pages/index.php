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
    <link rel="stylesheet" href="../css/style-painel.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/styles_bootstrap.css">
</head>

<body>

    <!-- Barra superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-painel container-fluid" style="margin-top: -10px;">
            <div style="margin-right: 15px;" class="ms-auto dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?php echo $_SESSION['name'];  ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCadastroUsuario">
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
        <div class="text-center fw-bold py-3">
            <a href="../pages/index.php" style="text-decoration: none; color: white;"><i class="bi bi-speedometer2 me-2"></i> LW Store</a>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#cadastros" role="button" aria-expanded="false" aria-controls="cadastros">

                <span><i class="bi bi-folder"></i> Cadastros</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="cadastros">
                <a href="#" class="nav-link submenu" data-page="clientes">Clientes</a>
                <a href="#" class="nav-link submenu" data-page="vendedores">Vendedores</a>
                <a href="#" class="nav-link submenu" data-page="produtos">Produtos</a>
            </div>

            <!--Movimentos-->
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#movimentacoes" role="button" aria-expanded="false" aria-controls="movimentacao">

                <span><i class="bi-cart"></i> Movimentações</span>
                <i class="bi bi-chevron-down"></i>
            </a>

            <div class="collapse" id="movimentacoes">
                <a href="#" class="nav-link submenu" data-page="vendas">Vendas</a>
            </div>

            <div class="collapse" id="movimentacoes">
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


    <!-- ================================================================================================================= -->
    <!-- Precisa arrumar aqui , os card dos produtos estao ficando um em cima do outro e tbm estao ficando em baixo do nav -->
    <!-- ================================================================================================================= -->

    <!-- Conteúdo principal -->
    <div id="main">
        <div class="container my-4">
            <div class="row g-4">
                <!-- Produto -->
                <div class="col-sm-6 col-md-4 col-lg-3 produto">
                    <div class="card h-100 shadow-sm produto-card">
                        <img src="../uploads/sem-foto.jpg" class="card-img-top" alt="Produto">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Produto</h5>
                            <p class="card-text">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Doloremque asperiores, placeat dolorum iure.
                            </p>
                            <div class="mt-auto">
                                <p class="fw-bold text-primary fs-5">R$ 9.999,99</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 produto">
                    <div class="card h-100 shadow-sm produto-card">
                        <img src="../uploads/sem-foto.jpg" class="card-img-top" alt="Produto">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Produto</h5>
                            <p class="card-text">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Doloremque asperiores, placeat dolorum iure.
                            </p>
                            <div class="mt-auto">
                                <p class="fw-bold text-primary fs-5">R$ 9.999,99</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 produto">
                    <div class="card h-100 shadow-sm produto-card">
                        <img src="../uploads/sem-foto.jpg" class="card-img-top" alt="Produto">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Produto</h5>
                            <p class="card-text">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Doloremque asperiores, placeat dolorum iure.
                            </p>
                            <div class="mt-auto">
                                <p class="fw-bold text-primary fs-5">R$ 9.999,99</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 produto">
                    <div class="card h-100 shadow-sm produto-card">
                        <img src="../uploads/sem-foto.jpg" class="card-img-top" alt="Produto">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Produto</h5>
                            <p class="card-text">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Doloremque asperiores, placeat dolorum iure.
                            </p>
                            <div class="mt-auto">
                                <p class="fw-bold text-primary fs-5">R$ 9.999,99</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de dados do  usuário -->
    <div class="modal fade" id="modalCadastroUsuario" tabindex="-1" aria-labelledby="modalCadastroUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content width-modal">

                <form id="formCadUsuario" enctype="multipart/form-data">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalConfiguracoesLabel">Dados do Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Nome e Login na mesma linha -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nm_nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nm_nome" name="nm_nome" value="<?php echo $_SESSION['name'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="nm_login" class="form-label">Login</label>
                                <input type="text" class="form-control" id="nm_login" name="nm_login" value="<?php echo $_SESSION['login'] ?? ''; ?>">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="ds_email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="ds_email" name="ds_email" value="<?php echo $_SESSION['email'] ?? ''; ?>">
                        </div>

                        <!-- Senha -->
                        <div class="mb-3">
                            <label for="ds_password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="ds_password" name="senha" value="<?php echo $_SESSION['password'] ?? ''; ?>">
                        </div>

                        <!-- Foto de Perfil (quadrada) -->
                        <!-- Foto de Perfil (imagem ao lado do input file) -->
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <!-- Input de arquivo (lado esquerdo) -->
                            <div>
                                <label for="foto_perfil" class="form-label">Foto de Perfil</label>
                                <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*">
                            </div>

                            <!-- Imagem de pré-visualização (lado direito) -->
                            <div id="preview-container">
                                <?php if (!empty($_SESSION['photo']) && isset($_SESSION['photo'])): ?>
                                    <img src="../uploads/<?php echo $_SESSION['photo']; ?>" alt="Foto de Perfil"
                                        width="150" height="150"
                                        style="object-fit: cover; border-radius: 8px;">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Checkbox Administrador -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="inadim" name="inadmin" <?php echo ($_SESSION['type_user'] == "admin") ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="inadim">Administrador</label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Editar dados</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!--Fim do modal de Cadastro de usuários-->
    <script src="js/enviardados.js"></script>

</body>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

</script>

<script src="../js/script.js"></script>
<script src="js/enviardados.js"></script>

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