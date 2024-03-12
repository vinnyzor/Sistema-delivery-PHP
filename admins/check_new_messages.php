<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

// Verificar se o restaurante está logado
if (!isset($_SESSION['email'])) {
  echo json_encode(array('success' => false, 'error' => 'Restaurante not logged in.'));
  exit();
}

require_once 'conexao.php';

$email = $_SESSION['email'];

$sql = "SELECT * FROM restaurantes WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();
  $restauranteId = $row['id'];
} else {
  echo json_encode(array('success' => false, 'error' => 'Restaurante not found.'));
  exit();
}

// Consultar o número de mensagens não lidas para o restaurante
$query = "SELECT COUNT(*) AS totalMensagensNaoLidas FROM mensagens WHERE id_restaurante = $restauranteId AND lido = 0 AND id_de_admin = 1";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$totalMensagensNaoLidas = $row['totalMensagensNaoLidas'];

// Retornar a quantidade de mensagens não lidas em formato JSON
echo json_encode(array('success' => true, 'totalMensagensNaoLidas' => $totalMensagensNaoLidas));
exit();

?>