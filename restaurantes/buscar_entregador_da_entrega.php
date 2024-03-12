<?php
// Verifique se o código do motoboy foi enviado corretamente
if (isset($_POST['entregador'])) {
  $entregador = $_POST['entregador'];


  require_once 'conexao.php';

  // Execute a lógica para buscar as informações do entregador no seu banco de dados
  // Use o valor de $codMotoboy para identificar o entregador correto

  // Exemplo de código para buscar as informações do entregador usando o MySQLi
  $sql = "SELECT * FROM entregadores WHERE id = $entregador";
  $result = $conn->query($sql);

  // Verifique se a consulta retornou algum resultado
  if ($result && $result->num_rows > 0) {
    $entregador = $result->fetch_assoc();

    // Retorne os dados do entregador como um objeto JSON
    echo json_encode($entregador);
  } else {
    echo "Nenhum entregador encontrado com o código fornecido.";
  }
}
?>
