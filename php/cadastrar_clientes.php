<?php
header('Content-Type: application/json');
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $cpf = $_POST['CPF'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $address = $_POST['address'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $photo = $_FILES['photo'] ?? null;

        // Validação dos campos obrigatórios
        if (empty($name) || empty($gender) || empty($telephone) || empty($cpf) || empty($birthdate) || empty($email) || empty($address)) {
            echo json_encode(['error' => 'Todos os campos obrigatórios devem ser preenchidos.']);
            exit;
        }

        // Processamento da foto
        $caminho_salvar = "../uploads/sem-foto.webp"; // padrão
        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $pasta = "../uploads/";
            $extensao = strtolower(pathinfo($photo["name"], PATHINFO_EXTENSION));

            // Verifica se é uma extensão permitida
            $ext_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($extensao, $ext_permitidas)) {
                echo json_encode(['error' => 'Formato de imagem não permitido.']);
                exit;
            }

            $novo_nome = uniqid() . "." . $extensao;
            $caminho_salvar = $pasta . $novo_nome;

            if (!move_uploaded_file($photo["tmp_name"], $caminho_salvar)) {
                echo json_encode(['error' => 'Erro ao salvar a foto no servidor.']);
                exit;
            }
        }

        // Inserção no banco
        $sql = "INSERT INTO customers
        (name, gender, telephone, cpf, birthdate, email, address, photo)
        VALUES
        (:name, :gender, :telephone, :cpf, :birthdate, :email, :address, :photo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':photo', $caminho_salvar);
        $stmt->execute();

        echo json_encode ([
            'success' => true,
            'message' => 'Cliente cadastrado com sucesso.'
        ]);

    } catch (Exception $e) {
        echo json_encode(['error' => 'Erro ao processar os dados: ' . $e->getMessage()]);
        exit;
    }
}
?>