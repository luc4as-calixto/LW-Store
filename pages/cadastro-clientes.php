<?php

session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../pages/login.php');
    exit();
}

?>

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
                    <label for="CPF" class="form-label">CPF</label>
                    <input type="text" class="form-control" id="CPF" name="CPF" placeholder="Digite o CPF*" required>
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
                    <label for="birthdate" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="Coloque a data de nascimento*" required>
                </div>
    
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Digite o email*" required>
                </div>

                <div class="mb-4">
                    <label for="photo" class="form-label">Foto do Cliente</label>
                    <div class="d-flex align-items-center gap-3">
                        <!-- Input de arquivo -->
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    </div>
                </div>

            </div>

            <!-- Coluna da direita -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" class="form-control" id="cep" name="cep" placeholder="Digite o CEP*" required>
                </div>

                <div class="mb-3">
                    <label for="rua" class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="rua" name="address" placeholder="Digite o endereço*" required readonly>
                </div>

                <div class="mb-3">
                    <label for="number" class="form-label">Número</label>
                    <input type="text" class="form-control" id="number" name="numberAddress" placeholder="Digite o número*" required>
                </div>

                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Digite o bairro*" required readonly>
                </div>

                <div class="mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="city" placeholder="Digite a cidade*" required readonly>
                </div>

                <div class="mb-3">
                    <label for="uf" class="form-label">Estado</label>
                    <input type="text" class="form-control" id="uf" name="state" placeholder="Digite o estado*" required readonly>
                </div>

                <!-- Pré-visualização da imagem -->
                <div id="preview-container"></div>

            </div>
        </div>
        <button id="btn" type="submit" class="btn-normal"><i class="bi bi-tags"></i> Cadastrar Cliente</button>

        <div id="message" style="display: none;"></div>

    </form>
</div>
<script src="../js/enviardadosCliente.js"></script>
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

    $(document).ready(function() {

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#rua").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
            }
            
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#rua").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#uf").val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#cep").val(cep);
                                $("#rua").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#uf").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                $("#message").removeClass("success").addClass("error")
                                    .text('CEP não encontrado.').fadeIn(500).delay(3000).fadeOut(500);
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        $("#message").removeClass("success").addClass("error")
                            .text('Formato de CEP inválido.').fadeIn().delay(3000).fadeOut(500);
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });
        
</script>