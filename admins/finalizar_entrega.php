<?php

require_once 'conexao.php';

// Verifique se o ID da entrega foi enviado corretamente
if (isset($_POST['id'])) {
  $id = $_POST['id'];

  // Execute a lógica para atualizar o status da entrega para "Concluída"
  // Use o valor de $idEntrega para identificar a entrega correta no seu banco de dados

  // Exemplo de código para atualizar o status usando o MySQLi
  $sql = "UPDATE entregas SET status = 'concluida' WHERE id = $id";
  $result = $conn->query($sql);

  // Verifique se a atualização foi bem-sucedida e retorne uma resposta adequada
  if ($result) {
    echo "Status da entrega atualizado com sucesso.";
  } else {
    echo "Ocorreu um erro ao atualizar o status da entrega.";
  }
}
?>
