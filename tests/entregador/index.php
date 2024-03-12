<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['link'])) {
  $link = $_GET['link'];

  // Verificar se o link existe na tabela de entregas
  $sql = "SELECT * FROM entregas WHERE link = '$link'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $entrega = $result->fetch_assoc();
    $entregaId = $entrega['id'];

    // Exibir as informações da entrega
    echo "Informações da entrega:<br>";
    echo "ID: " . $entrega['id'] . "<br>";
    echo "Restaurante ID: " . $entrega['restaurante_id'] . "<br>";
    echo "Endereço do Cliente: " . $entrega['endereco_cliente'] . "<br>";
    echo "Distância: " . $entrega['distancia'] . "<br>";
    echo "Taxa de Entrega: " . $entrega['taxa_entrega'] . "<br>";

    // Exibir o formulário para o entregador inserir o identificador e aceitar a entrega
    echo "<form method='POST' action='aceitar_entrega.php'>";
    echo "Identificador do Entregador: <input type='text' name='identificador' required><br>";
    echo "<input type='hidden' name='entrega_id' value='$entregaId'>";
    echo "<input type='submit' value='Aceitar Entrega'>";
    echo "</form>";
  } else {
    // O link não existe na tabela de entregas
    echo "Link inválido!";
  }
} else {
  // Nenhum link foi fornecido
  echo "Link não encontrado!";
}
?>
