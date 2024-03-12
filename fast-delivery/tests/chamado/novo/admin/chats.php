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
  $selectedChatId = isset($_GET['chatId']) ? $_GET['chatId'] : null;

  // Consulta SQL para recuperar os chats do admin
  $query = "SELECT c.id, r.nome_estabelecimento, c.lastupdate
            FROM chats c
            LEFT JOIN restaurantes r ON c.id_de = r.id";

  $result = mysqli_query($conn, $query);

  $chats = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $chat = array(
      'id' => $row['id'],
      'nome_estabelecimento' => $row['nome_estabelecimento'],
      'lastupdate' => $row['lastupdate']
    );
    $chats[] = $chat;
  }

  // Consulta SQL para recuperar o ID do restaurante com base no ID do chat
$query = "SELECT id_de FROM chats WHERE id = $selectedChatId";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$restauranteId = $row['id_de'];

  // Consulta SQL para recuperar as mensagens do chat selecionado
  $selectedChatMessages = array();
  if ($selectedChatId !== null) {
    $query = "SELECT m.id, m.mensagem, m.data, r.nome_estabelecimento, a.nome_admin
              FROM mensagens m
              INNER JOIN restaurantes r ON m.id_restaurante = r.id
              LEFT JOIN admins a ON m.id_de_admin = a.id
              WHERE m.id_chat = $selectedChatId
              ORDER BY m.data ASC";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
      $message = array(
        'id' => $row['id'],
        'mensagem' => $row['mensagem'],
        'data' => $row['data'],
        'nome_estabelecimento' => $row['nome_estabelecimento'],
        'nome_admin' => $row['nome_admin']
      );
      $selectedChatMessages[] = $message;
    }
  }
} else {
  header("Location: login.php");
  exit();
}
?>


<!DOCTYPE html>
<html>
<head>

  <title>Chats</title>
  <style>
    .container {
      display: flex;
    }

    

    .test {
      flex: 2;
    }

    /* Estilos para as mensagens */
    .messages {
      flex: 1;
      padding: 20px;
      background-color: #f0f0f0;
    }

    .messages h2 {
      margin-bottom: 10px;
    }

    .messages p {
      margin-bottom: 5px;
    }

    .message.admin {
      text-align: right;
      color: blue;
    }

    .message.restaurante {
      text-align: left;
      color: green;
    }
  </style>
</head>
<body>
  <style type="text/css">
    .container {
  display: flex;
}

.chat-list {
  flex: 1;
  padding: 20px;
  background-color: #f0f0f0;
}

.chat-list ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.chat-list li {
  margin-bottom: 10px;
}

.chat-list a {
  display: block;
  padding: 5px 10px;
  background-color: #fff;
  color: #333;
  text-decoration: none;
  border-radius: 5px;
}

.chat-list a:hover {
  background-color: #e0e0e0;
}

.messages {
  flex: 2;
  padding: 20px;
}

.messages h2 {
  margin-bottom: 10px;
}

.messages p {
  margin-bottom: 5px;
}
</style>
  <h1>Chats</h1>

  <div class="container">
    <div class="chat-list">
      <h2>Lista de Chats</h2>

      <ul>
        <?php foreach ($chats as $chat): ?>
          <li>
            <a href="?chatId=<?php echo $chat['id']; ?>">
              <?php echo $chat['nome_estabelecimento']; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

     <div class="test">
      <div class="messages">
        <?php if ($selectedChatId !== null): ?>
    <h2>Mensagens</h2>

    <?php foreach ($selectedChatMessages as $message): ?>
        <?php
        $messageClass = ($message['nome_admin'] !== null) ? 'admin' : 'restaurante';
        ?>
        <p class="message <?php echo $messageClass; ?>">
            <span class="time-ago" data-datetime="<?php echo $message['data']; ?>">
                <?php echo ($message['data']); ?>
            </span> -
            <?php if ($message['nome_admin'] !== null): ?>
                (Admin) <?php echo $message['nome_admin']; ?>
            <?php else: ?>
                (Restaurante) <?php echo $message['nome_estabelecimento']; ?>
            <?php endif; ?>:
            <?php echo $message['mensagem']; ?>
        </p>
    <?php endforeach; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
      <script src="pt-br.js"></script>
    <script>
        function updateMessageTimeAgo() {
            $('.time-ago').each(function() {
                var datetime = $(this).data('datetime');
                var formattedTime = moment(datetime).fromNow();
                $(this).text(formattedTime);
            });
        }

        // Atualize o tempo decorrido a cada segundo
        setInterval(updateMessageTimeAgo, 3000);
        updateMessageTimeAgo();
    </script>
