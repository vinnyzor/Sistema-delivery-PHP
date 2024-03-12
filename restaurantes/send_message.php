<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

require_once 'db_connection.php';

$email = $_SESSION['email'];

$sql = "SELECT * FROM restaurantes WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();
  $restauranteId = $row['id'];

  // Verificar se a conversa já existe entre o restaurante e o admin
  $query = "SELECT * FROM chats WHERE (id_de = $restauranteId AND id_para = '1') OR (id_de = '1' AND id_para = $restauranteId)";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) === 0) {
    // A conversa não existe, então vamos criá-la
    $insertQuery = "INSERT INTO chats (id_de, id_para, lastupdate) VALUES ($restauranteId, '1', NOW())";
    mysqli_query($conn, $insertQuery);

    // Obter o ID do chat recém-criado
    $chatId = mysqli_insert_id($conn);
  } else {
    // A conversa já existe, obter o ID do chat existente
    $chat = mysqli_fetch_assoc($result);
    $chatId = $chat['id'];

    // Atualizar o campo 'lastupdate' da conversa existente
    $updateQuery = "UPDATE chats SET lastupdate = NOW() WHERE id = $chatId";
    mysqli_query($conn, $updateQuery);
  }

  // Inserir a mensagem no chat específico
  $message = $_POST['message'];
  $insertMessageQuery = "INSERT INTO mensagens (id_de, id_chat, mensagem, data, lido, id_restaurante) VALUES ($restauranteId, $chatId, '$message', NOW(), 0, $restauranteId)";
  mysqli_query($conn, $insertMessageQuery);

  // Recuperar as mensagens do chat
  $retrieveMessagesQuery = "SELECT * FROM mensagens WHERE id_chat = $chatId";
  $messagesResult = mysqli_query($conn, $retrieveMessagesQuery);

  // Exibir as mensagens no chat
  while ($message = mysqli_fetch_assoc($messagesResult)) {
    echo "<p>{$message['mensagem']}</p>";
  }

} else {
  header("Location: login.php");
  exit();
}

// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>
