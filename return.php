<?php
// return.php
 
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permite qualquer origem. Use um URL específico para maior segurança
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
   
 
echo json_encode($array, JSON_UNESCAPED_UNICODE);
exit;
?>
 