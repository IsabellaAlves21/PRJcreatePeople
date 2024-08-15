<?php
require('./database.php');
 
$metodo = strtoupper($_SERVER['REQUEST_METHOD']);
 
if ($metodo === 'PUT') {
    parse_str(file_get_contents("php://input"), $put);
 
    $id = filter_var($put['id'] ?? null, FILTER_VALIDATE_INT);
    $nome = $put['nome'] ?? null;
    $endereco = $put['endereco'] ?? null;
 
    if ($id && $nome && $endereco) {
        $sql = $pdo->prepare("SELECT * FROM pessoas WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
 
        if ($sql->rowCount() > 0) {
            $sql = $pdo->prepare("UPDATE pessoas SET nome = :nome, endereco = :endereco WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->bindValue(':nome', $nome);
            $sql->bindValue(':endereco', $endereco);
            $sql->execute();
 
            $array['result'] = [
                "id" => $id,
                "nome" => $nome,
                "endereco" => $endereco
            ];
        } else {
            $array['error'] = 'Erro: Id inexistente!';
        }
    } else {
        $array['error'] = 'Erro: Valores nulos ou inválidos!';
    }
} else {
    $array['error'] = 'Erro: Método inválido - Apenas PUT é permitido.';
}
 
require('./return.php');