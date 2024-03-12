<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

require_once 'conexao.php';

// Função para gerar um link aleatório único
function gerarLinkAleatorio() {
  $caracteresPermitidos = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $tamanhoLink = 10;
  $link = '';

  do {
    $link = '';
    for ($i = 0; $i < $tamanhoLink; $i++) {
      $randomIndex = rand(0, strlen($caracteresPermitidos) - 1);
      $link .= $caracteresPermitidos[$randomIndex];
    }
  } while (verificarLinkExistente($link)); // Verificar se o link já existe no banco de dados

  return $link;
}

// Função para verificar se o link já existe no banco de dados
function verificarLinkExistente($link) {
  global $conn;

  $sql = "SELECT COUNT(*) AS count FROM entregas WHERE link = '$link'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $count = $row['count'];

  return $count > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $restauranteId = $_POST['restauranteId'];
  $enderecoCliente = $_POST['enderecoCliente'];
  $bairroEntrega = $_POST['bairroEntrega'];
  $codEntrega = $_POST['codEntrega'];
  $distancia = $_POST['distancia'];
  $taxaEntrega = $_POST['taxaEntrega'];

  // Diminuir 25 centavos do valor da taxa de entrega
  $taxaEntregaEntregador = $taxaEntrega - 0.25;

  // Obter a data e hora atual
  $dataHora = date('Y-m-d H:i:s');

  // Gerar o link aleatório
  $linkAleatorio = gerarLinkAleatorio();

  // Realize as devidas validações e formatações dos dados, se necessário

  $sql = "INSERT INTO entregas (restaurante_id, endereco_cliente, distancia, taxa_entrega, taxa_entrega_entregador, data_hora, link, bairro, cod_entrega) VALUES ('$restauranteId', '$enderecoCliente', '$distancia', '$taxaEntrega', '$taxaEntregaEntregador', '$dataHora', '$linkAleatorio', '$bairroEntrega', '$codEntrega')";

  if ($conn->query($sql) === TRUE) {
    $entregaId = $conn->insert_id;
    echo $entregaId;
  } else {
    echo "Erro ao salvar entrega: " . $conn->error;
  }
}
?>
