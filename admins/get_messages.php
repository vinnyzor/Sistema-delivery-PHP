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
$query = "SELECT id, nome_estabelecimento FROM restaurantes WHERE email='$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
  $row = mysqli_fetch_assoc($result);
  $restauranteId = $row['id'];
  $nomeEstabelecimento = $row['nome_estabelecimento'];

  // Consulta SQL para recuperar as mensagens relacionadas ao restaurante logado
  $query = "SELECT * FROM mensagens WHERE id_restaurante = $restauranteId";
  $result = mysqli_query($conn, $query);

  $messages = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $message = array(
      'id' => $row['id'],
      'id_de' => $row['id_de'],
      'id_chat' => $row['id_chat'],
      'mensagem' => $row['mensagem'],
      'data' => $row['data'],
      'id_de_admin' => $row['id_de_admin'],
      'nome_estabelecimento' => $nomeEstabelecimento,
      'lido' => $row['lido']
    );
    $messages[] = $message;
  }

  // Retornar as mensagens em formato JSON
  echo json_encode(array('messages' => $messages));
} else {
  header("Location: login.php");
  exit();
}

// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>
