<?php
session_start();

date_default_timezone_set('America/Sao_Paulo');
require_once 'db_connection.php';

// Verificar se o admin está logado
if (!isset($_SESSION['email'])) {
  echo json_encode(array('success' => false, 'error' => 'Admin not logged in.'));
  exit();
}

// Obter o último ID de chat exibido pelo admin
$lastChatId = isset($_POST['lastChatId']) ? $_POST['lastChatId'] : 0;

// Consulta SQL para recuperar os novos chats
$query = "SELECT c.id, r.nome_estabelecimento, c.lastupdate
          FROM chats c
          LEFT JOIN restaurantes r ON c.id_de = r.id
          WHERE c.id > $lastChatId";

$result = mysqli_query($conn, $query);

$conversations = array();
while ($row = mysqli_fetch_assoc($result)) {
  $conversation = array(
    'id' => $row['id'],
    'nome_estabelecimento' => $row['nome_estabelecimento'],
    'lastupdate' => $row['lastupdate']
  );
  $conversations[] = $conversation;
}

// Retornar as conversas em formato JSON
echo json_encode($conversations);
exit();

?>
