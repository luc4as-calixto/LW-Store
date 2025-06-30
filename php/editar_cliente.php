<?php
header('Content-Type: application/json');
session_start();
require_once "../php/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        $id_customer = $_POST['id_customer'] ?? '';
        $name = $_POST['name'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $address = $_POST['address'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $photo = $_FILES['photo'] ?? null;

        // Verifica se todos os campos obrigatórios estão preenchidos
        if (empty($id_customer) || empty($name) || empty($cpf) ||
            empty($email) || empty($telephone) || empty($address) ||
            empty($gender) || empty($birthdate)) {
            echo json_encode(['error' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        // Verifica se mudou algo
        $stmt = $conn->prepare("SELECT * FROM customers WHERE id_customer = :id_customer");
        $stmt->bindParam(':id_customer', $id_customer, PDO::PARAM_INT);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cliente['name'] == $name && $cliente['cpf'] == $cpf &&
            $cliente['email'] == $email && $cliente['telephone'] == $telephone &&
            $cliente['address'] == $address && $cliente['gender'] == $gender &&
            $cliente['birthdate'] == $birthdate) {
            echo json_encode(['error' => 'Nenhum dado foi alterado.']);
            exit;
        }

        // Verifica se o cliente existe
        $check = $conn->prepare("SELECT COUNT(*) FROM customers WHERE id_customer = :id_customer");
        $check->bindParam(':id_customer', $id_customer, PDO::PARAM_INT);   
        $check->execute();
        if ($check->fetchColumn() == 0) {
            echo json_encode(['error' => 'Cliente não encontrado.']);
            exit;
        }

        // Upload da imagem
        $caminho_salvar = null;
        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($photo["name"], PATHINFO_EXTENSION));
            $novo_nome = uniqid() . "." . $extensao;
            $caminho_salvar = $novo_nome;

            if (!move_uploaded_file($photo["tmp_name"], $caminho_salvar)) {
                echo json_encode(['error' => 'Erro ao salvar a foto no servidor.']);
                exit;
            }
        }

        // Atualiza o cliente
        if ($caminho_salvar) {
            // Se uma nova foto foi enviada
            $sql = "UPDATE customers 
                    SET name = :name, 
                        cpf = :cpf, 
                        email = :email, 
                        telephone = :telephone, 
                        address = :address,
                        gender = :gender,
                        birthdate = :birthdate,
                        photo = :photo
                    WHERE id_customer = :id_customer";  
        } else {
            // Se não foi enviada uma nova foto
            $sql = "UPDATE customers 
                    SET name = :name, 
                        cpf = :cpf, 
                        email = :email, 
                        telephone = :telephone, 
                        address = :address,
                        gender = :gender,
                        birthdate = :birthdate
                    WHERE id_customer = :id_customer";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_customer', $id_customer, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':birthdate', $birthdate);
        if ($caminho_salvar) {
            $stmt->bindParam(':photo', $caminho_salvar);
        }
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Cliente atualizado com sucesso.'
        ]);

    } catch (Exception $e) {
        echo json_encode(['error' => 'Erro ao processar a solicitação: ' . $e->getMessage()]);
        exit;
    }
};