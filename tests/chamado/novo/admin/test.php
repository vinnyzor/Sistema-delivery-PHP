<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require_once 'db_connection.php';

// Verificar se o admin está logado
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

function time_ago($datetime) {
  $now = new DateTime();
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  if ($diff->y > 0) {
    return $diff->y . " ano" . ($diff->y > 1 ? "s" : "") . " atrás";
  } elseif ($diff->m > 0) {
    return $diff->m . " mês" . ($diff->m > 1 ? "es" : "") . " atrás";
  } elseif ($diff->d > 0) {
    return $diff->d . " dia" . ($diff->d > 1 ? "s" : "") . " atrás";
  } elseif ($diff->h > 0) {
    return $diff->h . " hora" . ($diff->h > 1 ? "s" : "") . " atrás";
  } elseif ($diff->i > 0) {
    return $diff->i . " minuto" . ($diff->i > 1 ? "s" : "") . " atrás";
  } elseif ($diff->s > 0) {
    return $diff->s . " segundo" . ($diff->s > 1 ? "s" : "") . " atrás";
  } else {
    return "Agora mesmo";
  }
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
