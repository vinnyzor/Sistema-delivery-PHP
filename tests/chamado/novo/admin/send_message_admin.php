<?php
session_start();
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

  // Verificar se foi fornecido um ID de chat válido e uma mensagem
  if (isset($_POST['chatId']) && isset($_POST['message'])) {
    $chatId = $_POST['chatId'];
    $message = $_POST['message'];
    $restauranteId = $_POST['restauranteId'];

    // Inserir a mensagem no banco de dados
    $insertQuery = "INSERT INTO mensagens (id_de, id_chat, mensagem, data, lido, id_restaurante, id_de_admin) VALUES ($restauranteId , $chatId, '$message', NOW(), 0, $restauranteId, 1)";
    mysqli_query($conn, $insertQuery);

    // Responder com uma resposta de sucesso
    $response = array('success' => true);
    echo json_encode($response);
    exit();
  }
}

// Responder com uma resposta de erro em caso de falha
$response = array('success' => false);
echo json_encode($response);
exit();
?>
