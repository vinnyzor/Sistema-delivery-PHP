<?php
// Conexão com o banco de dados (substitua pelos dados de sua configuração)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
  die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta SQL para buscar os dados de entregas
$sql = "SELECT * FROM entregas";
$result = $conn->query($sql);

// Array para armazenar os dados de entregas
$entregas = array();

// Verificar se a consulta retornou resultados
if ($result->num_rows > 0) {
  // Percorrer os resultados e adicionar ao array de entregas
  while ($row = $result->fetch_assoc()) {
    $entrega = array(
      'id' => $row['id'],
      'restaurante_id' => $row['restaurante_id'],
      'endereco_cliente' => $row['endereco_cliente'],
      'distancia' => $row['distancia'],
      'data_hora' => $row['data_hora'],
      'status' => $row['status'],
      'taxa_entrega_entregador' => $row['taxa_entrega_entregador'],
      'taxa_entrega' => $row['taxa_entrega']
    );
    $entregas[] = $entrega;
  }
}

// Fechar a conexão com o banco de dados
$conn->close();

// Definir o cabeçalho para indicar que a resposta é JSON
header('Content-Type: application/json');

// Retornar os dados de entregas como JSON
echo json_encode($entregas);
?>
