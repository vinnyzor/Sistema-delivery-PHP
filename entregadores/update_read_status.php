<?php
require_once 'db_connection.php';

// Verificar se o restaurante está logado e obter seu ID
session_start();
if (!isset($_SESSION['email'])) {
  echo json_encode(array('success' => false, 'error' => 'Restaurant not logged in.'));
  exit();
}

$email = $_SESSION['email'];
$query = "SELECT id FROM restaurantes WHERE email='$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
  $row = mysqli_fetch_assoc($result);
  $restauranteId = $row['id'];

  // Atualizar o status de leitura das mensagens
  $query = "UPDATE mensagens SET lido = 1 WHERE id_restaurante = $restauranteId AND id_de_admin = 1";
  if (mysqli_query($conn, $query)) {
    echo json_encode(array('success' => true));
  } else {
    echo json_encode(array('success' => false, 'error' => 'Failed to update read status.'));
  }
} else {
  echo json_encode(array('success' => false, 'error' => 'Restaurant not found.'));
}

mysqli_close($conn);
?>
