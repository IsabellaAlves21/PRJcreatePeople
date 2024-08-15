<?php
// database.php
 
$servername = "localhost";
$username = "root"; // Altere conforme suas credenciais
$password = "";     // Altere conforme suas credenciais
$dbname = "sistema_pessoas"; // Altere conforme o nome do seu banco de dados
 
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
 
    $conn = new mysqli($servername, $username, $password, $dbname);
 
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
 
$array =[
    'error'=>"",
    'result'=>[]
];
 
?>
 