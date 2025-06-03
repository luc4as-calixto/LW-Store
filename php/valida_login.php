<?php
session_start();
require_once '../php/conexao.php';

// Cria o usuário admin se não existir
try {
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE login = :login");
    $checkStmt->execute([':login' => 'admin']);
    if ($checkStmt->fetchColumn() == 0) {
        $hashedPassword = password_hash('admin', PASSWORD_DEFAULT);
        $insertStmt = $conn->prepare("INSERT INTO users (login, name, password, email, cpf, telephone, address, gender, birthdate, type_user, photo)
            VALUES (:login, :name, :password, :email, :cpf, :telephone, :address, :gender, :birthdate, :type_user, :photo)");
        $insertStmt->execute([
            ':login' => 'admin',
            ':name' => 'administrador',
            ':password' => $hashedPassword,
            ':email' => 'admin@admin.com',
            ':cpf' => '12345678901',
            ':telephone' => '1234567890',
            ':address' => '123 Main St',
            ':gender' => 'M',
            ':birthdate' => '2000-01-01',
            ':type_user' => 'admin',
            ':photo' => null
        ]);
    }
} catch (PDOException $e) {
    // Você pode logar o erro se necessário
}

header('Content-Type: application/json');

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE login = :login");
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password']) && $user['login'] == $login) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['password'] = $user['password'];
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
