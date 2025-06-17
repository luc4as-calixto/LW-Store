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

        if (!empty($_POST['password'])) {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'] ?? '';

            if ($password !== $confirm_password) {
                echo json_encode(['error' => 'As senhas não coincidem.']);
                exit;
            }

            $new_password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $new_password = $_SESSION['password']; // mantém senha atual (já com hash)
        }

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $pasta = "../uploads/users/";
            $nome_original = basename($_FILES["photo"]["name"]);
            $extensao = pathinfo($nome_original, PATHINFO_EXTENSION);
            $novo_nome = uniqid() . "." . $extensao;
            $caminho_salvar = $pasta . $novo_nome;

            if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $caminho_salvar)) {
                echo json_encode(['error' => 'Erro ao salvar a foto no servidor.']);
                exit;
            }
        } else {
            $caminho_salvar = $_SESSION['photo'] ?? "../uploads/sem-foto.webp";
        }

        $sql = "SELECT id_user FROM users WHERE login = :login";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['id_user'] != $id_user) {
            echo json_encode(['error' => 'Já existe um usuário com esse login.']);
            exit;
        }

        $sql = "UPDATE users SET login = :login, password = :password WHERE id_user = :id_user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $new_password);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "UPDATE sellers SET name = :name, email = :email, photo = :photo WHERE fk_id_user = :id_user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':photo', $caminho_salvar);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

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
