<?php
 
header('Content-Type: application/json');
require('database.php'); // Inclua a conexão com o banco de dados
 
$metodo = strtoupper($_SERVER['REQUEST_METHOD']);
 
if ($metodo === 'POST') {
 
    // Capturar os dados de idPessoa, ddi, ddd e telefone usando filter_input()
    $idPessoa = filter_input(INPUT_POST, 'idPessoa', FILTER_VALIDATE_INT);
    $ddi = filter_input(INPUT_POST, 'ddi', FILTER_SANITIZE_STRING);
    $ddd = filter_input(INPUT_POST, 'ddd', FILTER_SANITIZE_STRING);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
 
    // Verificar se todos os dados necessários estão presentes
    if ($idPessoa !== null && $ddi !== null && $ddd !== null && $telefone !== null) {
 
        // Preparar e executar a consulta SQL
        $query = "INSERT INTO telefones (id_pessoa, ddi, ddd, telefone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
 
        if ($stmt === false) {
            echo json_encode(['error' => 'Erro ao preparar a consulta']);
            http_response_code(500);
            exit;
        }
 
        $stmt->bind_param('iiss', $idPessoa, $ddi, $ddd, $telefone);
 
        if ($stmt->execute()) {
            // Retornar resposta JSON com sucesso
            echo json_encode(['message' => 'Telefone criado com sucesso']);
        } else {
            // Retornar resposta JSON com erro
            echo json_encode(['error' => 'Erro ao criar telefone']);
            http_response_code(500);
        }
 
        $stmt->close();
    } else {
        // Retornar resposta JSON com erro se dados estiverem faltando
        echo json_encode(['error' => 'Dados insuficientes']);
        http_response_code(400);
    }
 
} else {
    // Retornar resposta JSON com erro se o método não for POST
    echo json_encode(['error' => "Método inválido - apenas POST é permitido"]);
    http_response_code(405);
}
 
// Fechar a conexão com o banco de dados
$conn->close();
 
require('return.php');
 
?>