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
        $zipCode = $_POST['cep'] ?? '';
        $address = $_POST['address'] ?? '';
        $nrAddress = $_POST['numberAddress'] ?? '';
        $neighborhood = $_POST['bairro'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $photo = $_FILES['photo'] ?? null;

        // Validação dos campos obrigatórios
        if (empty($name) || empty($gender) || empty($telephone) || empty($cpf) || empty($birthdate) || empty($email) || empty($address) || empty($nrAddress) || empty($neighborhood) || empty($city) || empty($state) || empty($zipCode)) {
            echo json_encode(['error' => 'Todos os campos obrigatórios devem ser preenchidos.']);
            exit;
        }

        // Verifica se já existe CPF cadastrado
        $verifica = $conn->prepare("SELECT COUNT(*) FROM customers WHERE cpf = :cpf");
        $verifica->bindParam(':cpf', $cpf);
        $verifica->execute();
        if ($verifica->fetchColumn() > 0) {
            echo json_encode(['error' => 'CPF já cadastrado.']);
            exit;
        }

        // Processamento da foto
        $caminho_salvar = "sem-foto.webp"; // padrão
        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $ext_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($extensao, $ext_permitidas)) {
                echo json_encode(['error' => 'Formato de imagem não permitido.']);
                exit;
            }

            $novo_nome = uniqid() . "." . $extensao;
            $caminho_salvar = `uploads/$novo_nome`;

            if (!move_uploaded_file($photo["tmp_name"], $caminho_salvar)) {
                echo json_encode(['error' => 'Erro ao salvar a foto no servidor.']);
                exit;
            }
        }

        // Formatação do endereço
        $newAddress = trim($address) . ', ' . trim($nrAddress) . ', ' . trim($neighborhood) . ', ' . trim($city) . ', ' . trim($state) . ' - ' . trim($zipCode);

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
        $stmt->bindParam(':address', $newAddress);
        $stmt->bindParam(':photo', $caminho_salvar);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Cliente cadastrado com sucesso.'
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Erro ao processar os dados: ' . $e->getMessage()]);
        exit;
    }
}
