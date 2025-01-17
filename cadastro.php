<?php
// Define o cabeçalho para permitir o acesso a partir de qualquer origem (*)
header('Access-Control-Allow-Origin: *');

// Conecta-se ao banco de dados usando PDO
$connect = new PDO("mysql:host=localhost;dbname=id20457963_db_aula", "id20457963_imenes", "^9XP\gT~/4{8|Pi@");

// Decodifica os dados JSON recebidos da requisição HTTP
$received_data = json_decode(file_get_contents("php://input"));

// Cria um array vazio para armazenar os dados a serem enviados como resposta
$data = array();

// Verifica qual é a ação solicitada
if ($received_data->action == 'fetchall') {
    // Se a ação for "fetchall", faz uma consulta para recuperar todos os registros da tabela "fatec_alunos", ordenados pelo ID em ordem decrescente
    $query = "SELECT * FROM fatec_alunos ORDER BY id DESC";
    $statement = $connect->prepare($query);
    $statement->execute();

    // Enquanto houver registros retornados pela consulta, adiciona-os ao array $data
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    // Codifica o array $data como JSON e o envia como resposta
    echo json_encode($data);
}

if ($received_data->action == 'insert') {
    // Se a ação for "insert", cria um novo registro na tabela "fatec_alunos" com os dados recebidos
    $data = array(
        ':first_name' => $received_data->firstName,
        ':last_name' => $received_data->lastName
    );

    $query = "INSERT INTO fatec_alunos (first_name, last_name) VALUES (:first_name, :last_name)";

    $statement = $connect->prepare($query);

    // Executa a query, passando os dados como parâmetros
    $statement->execute($data);

    // Cria um array com a mensagem a ser enviada como resposta
    $output = array(
        'message' => 'Aluno Adicionado'
    );

    // Codifica o array $output como JSON e o envia como resposta
    echo json_encode($output);
}

if ($received_data->action == 'fetchSingle') {
    // Se a ação for "fetchSingle", busca um único registro da tabela "fatec_alunos" com o ID recebido
    $query = "SELECT * FROM fatec_alunos WHERE id = '" . $received_data->id . "'";
    $statement = $connect->prepare($query);
    $statement->execute();

    // Recupera o resultado da consulta e adiciona os dados ao array $data
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $data['id'] = $row['id'];
        $data['first_name'] = $row['first_name'];
        $data['last_name'] = $row['last_name'];
    }

    // Codifica o array $data como JSON e o envia como resposta
    echo json_encode($data);
}
// Verifica se a ação recebida na requisição é para atualizar um aluno
if ($received_data->action == 'update') {

    // Cria um array com os dados a serem atualizados no aluno
    $data = array(
        ':first_name' => $received_data->firstName, // Define o primeiro nome do aluno
        ':last_name' => $received_data->lastName, // Define o sobrenome do aluno
        ':id' => $received_data->hiddenId // Define o ID do aluno a ser atualizado
    );

    // Define a consulta SQL para atualizar os dados do aluno
    $query = "
        UPDATE fatec_alunos 
        SET first_name = :first_name, 
            last_name = :last_name 
        WHERE id = :id
    ";

    // Prepara a consulta para ser executada, substituindo os placeholders pelos valores do array $data
    $statement = $connect->prepare($query);

    // Executa a consulta, passando os valores do array $data como parâmetros
    $statement->execute($data);

    // Cria um array de resposta com a mensagem de sucesso
    $output = array(
        'message' => 'Aluno Atualizado'
    );

    // Converte o array de resposta para formato JSON e o envia para o cliente
    echo json_encode($output);
}

// Verifica se a ação recebida na requisição é para deletar um aluno
if ($received_data->action == 'delete') {

    // Define a consulta SQL para deletar o aluno com o ID recebido
    $query = "
        DELETE FROM fatec_alunos 
        WHERE id = '" . $received_data->id . "'
    ";

    // Prepara a consulta para ser executada
    $statement = $connect->prepare($query);

    // Executa a consulta
    $statement->execute();

    // Cria um array de resposta com a mensagem de sucesso
    $output = array(
        'message' => 'Aluno Deletado'
    );

    // Converte o array de resposta para formato JSON e o envia para o cliente
    echo json_encode($output);
}
?>