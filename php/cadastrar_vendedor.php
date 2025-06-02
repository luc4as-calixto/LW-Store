<?php 
header("Content-Type: application/json; charset=UTF-8");
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $name = $_POST['name'] ?? '';
        $login = $_POST['login_ven'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $create_password = $_POST['create-password'] ?? '';
        $password_confirmation = $_POST['password-confirmation'] ?? '';
        $cpf = $_POST['CPF'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $email = $_POST['email'] ?? '';
        $address = $_POST['address'] ?? '';
        $type_user = "vendedor";
        $photo = $_FILES['photo'] ?? null;

        // Validação dos campos obrigatórios
        if (empty($name) || empty($gender) || empty($telephone) || empty($login) || empty($create_password) || empty($password_confirmation) || empty($cpf) || empty($birthdate) || empty($email) || empty($address)) {
            echo json_encode(['error' => 'Todos os campos obrigatórios devem ser preenchidos.']);
            exit;
        }

        // Verifica se as senhas coincidem
        if ($create_password !== $password_confirmation) {
            echo json_encode(['error' => 'As senhas não coincidem.']);
            exit;
        }

        // Hash da senha
        $hashed_password = password_hash($create_password, PASSWORD_DEFAULT);

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
        $sql = "INSERT INTO users
                (name, login, gender, telephone, password, cpf, birthdate, email, address, type_user, photo) 
                VALUES 
                (:name, :login, :gender, :telephone, :password, :cpf, :birthdate, :email, :address, :type_user, :photo)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':type_user', $type_user);
        $stmt->bindParam(':photo', $caminho_salvar);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Vendedor cadastrado com sucesso! ' 
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'error' => 'Erro ao cadastrar vendedor: ' . $e->getMessage()
        ]);
    }
}
?>
