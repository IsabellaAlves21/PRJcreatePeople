<?php
header('Content-Type: application/json');
require ('database.php'); // Inclua aqui a sua conexão com o banco de dados
 
// Consulta para buscar todas as pessoas
$query = "SELECT id, nome, endereco FROM pessoas";
 
// Executar a consulta
$result = $conn->query($query);
 
// Verificar se a consulta foi bem-sucedida
if ($result === false) {
    echo json_encode(['error' => 'Erro na consulta: ' . $conn->error]);
    http_response_code(500);
    exit;
}
 
// Array para armazenar os resultados
$people = [];
 
// Processar o resultado da consulta
while ($row = $result->fetch_assoc()) {
    $people[] = $row;
}
 
// Retornar os dados em formato JSON
echo json_encode($people);
 
// Liberar o resultado e fechar a conexão
$result->free();
$conn->close();
 
// Incluir o arquivo de retorno (se necessário)
require('return.php');
?>