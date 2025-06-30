<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $name = $_POST['name'] ?? $_SESSION['name'];
        $login = $_POST['login'] ?? $_SESSION['login'];
        $email = $_POST['email'] ?? $_SESSION['email'];
        $id_user = $_SESSION['id_user'];
        $photo = $_FILES['photo'] ?? null;

        // Senha
        if (!empty($_POST['password'])) {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'] ?? '';

            if ($password !== $confirm_password) {
                echo json_encode(['error' => 'As senhas não coincidem.']);
                exit;
            }

            $new_password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $new_password = $_SESSION['password']; // mantém a senha atual (já com hash)
        }

        // Upload da imagem
        $caminho_salvar = $_SESSION['photo']; // valor padrão: mantém imagem atual
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
            $novo_nome = uniqid() . "." . $extensao;

            // Caminhos
            $caminho_relativo = 'uploads/' . $novo_nome;
            $caminho_fisico = __DIR__ . '/../uploads/' . $novo_nome;

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $caminho_fisico)) {
                $caminho_salvar = $caminho_relativo;
                $_SESSION['photo'] = $caminho_salvar; // atualiza a sessão
            } else {
                echo json_encode(['error' => 'Erro ao salvar a foto no servidor.']);
                exit;
            }
        }

        // Verifica se já existe outro usuário com o mesmo login
        $sql = "SELECT id_user FROM users WHERE login = :login";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['id_user'] != $id_user) {
            echo json_encode(['error' => 'Já existe um usuário com esse login.']);
            exit;
        }

        // Atualiza tabela users
        $sql = "UPDATE users SET login = :login, password = :password WHERE id_user = :id_user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $new_password);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        // Atualiza tabela sellers
        $sql = "UPDATE sellers SET name = :name, email = :email, photo = :photo WHERE fk_id_user = :id_user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':photo', $caminho_salvar);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        // Atualiza sessão
        $_SESSION['name'] = $name;
        $_SESSION['login'] = $login;
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $new_password;
        $_SESSION['photo'] = $caminho_salvar;

        echo json_encode([
            'success' => true,
            'message' => 'Usuário atualizado com sucesso!'
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Erro ao processar a solicitação: ' . $e->getMessage()]);
    }
}
