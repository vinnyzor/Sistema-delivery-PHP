<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require_once 'db_connection.php';

// Verificar se o admin está logado
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
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
  if (isset($_GET['chatId'])) {
    $chatId = $_GET['chatId'];

// Consulta SQL para recuperar o ID do restaurante com base no ID do chat
$query = "SELECT id_de FROM chats WHERE id = $chatId";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$restauranteId = $row['id_de'];


    // Consulta SQL para recuperar as mensagens do chat específico
    $query = "SELECT m.id, m.mensagem, m.data, r.nome_estabelecimento, a.nome_admin
              FROM mensagens m
              INNER JOIN restaurantes r ON m.id_restaurante = r.id
              LEFT JOIN admins a ON m.id_de_admin = a.id
              WHERE m.id_chat = $chatId
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
  } else {
    // Redirecionar de volta para a página de chats se não houver um ID de chat fornecido
    header("Location: chats.php");
    exit();
  }
} else {
  header("Location: login.php");
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Chat</title>
</head>
<body>
  <h1>Chat</h1>
  <h2><?php echo $nomeEstabelecimento; ?></h2>

  <div id="messages">
    <?php foreach ($messages as $message): ?>
      <p>
        <?php echo $message['data']; ?> - 
        <?php 
          if ($message['nome_admin'] !== null) {
            echo "(Admin) " . $message['nome_admin'];
          } else {
            echo "(Restaurante) " . $message['nome_estabelecimento'];
          }
        ?>:
        <?php echo $message['mensagem']; ?>
      </p>
    <?php endforeach; ?>
  </div>

  <form id="messageForm" method="post">
    <input type="text" name="message" id="messageInput">
    <button type="submit">Enviar</button>
  </form>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function() {
    // Armazena o ID da última mensagem exibida
    var lastMessageId = <?php echo end($messages)['id']; ?>;

    // Função para carregar as mensagens atualizadas
    function loadMessages() {
      var chatId = <?php echo $chatId; ?>; // Obtém o ID do chat

      $.ajax({
        url: 'get_new_messages.php',
        type: 'POST',
        data: { chatId: chatId, lastMessageId: lastMessageId },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            var newMessages = response.messages;

            // Adiciona apenas as novas mensagens à div "messages"
            newMessages.forEach(function(message) {
              var messageText = message.data + ' - ';

              if (message.nome_admin !== null) {
                messageText += "(Admin) " + message.nome_admin;
              } else {
                messageText += "(Restaurante) " + message.nome_estabelecimento;
              }

              messageText += ': ' + message.mensagem;

              $('<p>').text(messageText).appendTo('#messages');

              // Atualiza o ID da última mensagem exibida
              lastMessageId = message.id;
            });
          }
        },
        complete: function() {
          // Chama a função novamente após 2 segundos
          setTimeout(loadMessages, 1000);
        }
      });
    }

    // Chama a função para carregar as mensagens pela primeira vez
    loadMessages();

    // Enviar uma nova mensagem
    $('#messageForm').submit(function(e) {
      e.preventDefault();
      var messageInput = $('#messageInput');
      var message = messageInput.val();
      var chatId = <?php echo $chatId; ?>; // Obtém o ID do chat
      var restauranteId = <?php echo $restauranteId; ?>; // Obtém o ID do restaurante

      $.ajax({
        url: 'send_message_admin.php',
        type: 'POST',
        data: { chatId: chatId, message: message, restauranteId: restauranteId },
        success: function() {
          messageInput.val('');
           loadMessages();
        }
      });
    });
  });
</script>


</body>
</html>
