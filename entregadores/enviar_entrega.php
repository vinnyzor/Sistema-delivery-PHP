<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

require_once 'conexao.php';

// Função para gerar o link aleatório
function gerarLinkAleatorio() {
  $caracteresPermitidos = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $tamanhoLink = 10;
  $link = '';

  for ($i = 0; $i < $tamanhoLink; $i++) {
    $randomIndex = rand(0, strlen($caracteresPermitidos) - 1);
    $link .= $caracteresPermitidos[$randomIndex];
  }

  return $link;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $restauranteId = $_POST['restauranteId'];
  $enderecoCliente = $_POST['enderecoCliente'];
  $distancia = $_POST['distancia'];
  $taxaEntrega = $_POST['taxaEntrega'];
  
  // Diminuir 25 centavos do valor da taxa de entrega
  $taxaEntregaEntregador = $taxaEntrega - 0.25;
  
  // Obter a data e hora atual
  $dataHora = date('Y-m-d H:i:s');




  // Gerar o link aleatório
  $linkAleatorio = gerarLinkAleatorio();


  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.whatsgw.com.br/api/WhatsGw/Send',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"apikey" : "f8c401b5-5f5e-4f22-b978-7457ea9f6434",
"phone_number" : "5561998138142",
"contact_phone_number" : "120363040678169432",
"message_custom_id" : "yoursoftwareid",
"message_type" : "text",
"message_to_group" : "1",
"message_body" : "Chegou uma nova entrega acesse agora o link: \n\nhttp://localhost/fast-delivery/tests/entregador/index.php?link='.$linkAleatorio.'",
"check_status" : "1"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;



  // Realize as devidas validações e formatações dos dados, se necessário

  $sql = "INSERT INTO entregas (restaurante_id, endereco_cliente, distancia, taxa_entrega, taxa_entrega_entregador, data_hora, link) VALUES ('$restauranteId', '$enderecoCliente', '$distancia', '$taxaEntrega', '$taxaEntregaEntregador', '$dataHora', '$linkAleatorio')";
  
  if ($conn->query($sql) === TRUE) {
    echo "Entrega salva com sucesso!";
  } else {
    echo "Erro ao salvar entrega: " . $conn->error;
  }
}
?>
