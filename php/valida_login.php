<?php
session_start();
require_once '../php/conexao.php';

// Cria o usuário admin se não existir
try {
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE login = :login");
    $checkStmt->execute([':login' => 'admin']);
    if ($checkStmt->fetchColumn() == 0) {
        $hashedPassword = password_hash('admin', PASSWORD_DEFAULT);
        $insertStmt = $conn->prepare("INSERT INTO users (login, password, type_user)
            VALUES (:login, :password, :type_user)");
        $insertStmt->execute([
            ':login' => 'admin',
            ':password' => $hashedPassword,
            ':type_user' => 'admin',
        ]);

        $insertStmtSeller = $conn->prepare("
        INSERT INTO sellers (name, email, cpf, telephone, address, gender, birthdate, photo, fk_id_user)
        VALUES (:name, :email, :cpf, :telephone, :address, :gender, :birthdate, :photo, (SELECT id_user FROM users WHERE login = 'admin'))");

        $insertStmtSeller->execute([
            ':name' => 'Administrador',
            ':email' => 'admin@admin.com',
            ':cpf' => '12345678901',
            ':telephone' => '11999999999',
            ':address' => 'Rua admin, 123',
            ':gender' => 'M',
            ':birthdate' => '2000-01-01',
            ':photo' => 'sem-foto.webp'
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

    $stmt = $conn->prepare("SELECT * FROM sellers WHERE fk_id_user = :id_user");
    $stmt->bindParam(':id_user', $user['id_user']);
    $stmt->execute();
    $sellers = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password']) && $user['login'] == $login) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['password'] = $user['password'];
        $_SESSION['type_user'] = $user['type_user'];

        $_SESSION['name'] = $sellers['name'];
        $_SESSION['email'] = $sellers['email'];
        $_SESSION['cpf'] = $sellers['cpf'];
        $_SESSION['telephone'] = $sellers['telephone'];
        $_SESSION['address'] = $sellers['address'];
        $_SESSION['gender'] = $sellers['gender'];
        $_SESSION['birthdate'] = $sellers['birthdate'];
        $_SESSION['photo'] = !empty($sellers['photo']) ? $_SESSION['photo'] = ltrim(str_replace('../', '', $sellers['photo']), '/') : 'uploads/sem-foto.webp';



        $_SESSION['logado'] = true;

        echo json_encode([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'login' => $user['login'], // ou use 'id_user' => $user['id_user']
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
