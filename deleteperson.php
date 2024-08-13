
<?php
header('Content-Type: application/json');
require('database.php'); // Inclua aqui a sua conexão com o banco de dados
 
$metodo = strtoupper($_SERVER['REQUEST_METHOD']);
 
if ($metodo === 'DELETE') {
 
    // Ler e processar os dados da requisição
    parse_str(file_get_contents("php://input"), $delete);
 
    $idPessoa = $delete['idPessoa'] ?? null;
 
    // Para proteger o id de inserções não numéricas
    $idPessoa = filter_var($idPessoa, FILTER_VALIDATE_INT);
 
    // Verificar se o ID é válido e se existe na tabela de pessoas
    if ($idPessoa) {
        // Verificar se a pessoa existe
        $sql = $conn->prepare("SELECT * FROM pessoas WHERE id = ?");
        $sql->bind_param('i', $idPessoa);
        $sql->execute();
        $result = $sql->get_result();
 
        if ($result->num_rows > 0) {
 
            // Iniciar transação para garantir a integridade dos dados
            $conn->begin_transaction();
 
            try {
                // Primeiro, excluímos os telefones relacionados
                $query = "DELETE FROM telefones WHERE id_pessoa = ?";
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception('Erro ao preparar a consulta de exclusão de telefones');
                }
                $stmt->bind_param('i', $idPessoa);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao excluir telefones');
                }
                $stmt->close();
 
                // Depois, excluímos a pessoa
                $query = "DELETE FROM pessoas WHERE id = ?";
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception('Erro ao preparar a consulta de exclusão da pessoa');
                }
                $stmt->bind_param('i', $idPessoa);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao excluir a pessoa');
                }
                $stmt->close();
 
                // Confirmar a transação
                $conn->commit();
                $array['result'] = 'Pessoa e telefones excluídos com sucesso!';
            } catch (Exception $e) {
                // Reverter a transação em caso de erro
                $conn->rollback();
                $array['error'] = $e->getMessage();
            }
 
        } else {
            $array['error'] = "Erro: ID inexistente!";
        }
    } else {
        $array['error'] = "Erro: ID inválido";
    }
 
} else {
    $array['error'] = "Erro: Ação inválida - método permitido apenas DELETE";
}
 
// Fechar a conexão com o banco de dados
$conn->close();
 
// Incluir o arquivo de retorno
require('return.php');
 
?>
 