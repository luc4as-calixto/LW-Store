<?php
session_start();
require_once "conexao.php";

try {
    $idUser = $_SESSION['id_user'] ?? null;
    $typeUser = $_SESSION['type_user'] ?? null;

    if ($typeUser == 'admin') {
        $stmt = $conn->prepare("
        SELECT p.name AS produto, SUM(si.quantity) AS quantidade
        FROM sale_items si
        JOIN product p ON p.product_id = si.product_id
        GROUP BY p.name
        ORDER BY quantidade DESC
        LIMIT 6
        ");
    } else {
        $stmt = $conn->prepare("
            SELECT 
                p.name AS produto, 
                SUM(si.quantity) AS quantidade
            FROM 
                sale_items si
            JOIN 
                product p ON p.product_id = si.product_id
            JOIN 
                sales s ON s.id_sale = si.id_sale
            WHERE 
                s.id_user = :idUser
            GROUP BY 
                p.product_id, p.name
            ORDER BY 
                quantidade DESC
            LIMIT 6;
        ");
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    }
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $produtos = [];
    $quantidades = [];

    foreach ($resultado as $linha) {
        $produtos[] = $linha['produto'];
        $quantidades[] = $linha['quantidade'];
    }

    echo json_encode([
        'produto' => $produtos,
        'quantidade' => $quantidades
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'erro' => 'Erro ao buscar dados de produtos: ' . $e->getMessage()
    ]);
}
