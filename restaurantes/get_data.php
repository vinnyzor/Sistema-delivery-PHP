<?php
// Faça a conexão com o banco de dados aqui
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta para obter os dados das entregas
$query = "SELECT DATE(data_hora) AS dia, COUNT(*) AS total_entregas, SUM(taxa_entrega) AS total_taxa_entrega
          FROM entregas
          WHERE WEEK(data_hora) = WEEK(CURRENT_DATE())
          GROUP BY dia
          ORDER BY dia";

$result = mysqli_query($conexao, $query);

// Array para armazenar os dados das entregas
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
  $data[] = [
    'dia' => $row['dia'],
    'total_entregas' => intval($row['total_entregas']),
    'total_taxa_entrega' => floatval($row['total_taxa_entrega'])
  ];
}

// Retorne os dados em formato JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
