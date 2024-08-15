<?php
header('Content-Type: application/json');
require 'database.php'; // Inclua a conexão com o banco de dados
 
// Consulta para buscar todas as pessoas e seus telefones
$query = "
    SELECT p.id, p.nome, p.endereco, t.ddi, t.ddd, t.telefone
    FROM pessoas p
    LEFT JOIN telefones t ON p.id = t.id_pessoa
";
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
    $id = $row['id'];
    // Verificar se a pessoa já foi adicionada ao array
    if (!isset($people[$id])) {
        $people[$id] = [
            'id' => $id,
            'nome' => $row['nome'],
            'endereco' => $row['endereco'],
            'telefones' => []
        ];
    }
    // Adicionar telefone se estiver disponível
    if ($row['ddi']) {
        $people[$id]['telefones'][] = [
            'ddi' => $row['ddi'],
            'ddd' => $row['ddd'],
            'telefone' => $row['telefone']
        ];
    }
}
 
// Retornar os dados em formato JSON
echo json_encode(array_values($people));
 
// Liberar o resultado e fechar a conexão
$result->free();
$conn->close();
 
// Incluir o arquivo de retorno, se necessário
require('return.php');
?>