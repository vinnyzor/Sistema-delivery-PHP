<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require_once 'db_connection.php';

// Verificar se o restaurante está logado
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

// Obter o ID do restaurante logado
$email = $_SESSION['email'];
$query = "SELECT id FROM restaurantes WHERE email='$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
  $row = mysqli_fetch_assoc($result);
  $restauranteId = $row['id'];

  // Consulta SQL para recuperar as conversas do restaurante logado
  $query = "SELECT * FROM chats WHERE id_de = $restauranteId OR id_para = $restauranteId";
  $result = mysqli_query($conn, $query);

  $conversations = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $conversation = array(
      'id' => $row['id'],
      'id_de' => $row['id_de'],
      'id_para' => $row['id_para'],
      'lastupdate' => $row['lastupdate']
    );
    $conversations[] = $conversation;
  }

  // Retornar as conversas em formato JSON
  echo json_encode(array('conversations' => $conversations));
} else {
  header("Location: login.php");
  exit();
}

// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>
