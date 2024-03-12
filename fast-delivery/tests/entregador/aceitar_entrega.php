<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $identificadorEntregador = $_POST['identificador'];
  $entregaId = $_POST['entrega_id'];

  // Verificar se a entrega já possui um entregador
  $sqlVerificarEntregador = "SELECT entregador FROM entregas WHERE id = '$entregaId'";
  $resultVerificarEntregador = $conn->query($sqlVerificarEntregador);

  if ($resultVerificarEntregador->num_rows > 0) {
    $entregaAtual = $resultVerificarEntregador->fetch_assoc();
    $entregadorAtual = $entregaAtual['entregador'];

    // Se a entrega já tiver um entregador, exibir uma mensagem
    if (!empty($entregadorAtual)) {
      echo "Esta entrega já possui um entregador associado.";
      exit();
    }
  }

  // Consultar o entregador pelo identificador fornecido
  $sqlEntregador = "SELECT * FROM entregadores WHERE cod_motoboy = '$identificadorEntregador'";
  $resultEntregador = $conn->query($sqlEntregador);

  if ($resultEntregador->num_rows > 0) {
    $entregador = $resultEntregador->fetch_assoc();
    $entregadorId = $entregador['id'];

    // Associar o entregador à entrega correspondente
    $sqlAtualizarEntrega = "UPDATE entregas SET entregador = '$entregadorId' WHERE id = '$entregaId'";
    if ($conn->query($sqlAtualizarEntrega) === TRUE) {
      echo "Entregador associado com sucesso!";
    } else {
      echo "Erro ao associar o entregador: " . $conn->error;
    }
  } else {
    echo "Identificador do entregador inválido!";
  }
}
?>
