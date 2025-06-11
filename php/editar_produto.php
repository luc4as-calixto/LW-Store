<?php
header('Content-Type: application/json');
session_start();
require_once "../php/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $product_code = $_POST['product_code'] ?? '';
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $type_packaging = $_POST['type_packaging'] ?? '';
        $description = $_POST['description'] ?? '';
        $photo = $_FILES['photo'] ?? null;

        if (empty($product_code) || empty($name) || empty($price) || empty($amount) || empty($type_packaging) || empty($description)) {
            echo json_encode(['error' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        // Verifica se produto existe
        $check = $conn->prepare("SELECT COUNT(*) FROM product WHERE product_code = :product_code");
        $check->bindParam(':product_code', $product_code);
        $check->execute();
        if ($check->fetchColumn() == 0) {
            echo json_encode(['error' => 'Produto não encontrado.']);
            exit;
        }

        // Upload da imagem
        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $pasta = "../uploads/";
            $extensao = strtolower(pathinfo($photo["name"], PATHINFO_EXTENSION));
            $novo_nome = uniqid() . "." . $extensao;
            $caminho_salvar = $pasta . $novo_nome;

            if (!move_uploaded_file($photo["tmp_name"], $caminho_salvar)) {
                echo json_encode(['error' => 'Erro ao salvar a foto no servidor.']);
                exit;
            }
        } else {
            $caminho_salvar = "../uploads/produto-sem-imagem.webp";
        }

        // Atualiza o produto
        $sql = "UPDATE product 
                SET name = :name, price = :price, amount = :amount, 
                    type_packaging = :type_packaging, description = :description, 
                    photo = :photo 
                WHERE product_code = :product_code";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':type_packaging', $type_packaging);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':photo', $caminho_salvar);
        $stmt->bindParam(':product_code', $product_code);
        $stmt->execute();


    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erro ao atualizar o produto: ' . $e->getMessage()]);
        exit;
    }
}
?>