<?php endif; ?>
      </div>
<?php
$lastMessageId = end($selectedChatMessages)['id'];
if ($lastMessageId === null) {
    $lastMessageId = -1;
}

$chatId = $selectedChatId ?? -1; // Usando o operador de coalescência nula para definir um valor padrão de -1 se $selectedChatId for nulo
$restauranteId = $restauranteId ?? -1; // Usando o operador de coalescência nula para definir um valor padrão de -1 se $restauranteId for nulo

?>
      <form id="messageForm" method="post">
        <input type="text" name="message" id="messageInput">
        <button type="submit">Enviar</button>
      </form>
    </div>
  </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
      // Armazena o ID da última mensagem exibida
      var lastMessageId = <?php echo $lastMessageId; ?>;

      // Função para buscar novas mensagens
      function fetchNewMessages() {
        var chatId = <?php echo $chatId; ?>; // Obtém o ID do chat

        $.ajax({
          url: 'fetch_new_messages.php',
          type: 'POST',
          data: { chatId: chatId, lastMessageId: lastMessageId },
          dataType: 'json', // Adicione esta linha para especificar o tipo de dados esperado
          success: function(response) {
            if (response && response.length > 0) {
              response.forEach(function(message) {
                var messageHtml = '<p class="message ' + (message.nome_admin !== null ? 'admin' : 'restaurante') + '"><span class="time-ago" data-datetime="' +
                  message.data + '">' +
                  message.data + '</span> - ' +
                  (message.nome_admin !== null ? '(Admin) ' + message.nome_admin : '(Restaurante) ' + message.nome_estabelecimento) +
                  ': ' + message.mensagem +
                  '</p>';

                $('.messages').append(messageHtml);

                lastMessageId = message.id; // Atualiza o ID da última mensagem
                updateMessageTimeAgo();
              });
            }
          }
        });
      }

      // Inicia a busca de novas mensagens a cada 1 segundo
      setInterval(fetchNewMessages, 1000);



setInterval(function() {
  $.ajax({
    url: 'update_last_login.php',
    type: 'GET',
    success: function(response) {
      console.log(response); // Exibe a resposta do servidor no console
    },
    error: function(xhr, status, error) {
      console.log(xhr.responseText); // Exibe o erro no console
    }
  });
}, 10000); // Executa a cada 10 segundos (10000 milissegundos)

function fetchNewChats() {
  $.ajax({
    url: 'fetch_new_chats.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response && response.length > 0) {
        // Limpa a lista de chats existente
        $('.chat-list ul').empty();

        response.forEach(function(chat) {
          var chatHtml = '<li><a href="?chatId=' + chat.id + '">' + chat.nome_estabelecimento + '</a></li>';
          $('.chat-list ul').append(chatHtml);
        });
      }
    }
  });
}

// Define o intervalo de busca de novos chats (em milissegundos)
var fetchNewChatsInterval = 2000; // 2 segundos

// Inicia a busca de novos chats imediatamente
fetchNewChats();

// Inicia a busca de novos chats em intervalos regulares
setInterval(fetchNewChats, fetchNewChatsInterval);
      // Enviar uma nova mensagem
      $('#messageForm').submit(function(e) {
        e.preventDefault();
        var messageInput = $('#messageInput');
        var message = messageInput.val();
        var chatId = <?php echo $chatId; ?>; // Obtém o ID do chat
        var restauranteId = <?php echo $restauranteId; ?>;

        $.ajax({
          url: 'send_message_admin.php',
          type: 'POST',
          data: { chatId: chatId, message: message, restauranteId: restauranteId },
          success: function() {
            messageInput.val('');
            fetchNewMessages();
          }
        });
      });
    });
  </script>
  <script>
  // Função para atualizar o tempo
  function updateMessageTime() {
    $('.message-time').each(function() {
      var messageTime = $(this).data('time');
      var formattedTime = moment(messageTime).fromNow();
      $(this).text(formattedTime);
    });
  }

  // Atualiza o tempo a cada 1 segundo
  setInterval(updateMessageTime, 1000);
</script>
</body>
</html>
