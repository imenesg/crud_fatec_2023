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
    $query = "SELECT * FROM fatec_Professores ORDER BY salario DESC";
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
    // Se a ação for "insert", cria um novo registro na tabela "fatec_professores" com os dados recebidos
    $data = array(
        ':nome' => $received_data->firstName,
        ':endereco' => $received_data->endereco,
        ':curso' => $received_data->curso,
        ':salario' => $received_data->salario
    );

    $query = "INSERT INTO fatec_Professores (nome, endereco, curso, salario  ) VALUES (:nome, :endereco, :curso, :salario)";

    $statement = $connect->prepare($query);

    // Executa a query, passando os dados como parâmetros
    $statement->execute($data);

    // Cria um array com a mensagem a ser enviada como resposta
    $output = array(
        'message' => 'Professor Adicionado'
    );

    // Codifica o array $output como JSON e o envia como resposta
    echo json_encode($output);
}

if ($received_data->action == 'fetchSingle') {
    // Se a ação for "fetchSingle", busca um único registro da tabela "fatec_alunos" com o ID recebido
    $query = "SELECT * FROM fatec_Professores WHERE id = '" . $received_data->id . "'";
    $statement = $connect->prepare($query);
    $statement->execute();

    // Recupera o resultado da consulta e adiciona os dados ao array $data
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $data['id'] = $row['id'];
        $data['nome'] = $row['nome'];
        $data['endereco'] = $row['endereco'];
        $data['curso'] = $row['curso'];
        $data['salario'] = $row['salario'];
    }

    // Codifica o array $data como JSON e o envia como resposta
    echo json_encode($data);
}
// Verifica se a ação recebida na requisição é para atualizar um aluno
if ($received_data->action == 'update') {

    // Cria um array com os dados a serem atualizados no aluno
    $data = array(
        ':nome' => $received_data->nome, // Define o primeiro nome do professor
        ':endereco' => $received_data->endereco, 
        ':curso' => $received_data->curso, 
        ':salario' => $received_data->salario,
        ':id' => $received_data->hiddenId 
    );

    // Define a consulta SQL para atualizar os dados do aluno
    $query = "UPDATE fatec_Professores SET nome = :nome, endereco = :endereco, curso = :curso, salario = :salario, WHERE id = :id";

    // Prepara a consulta para ser executada, substituindo os placeholders pelos valores do array $data
    $statement = $connect->prepare($query);

    // Executa a consulta, passando os valores do array $data como parâmetros
    $statement->execute($data);

    // Cria um array de resposta com a mensagem de sucesso
    $output = array(
        'message' => 'Professor Atualizado'
    );

    // Converte o array de resposta para formato JSON e o envia para o cliente
    echo json_encode($output);
}

// Verifica se a ação recebida na requisição é para deletar um aluno
if ($received_data->action == 'delete') {

    // Define a consulta SQL para deletar o aluno com o ID recebido
    $query = "DELETE FROM fatec_Professores WHERE id = '". $received_data->id ."'";

    // Prepara a consulta para ser executada
    $statement = $connect->prepare($query);

    // Executa a consulta
    $statement->execute();

    // Cria um array de resposta com a mensagem de sucesso
    $output = array(
        'message' => 'Professor Deletado'
    );

    // Converte o array de resposta para formato JSON e o envia para o cliente
    echo json_encode($output);
}
?>