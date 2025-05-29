<?php
session_start();
require_once '../php/conexao.php';

header('Content-Type: application/json');

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE login = :login");
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && $user['password'] == $password && $user['login'] == $login){
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['cpf'] = $user['cpf'];
        $_SESSION['telephone'] = $user['telephone'];
        $_SESSION['address'] = $user['address'];
        $_SESSION['gender'] = $user['gender'];
        $_SESSION['birthdate'] = $user['birthdate'];
        $_SESSION['type_user'] = $user['type_user'];
        $_SESSION['logado'] = true;
        $_SESSION['photo'] = !empty($user['photo']) ? $user['photo'] : 'sem-foto.jpg';

        echo json_encode([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'redirect' => '../pages/index.php'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Usuário ou senha inválidos.'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro no banco de dados: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro inesperado: ' . $e->getMessage()
    ]);
}
?>
