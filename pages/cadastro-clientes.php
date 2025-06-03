<div class="container mt-4" id="pagina">
        <h1>Cadastro de Cliente</h1>
        <form id="formCliente" method="POST" enctype="multipart/form-data">

            <div class="row mb-3">
                <!-- Coluna da esquerda -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Cliente</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome do clietne*" required>
                    </div>

                    <div class="mb-3">
                        <label for="login_cliente" class="form-label">login do cliente</label>
                        <input type="text" class="form-control" id="login_cliente" name="login_cliente" placeholder="Digite o login do cliente*" required>
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Gênero</label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="" disabled selected>Selecione o gênero do cliente</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="telephone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Digite o telefone*" required>
                    </div>

                    <div class="mb-3">
                        <label for="create-password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="create-password" name="create-password" placeholder="Digite uma senha*" required>
                    </div>

                    <div class="mb-3">
                        <label for="password-confirmation" class="form-label">Confimação</label>
                        <input type="password" class="form-control" id="password-confirmation" name="password-confirmation" placeholder="Confirme a senha*" required>
                    </div>

                    <p style="text-align: left;">( * ) campos obrigatórios</p>

                </div>

                <!-- Coluna da direita -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label for="photo" class="form-label">Foto de Perfil</label>
                        <div class="d-flex align-items-center gap-3">   
                            <!-- Input de arquivo -->
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">

                            <!-- Pré-visualização da imagem -->
                            <div id="preview-container">
                                <?php if (!empty($_SESSION['photo']) && isset($_SESSION['photo'])): ?>
                                    <img src="../uploads/<?php echo $_SESSION['photo']; ?>" alt="Foto do cliente"
                                        width="100" height="100"
                                        style="object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                                <?php else: ?>
                                    <!-- Imagem padrão caso não tenha upload -->
                                    <img src="../uploads/sem-foto.jpg" alt="Foto do Produto"
                                        width="100" height="100"
                                        style="object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" style="margin-top: 40px;">
                        <label for="CPF" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="CPF" name="CPF" placeholder="Digite o CPF*" required>
                    </div>

                    <div class="mb-3">
                        <label for="birthdate" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="Coloque a data de nascimento*" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Digite o email*" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Digite o endereço*" required>
                    </div>
                    
                </div>
            </div>
            <button id="btn" type="submit" class="btn-normal"><i class="bi bi-tags"></i> Cadastrar Cliente</button>

            <div id="message" style="display: none;"></div>

        </form>
        <script src="../js/enviardadosCliente.js"></script>
    </div>
    <script>
        document.getElementById('photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const preview = document.createElement('img');
            preview.width = 100;
            preview.height = 100;
            preview.style.objectFit = 'cover';
            preview.style.borderRadius = '8px';
            preview.style.border = '1px solid #ccc';

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;

                const container = document.getElementById('preview-container');
                container.innerHTML = '';
                container.appendChild(preview);
            };
            reader.readAsDataURL(file);
        });
    </script>