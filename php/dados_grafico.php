<?php
session_start();
require_once "conexao.php";

try {
    $idUser = $_SESSION['id_user'] ?? null;
    $typeUser = $_SESSION['type_user'] ?? null;

    if ($typeUser == 'admin') {
        $stmt = $conn->prepare("
            SELECT DATE_FORMAT(date_sale, '%Y-%m') AS mes, COUNT(*) AS total_vendas
            FROM sales
            GROUP BY mes
            ORDER BY mes
        ");
    } else {
        $stmt = $conn->prepare("
            SELECT DATE_FORMAT(date_sale, '%Y-%m') AS mes, COUNT(*) AS total_vendas
            FROM sales
            WHERE id_user = :idUser
            GROUP BY mes
            ORDER BY mes
        ");
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    }

    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $meses = [];
    $totais = [];

    foreach ($resultado as $linha) {
        $meses[] = $linha['mes'];
        $totais[] = $linha['total_vendas'];
    }

    echo json_encode([
        'meses' => $meses,
        'totais' => $totais
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'erro' => 'Erro ao buscar dados: ' . $e->getMessage()
    ]);
}
