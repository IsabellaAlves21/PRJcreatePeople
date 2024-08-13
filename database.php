
<?php
// database.php
 
$servername = "localhost";
$username = "root"; // Altere conforme suas credenciais
$password = "";     // Altere conforme suas credenciais
$dbname = "sistema_pessoas"; // Altere conforme o nome do seu banco de dados
 
try {
    // Criar conexão usando PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Definir o modo de erro do PDO para Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Caso haja um erro na conexão, exibe a mensagem e encerra o script
    die("Connection failed: " . $e->getMessage());
}
 
$array =[
    'error'=>"",
    'result'=>[]
];
 
?>
 