<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se ocorreu algum erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$email = $_SESSION['email'];

$sql = "SELECT * FROM restaurantes WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();
  $restauranteId = $row['id'];

  // Verifica as dívidas em aberto do restaurante
  $sql = "SELECT * FROM financas WHERE restaurante_id='$restauranteId' AND status_divida='em_aberto' ORDER BY dia_divida ASC LIMIT 2";
  $result = $conn->query($sql);

  if (!$result) {
    die("Erro na consulta: " . $conn->error);
  }

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $divida = $row['divida'];
      $valor_devido = $row['valor_devido'];
      $dia_divida = $row['dia_divida'];
    $dataFormatada = date('d/m/Y', strtotime($dia_divida));
      
    echo "Valor Devido: R$ $valor_devido<br>";
      echo "Data referente: $dataFormatada<br>";

      echo "<br>";
    }
  } else {
    echo "Aguarde o fechamento do próximo repasse.";
  }

} else {
  header("Location: login.php");
  exit();
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
