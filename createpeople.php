<?php
 
require('database.php');
 
$array = [];  // Inicializar a variável $array
 
$metodo = strtoupper($_SERVER['REQUEST_METHOD']);
 
if ($metodo === 'POST') {
 
 
    $nome = filter_input(INPUT_POST,'nome');
    $endereco = filter_input(INPUT_POST,'endereco');
 
    if ($nome && $endereco) {
 
        $sql = $pdo->prepare("INSERT INTO pessoas (nome, endereco) VALUES (:nome, :endereco)");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':endereco', $endereco);
        $sql->execute();
 
        $id = $pdo->lastInsertId();
 
        $array = [
            "result" => [
                "id" => $id,
                "nome" => $nome,
                "endereco" => $endereco
            ]
        ];
 
    } else {
        $array = ['error' => 'Erro: Dados insuficientes'];
        http_response_code(400);
    }
 
} else {
    $array = ['error' => "Erro: Ação inválida - método permitido apenas POST"];
    http_response_code(405);
}
 
// Incluir o arquivo de retorno (se necessário)
require('return.php');
?>
 