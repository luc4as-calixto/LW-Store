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

        // Validação dos campos obrigatórios
        if (
            empty($product_code) || empty($name) || empty($price) ||
            empty($amount) || empty($type_packaging) || empty($description)
        ) {
            echo json_encode(['error' => 'Todos os campos obrigatórios devem ser preenchidos.']);
            exit;
        }

        // Validação do arquivo de foto
        $pasta = "../uploads/";
        // Pega o nome original da foto e cria um nome único para evitar conflito
        $nome_original = basename($_FILES["foto"]["name"]);
        $extensao = pathinfo($nome_original, PATHINFO_EXTENSION);
        $novo_nome = uniqid() . "." . $extensao;
        // Caminho completo para salvar no servidor
        $caminho_salvar = $pasta . $novo_nome;

        // Mover o arquivo enviado para a pasta
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $caminho_salvar)) {

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
            $stmt->bindParam(':photo', $caminho_salvar);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Produto cadastrado com sucesso!'
            ]);
        } else {
            echo "Erro ao salvar a foto no servidor.";
        };
    } catch (Exception $e) {
        echo json_encode(['error' => 'Erro ao cadastrar produto: ' . $e->getMessage()]);
        exit;
    }
}
