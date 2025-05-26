<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$lwstore = 'lwstore';

try {
    $conn = new PDO("mysql:host=$host;dbname=$lwstore;charset=utf8", $usuario, $senha);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}