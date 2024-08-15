<?php
 
require('database.php');
 
// O resto do seu código para lidar com a requisição
$metodo = strtoupper($_SERVER['REQUEST_METHOD']);
 
if ($metodo === 'GET') {
 
    // Obter o ID da requisição GET
    $idPessoa = $_GET['id'] ?? null;
 
    if ($idPessoa) {
        // Validar e sanitizar o ID
        $idPessoa = filter_var($idPessoa, FILTER_VALIDATE_INT);
 
        if ($idPessoa) {
            // Preparar a consulta para selecionar um registro específico
            $sql = $pdo->prepare("SELECT id, nome, endereco FROM pessoas WHERE id = :id");
            $sql->bindParam(':id', $idPessoa, PDO::PARAM_INT);
            $sql->execute();
 
            // Buscar o resultado
            $result = $sql->fetch(PDO::FETCH_ASSOC);
 
            if ($result) {
                // Retornar o resultado como JSON
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            } else {
                // ID não encontrado
                echo json_encode(['error' => 'Registro não encontrado'], JSON_UNESCAPED_UNICODE);
                http_response_code(404);
            }
        } else {
            // ID inválido
            echo json_encode(['error' => 'ID inválido'], JSON_UNESCAPED_UNICODE);
            http_response_code(400);
        }
    } else {
        // Preparar a consulta para listar todos os registros
        $sql = $pdo->prepare("SELECT id, nome, endereco FROM pessoas");
        $sql->execute();
 
        // Buscar todos os resultados
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
 
        if ($result) {
            // Retornar os resultados como JSON
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            // Nenhum registro encontrado
            echo json_encode(['error' => 'Nenhum registro encontrado'], JSON_UNESCAPED_UNICODE);
            http_response_code(404);
        }
    }
 
} else {
    // Método não permitido
    echo json_encode(['error' => 'Método não permitido. Utilize GET.'], JSON_UNESCAPED_UNICODE);
    http_response_code(405);
}
 
// Incluir o arquivo de retorno (se necessário)
require('return.php');
?>