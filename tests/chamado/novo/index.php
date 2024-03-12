<?php
session_start();
require_once 'db_connection.php';

// Verificar se o restaurante está logado
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Suporte em Tempo Real</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="client.js"></script>
</head>
<body>

<style type="text/css">
    .admin-message {
  background-color: #f1f1f1;
  text-align: left;
  padding: 10px;
  margin-bottom: 10px;
}

.restaurant-message {
  background-color: #e1ffe1;
  text-align: right;
  padding: 10px;
  margin-bottom: 10px;
}

</style>
    <h1>Conversas</h1>
    <div id="conversations"></div>
    <h2>Mensagens</h2>
    <div id="messages"></div>
    <form id="messageForm">
        <input type="text" id="messageInput" placeholder="Digite sua mensagem" />
        <button type="submit">Enviar</button>
    </form>

       
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="pt-br.js"></script>

</body>
</html>
