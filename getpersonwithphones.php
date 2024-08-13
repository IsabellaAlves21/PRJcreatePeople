<?php
header('Content-Type: application/json');
require('database.php'); // Inclua aqui a sua conexão com o banco de dados usando PDO
 
$metodo = strtoupper($_SERVER['REQUEST_METHOD']);
 
if ($metodo === 'GET') {
    // Obter o ID da pessoa a partir dos parâmetros da URL
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
 
    if ($id !== false && $id !== null) {
        // Preparar a consulta para buscar pessoa com telefones associados
        $query = "
            SELECT p.id, p.nome, p.endereco, t.ddi, t.ddd, t.telefone
            FROM pessoas p
            INNER JOIN telefones t ON p.id = t.id_pessoa
            WHERE p.id = :id
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
 
        $person = null;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!$person) {
                $person = [
                    'id' => $row['id'],
                    'nome' => $row['nome'],
                    'endereco' => $row['endereco'],
                    'telefones' => []
                ];
            }
            $person['telefones'][] = [
                'ddi' => $row['ddi'],
                'ddd' => $row['ddd'],
                'telefone' => $row['telefone']
            ];
        }
 
        // Verificar se a pessoa foi encontrada e retornar a resposta apropriada
        if ($person) {
            echo json_encode($person);
        } else {
            echo json_encode(['error' => 'Pessoa não encontrada ou sem telefones']);
            http_response_code(404);
        }
 
    } else {
        echo json_encode(['error' => 'ID da pessoa não fornecido ou inválido']);
        http_response_code(400);
    }
 
} else {
    echo json_encode(['error' => 'Método inválido - apenas GET é permitido']);
    http_response_code(405);
}
 
// Fechar a conexão com o banco de dados
$pdo = null;
 
require('return.php');
?>