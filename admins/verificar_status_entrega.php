<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $entregaId = $_POST['entregaId'];

  // Realize as consultas no banco de dados para obter o status da entrega
  $sql = "SELECT status FROM entregas WHERE id = '$entregaId'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $status = $row['status'];

    // Retorne o status da entrega como resposta
    echo $status;
  } else {
    // Se não encontrar a entrega, retorne uma resposta vazia ou uma mensagem de erro, conforme necessário
    echo "Status da entrega não encontrado";
  }
}
?>
