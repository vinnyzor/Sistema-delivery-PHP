<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['telefone'])) {
  header("Location: login.php");
  exit();
}

require_once 'conexao.php';

$telefone = $_SESSION['telefone'];

$sql = "SELECT * FROM entregadores WHERE telefone='$telefone'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();
  $entregadorId = $row['id'];
  
  // Receber o período selecionado
  $periodo = $_POST['periodo'];

  // Estabelecer conexão com o banco de dados
  $mysqli = new mysqli('localhost', 'root', '', 'delivery');

  // Verificar se a conexão foi estabelecida com sucesso
  if ($mysqli->connect_error) {
    die('Erro na conexão com o banco de dados: ' . $mysqli->connect_error);
  }

  // Construir a consulta SQL com base no período selecionado
  if ($periodo === 'diaAtual') {
    $sql = "SELECT * FROM entregas WHERE entregador='$entregadorId' AND DATE(data_hora) = CURDATE()";
  } else if ($periodo === 'semanaAtual') {
    $sql = "SELECT * FROM entregas WHERE entregador='$entregadorId' AND WEEK(data_hora) = WEEK(CURDATE())";
  } else if ($periodo === 'mesAtual') {
    $sql = "SELECT * FROM entregas WHERE entregador='$entregadorId' AND MONTH(data_hora) = MONTH(CURDATE())";
  } else if ($periodo === 'todasEntregas') {
    $sql = "SELECT * FROM entregas WHERE entregador='$entregadorId'";
  } else {
    // Período inválido, retornar uma resposta de erro ou tratar de outra forma adequada
  }

  // Executar a consulta SQL
  $result = $mysqli->query($sql);

  // Verificar se a consulta foi bem-sucedida
  if ($result) {
    // Construir um array com os dados das entregas
    $entregas = array();
    while ($row = $result->fetch_assoc()) {
      $entregas[] = $row;
    }

    // Enviar os dados das entregas como resposta em formato JSON
    echo json_encode($entregas);
  } else {
    // Lidar com erros de consulta aqui
    echo 'Erro na consulta: ' . $mysqli->error;
  }

  // Fechar a conexão com o banco de dados
  $mysqli->close();
} else {
  header("Location: login.php");
  exit();
}
?>
