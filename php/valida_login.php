<?php
session_start();
require_once 'conexao.php';

$login = $_POST['login'];
$password = $_POST['password'];

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE login = :login AND password = :password");
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':password', $password);
    $stmt->execute();


}