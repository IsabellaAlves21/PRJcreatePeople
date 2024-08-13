<?php
require ('database.php'); // Inclua aqui a sua conexão com o banco de dados
 
$metodo = strtoupper($_SERVER['REQUEST_METHOD']);
 
if ($metodo === 'PUT') {
    parse_str(file_get_contents("php://input"), $put);
 
$id = filter_var($put['id'] ?? null, FILTER_VALIDATE_INT);
$id_pessoa = $put['id_pessoa'] ?? null;
$ddi = $put['ddi'] ?? null;
$ddd = $put['ddd'] ?? null;
$telefone = $put['telefone'] ?? null;
 
if ($id && $ddi && $ddd && $telefone) {
    $sql = $pdo->prepare("SELECT * FROM telefones WHERE id_pessoa = :id");
    $sql->bindValue(":id", $id);
    $sql->execute();
 
    if ($sql->rowCount() > 0){
        $sql = $pdo->prepare("UPDATE telefones SET ddi = :ddi, ddd = :ddd, telefone = :telefone WHERE id_pessoa = :id");
            $sql->bindValue(':id', $id);
            $sql->bindValue(':ddi', $ddi);
            $sql->bindValue(':ddd', $ddd);
            $sql->bindValue(':telefone', $telefone);
            $sql->execute();
            $array['result'] = [
                "id" => $id,
                "ddi" => $ddi,
                "ddd" => $ddd,
                "telefone" => $telefone
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
require('return.php');
?>