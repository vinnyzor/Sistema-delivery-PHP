<?php
session_start();
require_once 'db_connection.php';

// Verificar se o restaurante está logado
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

// Obter o ID do restaurante logado
$email = $_SESSION['email'];
$query = "SELECT id FROM restaurantes WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
  $row = mysqli_fetch_assoc($result);
  $restauranteId = $row['id'];

  // Obter o ID do chat da solicitação
  $chatId = $_POST['chatId'];

  // Consulta SQL para obter as mensagens do chat específico relacionadas ao restaurante logado
  $query = "SELECT * FROM mensagens WHERE id_chat = $chatId AND id_de = $restauranteId ORDER BY data ASC";
  $result = mysqli_query($conn, $query);

  $messages = array();

  while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
  }

  // Converter as mensagens em formato JSON e enviar a resposta
  echo json_encode($messages);
} else {
  // Redirecionar se o restaurante não estiver registrado
  header("Location: login.php");
  exit();
}

// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>
