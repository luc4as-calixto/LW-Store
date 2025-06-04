<?php
header('Content-Type: application/json');
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Recebendo dados do formulário
        $product_code = $_POST['product_code'] ?? '';
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $type_packaging = $_POST['type_packaging'] ?? '';
        $description = $_POST['description'] ?? '';
        $photo = $_FILES['photo'] ?? null;

        $stmt = "SELECT product_code FROM product WHERE product_code = :product_code";
        $checkStmt = $conn->prepare($stmt);
        $checkStmt->bindParam(':product_code', $product_code);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            echo json_encode(['error' => 'Já existe um produto cadastrado com este código.']);
            exit;
        }        

        // Validação dos campos obrigatórios
        if (
            empty($product_code) || empty($name) || empty($price) ||
            empty($amount) || empty($type_packaging) || empty($description)
        ) {
            echo json_encode(['error' => 'Todos os campos obrigatórios devem ser preenchidos.']);
            exit;
        }

        // Caminho da foto que será salva no banco

        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $pasta = "../uploads/";
            $nome_original = basename($photo["name"]);
            $extensao = pathinfo($nome_original, PATHINFO_EXTENSION);
            $novo_nome = uniqid() . "." . $extensao;    
            $caminho_salvar = $pasta . $novo_nome;

            if (!move_uploaded_file($photo["tmp_name"], $caminho_salvar)) {
                echo json_encode(['error' => 'Erro ao salvar a foto no servidor.']);
                exit;
            }
        } else {
            $caminho_salvar = "../uploads/produto-sem-imagem.webp";
        }

        // Inserindo no banco de dados
        $sql = "INSERT INTO product (product_code, name, price, amount, type_packaging, description, photo) 
                VALUES (:product_code, :name, :price, :amount, :type_packaging, :description, :photo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_code', $product_code);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':type_packaging', $type_packaging);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':photo', $caminho_salvar); // salva o caminho da imagem ou NULL se não tiver

        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Produto cadastrado com sucesso!'
        ]);

    } catch (Exception $e) {
        echo json_encode(['error' => 'Erro ao cadastrar produto: ' . $e->getMessage()]);
        exit;
    }
}
