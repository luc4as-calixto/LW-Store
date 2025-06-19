<?php

header('Content-Type: application/json');
session_start();
require_once "../php/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $id_seller = $_POST['id_seller'] ?? '';
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $address = $_POST['address'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $photo = $_FILES['photo'] ?? null;

        // Verifica se mudou algo
        $stmt = $conn->prepare("SELECT * FROM sellers WHERE id_seller = :id_seller");
        $stmt->bindParam(':id_seller', $id_seller, PDO::PARAM_INT);
        $stmt->execute();
        $vendedor = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($vendedor['name'] == $name && $vendedor['email'] == $email && $vendedor['cpf'] == $cpf && $vendedor['telephone'] == $telephone && $vendedor['address'] == $address && $vendedor['gender'] == $gender && $vendedor['birthdate'] == $birthdate) {
            echo json_encode(['error' => 'Nenhum dado foi alterado.']);
            exit;
        }

        // Verifica se todos os campos obrigatórios estão preenchidos
        if (empty($name) || empty($email) || empty($cpf) || empty($telephone) || empty($address) || empty($gender) || empty($birthdate)) {
            echo json_encode(['error' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        // Verifica se o vendedor existe
        $check = $conn->prepare("SELECT COUNT(*) FROM sellers WHERE id_seller = :id_seller");
        $check->bindParam(':id_seller', $id_seller, PDO::PARAM_INT);
        $check->execute();
        if ($check->fetchColumn() == 0) {
            echo json_encode(['error' => 'Vendedor não encontrado.']);
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

        // Atualiza o vendedor
        if ($caminho_salvar) {
            // Se uma nova foto foi enviada
            $sql = "UPDATE sellers 
                    SET name = :name, 
                        email = :email, 
                        cpf = :cpf, 
                        telephone = :telephone, 
                        address = :address, 
                        gender = :gender,
                        birthdate = :birthdate,
                        photo = :photo
                    WHERE id_seller = :id_seller";
        } else {
            // Se nenhuma nova foto foi enviada, não atualiza o campo photo
            $sql = "UPDATE sellers 
                    SET name = :name, 
                        email = :email, 
                        cpf = :cpf, 
                        telephone = :telephone, 
                        address = :address, 
                        gender = :gender,       
                        birthdate = :birthdate
                    WHERE id_seller = :id_seller";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_seller', $id_seller, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
        if ($caminho_salvar) {
            $stmt->bindParam(':photo', $caminho_salvar, PDO::PARAM_STR);
        }
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Vendedor atualizado com sucesso!'
        ]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erro ao atualizar vendedor: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Erro inesperado: ' . $e->getMessage()]);
    }
}
