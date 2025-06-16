<?php
header('Content-Type: application/json');
session_start();
require_once "../php/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $product_id = $_POST['id'] ?? '';
        $product_code = $_POST['product_code'] ?? '';
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $type_packaging = $_POST['type_packaging'] ?? '';
        $description = $_POST['description'] ?? '';
        $photo = $_FILES['photo'] ?? null;

        // Verifica se todos os campos obrigatórios estão preenchidos
        if (empty($product_id) || empty($product_code) || empty($name) || empty($price) || empty($amount) || empty($type_packaging) || empty($description)) {
            echo json_encode(['error' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        // Verifica se o produto existe
        $check = $conn->prepare("SELECT COUNT(*) FROM product WHERE product_id = :product_id");
        $check->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $check->execute();
        if ($check->fetchColumn() == 0) {
            echo json_encode(['error' => 'Produto não encontrado.']);
            exit;
        }

        // Upload da imagem
        $caminho_salvar = null;
        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $pasta = "../uploads/";
            $extensao = strtolower(pathinfo($photo["name"], PATHINFO_EXTENSION));
            $novo_nome = uniqid() . "." . $extensao;
            $caminho_salvar = $pasta . $novo_nome;

            if (!move_uploaded_file($photo["tmp_name"], $caminho_salvar)) {
                echo json_encode(['error' => 'Erro ao salvar a foto no servidor.']);
                exit;
            }
        }

        // Atualiza o produto
        if ($caminho_salvar) {
            // Se uma nova foto foi enviada
            $sql = "UPDATE product 
                    SET product_code = :product_code,
                        name = :name, 
                        price = :price, 
                        amount = :amount, 
                        type_packaging = :type_packaging, 
                        description = :description, 
                        photo = :photo 
                    WHERE product_id = :product_id";
        } else {
            // Se nenhuma nova foto foi enviada, não atualiza o campo photo
            $sql = "UPDATE product 
                    SET product_code = :product_code,
                        name = :name, 
                        price = :price, 
                        amount = :amount, 
                        type_packaging = :type_packaging, 
                        description = :description
                    WHERE product_id = :product_id";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_code', $product_code);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':type_packaging', $type_packaging);
        $stmt->bindParam(':description', $description);
        if ($caminho_salvar) {
            $stmt->bindParam(':photo', $caminho_salvar);
        }
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Produto atualizado com sucesso!'
        ]);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erro ao atualizar o produto: ' . $e->getMessage()]);
        exit;
    }
}
?>