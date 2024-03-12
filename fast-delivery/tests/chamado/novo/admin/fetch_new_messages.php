<?php
session_start();

date_default_timezone_set('America/Sao_Paulo');
require_once 'db_connection.php';

// Verificar se o admin está logado
if (!isset($_SESSION['email'])) {
  echo json_encode(array('success' => false, 'error' => 'Admin not logged in.'));
  exit();
}

// Obter o ID do admin logado
$email = $_SESSION['email'];
$query = "SELECT id FROM admins WHERE email='$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
  $row = mysqli_fetch_assoc($result);
  $adminId = $row['id'];

  // Verificar se foi fornecido um ID de chat válido
  if (isset($_POST['chatId'])) {
    $chatId = $_POST['chatId'];
    $lastMessageId = $_POST['lastMessageId'];

    // Consulta SQL para recuperar as novas mensagens do chat específico
    $query = "SELECT m.id, m.mensagem, m.data, r.nome_estabelecimento, a.nome_admin
              FROM mensagens m
              INNER JOIN restaurantes r ON m.id_restaurante = r.id
              LEFT JOIN admins a ON m.id_de_admin = a.id
              WHERE m.id_chat = $chatId AND m.id > $lastMessageId
              ORDER BY m.data ASC";

    $result = mysqli_query($conn, $query);

    $messages = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $message = array(
        'id' => $row['id'],
        'mensagem' => $row['mensagem'],
        'data' => $row['data'],
        'nome_estabelecimento' => $row['nome_estabelecimento'],
        'nome_admin' => $row['nome_admin']
      );
      $messages[] = $message;
    }

    // Retornar as mensagens em formato JSON
    echo json_encode($messages);
    exit();
  } else {
    // Retornar erro se o ID do chat não for fornecido
    echo json_encode(array('success' => false, 'error' => 'Chat ID is missing.'));
    exit();
  }
} else {
  // Retornar erro se o admin não estiver logado
  echo json_encode(array('success' => false, 'error' => 'Admin not logged in.'));
  exit();
}
?>
