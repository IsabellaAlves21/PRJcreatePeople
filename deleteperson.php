<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permite todos os domínios, substitua '*' pelo domínio específico se necessário
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT'); // Métodos HTTP permitidos
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Cabeçalhos permitidos
 
require('database.php'); // Inclua aqui a sua conexão com o banco de dados
 
$metodo = strtoupper($_SERVER['REQUEST_METHOD']);
 
if ($metodo === 'DELETE') {
    // Ler e processar os dados da requisição
    parse_str(file_get_contents("php://input"), $delete);
 
    $idPessoa = $delete['id_pessoa'] ?? null;
    $idPessoa = filter_var($idPessoa, FILTER_VALIDATE_INT);
 
    // ID fixo para teste, remova isso e use a linha acima para ID dinâmico
    //$idPessoa = 4;
 
    // Verificar se o ID é válido e se existe na tabela de pessoas
    if ($idPessoa) {
        try {
            // Preparar a consulta para verificar se a pessoa existe
            $sql = $pdo->prepare("SELECT * FROM pessoas WHERE id = :id");
            $sql->bindParam(':id', $idPessoa, PDO::PARAM_INT);
            $sql->execute();
           
            if ($sql->rowCount() > 0) {
                // Iniciar transação para garantir a integridade dos dados
                $pdo->beginTransaction();
 
                try {
                    // Excluir telefones relacionados
                    $query = "DELETE FROM telefones WHERE id_pessoa = :id";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id', $idPessoa, PDO::PARAM_INT);
                    $stmt->execute();
 
                    // Excluir a pessoa
                    $query = "DELETE FROM pessoas WHERE id = :id";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id', $idPessoa, PDO::PARAM_INT);
                    $stmt->execute();
 
                    // Confirmar a transação
                    $pdo->commit();
                    $array['result'] = 'Pessoa e telefones excluídos com sucesso!';
                } catch (Exception $e) {
                    // Reverter a transação em caso de erro
                    $pdo->rollback();
                    $array['error'] = $e->getMessage();
                }
            } else {
                $array['error'] = "Erro: ID inexistente!";
            }
        } catch (Exception $e) {
            $array['error'] = "Erro ao executar a consulta: " . $e->getMessage();
        }
    } else {
        $array['error'] = "Erro: ID inválido";
    }
} else {
    $array['error'] = "Erro: Ação inválida - método permitido apenas DELETE";
}
 
// Retornar a resposta como JSON
echo json_encode($array, JSON_UNESCAPED_UNICODE);
?>
 