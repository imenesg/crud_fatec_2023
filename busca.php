<?php

header('Access-Control-Allow-Origin: *'); // Define a permissão de acesso para todas as origens

$connect = new PDO("mysql:host=localhost;dbname=id20457963_db_aula", "id20457963_imenes", "^9XP\gT~/4{8|Pi@"); // Cria a conexão com o banco de dados MySQL

$received_data = json_decode(file_get_contents("php://input")); // Decodifica os dados recebidos em formato JSON

$data = array(); // Cria um array vazio para armazenar os dados recuperados do banco de dados

if($received_data->query != '') // Verifica se a consulta possui dados
{
	$query = "
	SELECT * FROM fatec_alunos
	WHERE first_name LIKE '%".$received_data->query."%'
	OR last_name LIKE '%".$received_data->query."%'
	ORDER BY id DESC
	";
	// Filtra os resultados pelo primeiro nome // Seleciona todos os dados da tabela fatec_alunos // Ordena os resultados pelo ID em ordem decrescente
}
else
{
	$query = "SELECT * FROM fatec_alunos ORDER BY id DESC
	";
}

$statement = $connect->prepare($query); // Prepara a consulta SQL

$statement->execute(); // Executa a consulta SQL

while($row = $statement->fetch(PDO::FETCH_ASSOC)) // Itera sobre os resultados da consulta SQL
{
	$data[] = $row; // Adiciona cada linha de resultado ao array de dados
}

echo json_encode($data); // Codifica o array de dados em formato JSON e envia a resposta para o cliente

?>
