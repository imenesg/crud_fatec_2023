<?php

ob_start(); // Inicia o buffer de saída

session_start(); // Inicia a sessão

require_once 'config.php'; // Requer o arquivo de configuração para estabelecer a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Verifica se o método de requisição é POST

    // Recebe o email e senha enviados pelo formulário de login
    $email = $_POST['email_login'];
    $senha = $_POST['senha_login'];

    // Verifica se o email e senha são válidos no banco de dados
    $query = "SELECT id, nome FROM fatec_admin WHERE email='$email' AND senha=md5('$senha')";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) { // Se os dados estiverem corretos

        $row = mysqli_fetch_assoc($result);

        // Armazena o ID e o nome do usuário na sessão
        $_SESSION['id'] = $row['id'];
        $_SESSION['nome'] = $row['nome'];

        header('Location: dashboard.html'); // Redireciona para a página de dashboard

    } else { // Se os dados estiverem incorretos

        echo '<script>alert("Email ou senha incorretos!")</script>'; // Exibe um alerta para o usuário
        header("Location: index.html#paralogin"); // Redireciona de volta para a página de login

    }
}

ob_end_flush(); // Finaliza o buffer de saída e envia os dados para o navegador

?>

