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
        $photo = $_FILES['photo'] ?? "sem-foto.webp";

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
        $caminho_salvar = $_SESSION['photo']; // valor padrão: mantém imagem atual
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
            $novo_nome = uniqid() . "." . $extensao;

            // Caminhos
            $caminho_salvar = $novo_nome;
            $caminho_fisico = "../uploads/$caminho_salvar";

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $caminho_fisico)) {
                $_SESSION['photo'] = $caminho_salvar; // atualiza a sessão
            } else {
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
