<?php
 
header('Content-Type: application/json');
require('database.php'); // Inclua a conexão com o banco de dados
 
$metodo = strtoupper($_SERVER['REQUEST_METHOD']);
 
if ($metodo === 'POST') {
 
    // Obter e decodificar JSON do corpo da requisição
    $data = json_decode(file_get_contents('php://input'), true);
 
    // Capturar os dados de nome e endereço
    $nome = filter_input(INPUT_POST,'nome');
    $endereco = filter_input(INPUT_POST,'endereco');
 
    // Verificar se os dados necessários estão presentes
    if ($nome && $endereco) {
 
        // Preparar e executar a consulta SQL
        $sql = $pdo->prepare("INSERT INTO pessoas (nome, endereco) VALUES (:nome, :endereco)");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':endereco', $endereco);
        $sql->execute();
 
        // Obter o ID do novo registro
        $id = $pdo->lastInsertId();
 
        // Retornar resposta JSON com sucesso
        echo json_encode([
            "result" => [
                "id" => $id,
                "nome" => $nome,
                "endereco" => $endereco
            ]
        ]);
 
    } else {
        // Retornar resposta JSON com erro se dados estiverem faltando
        echo json_encode(['error' => 'Erro: Dados insuficientes']);
        http_response_code(400);
    }
 
} else {
    // Retornar resposta JSON com erro se o método não for POST
    echo json_encode(['error' => "Erro: Ação inválida - método permitido apenas POST"]);
    http_response_code(405);
}
 
require('return.php');
?>