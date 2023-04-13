<?php

ob_start();

session_start(); // Inicia a sessão

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_cad'];
    $email = $_POST['email_cad'];
    $senha = $_POST['senha_cad'];

    // Verifica se o email já está em uso
    $query = "SELECT * FROM fatec_admin WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Este email já está em uso!');</script>";
    } else {
        // Insere o novo usuário no banco de dados
        $query = "INSERT INTO fatec_admin (nome, email, senha) VALUES ('$nome', '$email', md5('$senha'))";
        if (mysqli_query($conn, $query)) {
            echo '<script>alert("Usuário cadastrado com sucesso!")</script>';
            header("Location: index.html#paralogin");
        } else {
            echo '<script>alert("Erro ao cadastrar usuário!")</script>';
            header("Location: index.html#paracadastro");
        }
    }
}

ob_end_flush();

/*
CREATE TABLE fatec_admin (
id INT(11) NOT NULL AUTO_INCREMENT,
nome VARCHAR(100) NOT NULL,
email VARCHAR(100) NOT NULL,
senha VARCHAR(100) NOT NULL,
PRIMARY KEY (id),
UNIQUE KEY email (email)
);
*/

?>