<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$lwstore = 'lwstore';
$sqlFile = __DIR__ . '/../database/lwstore.sql';+

$databaseCreated = false;

try {
    $conn = new PDO("mysql:host=$host;charset=utf8mb4", $usuario, $senha);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cria banco se não existir
    $conn->exec("CREATE DATABASE IF NOT EXISTS `$lwstore` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->exec("USE `$lwstore`");

    // Se não houver tabelas, executa script
    $result = $conn->query("SHOW TABLES");
    if ($result->rowCount() === 0) {
        if (!file_exists($sqlFile)) {
            throw new Exception("Arquivo SQL não encontrado: $sqlFile");
        }

        $sql = file_get_contents($sqlFile);
        $statements = explode(';', $sql);
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if ($stmt !== '') {
                $conn->exec($stmt);
            }
        }
        $databaseCreated = true;
    }

} catch (Exception $e) {
    // repassa o erro para quem chamou (não interrompe aqui)
    throw $e;
}
