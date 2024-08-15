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
    if ($idPessoa && $ddi && $ddd && $telefone) {
 
        // Preparar e executar a consulta SQL
        $query = "INSERT INTO telefones (id_pessoa, ddi, ddd, telefone) VALUES (:id_pessoa, :ddi, :ddd, :telefone)";
        $stmt = $pdo->prepare($query); // Use $pdo em vez de $sql
 
        if ($stmt === false) {
            echo json_encode(['error' => 'Erro ao preparar a consulta']);
            http_response_code(500);
            exit;
        }
 
        // Bind os parâmetros
        $stmt->bindValue(':id_pessoa', $idPessoa);
        $stmt->bindValue(':ddi', $ddi);
        $stmt->bindValue(':ddd', $ddd);
        $stmt->bindValue(':telefone', $telefone);
 
        if ($stmt->execute()) {
            // Retornar resposta JSON com sucesso
            echo json_encode(['message' => 'Telefone criado com sucesso']);
        } else {
            // Retornar resposta JSON com erro
            echo json_encode(['error' => 'Erro ao criar telefone']);
            http_response_code(500);
        }
 
        $stmt->closeCursor(); // Se estiver usando PDO, use closeCursor()
 
    } else {
        $array = ['error' => 'Erro: Dados insuficientes'];
        http_response_code(400);
    }
 
} else {
    $array = ['error' => "Erro: Ação inválida - método permitido apenas POST"];
    http_response_code(405);
}
// Fechar a conexão com o banco de dados
// Fechar a conexão não é necessário com PDO, mas se estiver usando MySQLi:
// $conn->close();
 
require('return.php');
 
?>