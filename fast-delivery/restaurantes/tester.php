<?php
// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

// Conecte-se ao banco de dados
$conexao = mysqli_connect($servername, $username, $password, $dbname);

// Verifique se a conexão foi estabelecida corretamente
if (!$conexao) {
  die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Lógica para obter os dados do banco de dados
$query = "SELECT * FROM entregas WHERE data_hora >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
$result = mysqli_query($conexao, $query);
$dados = array();

while ($row = mysqli_fetch_assoc($result)) {
  $valor = $row['valor'];
  $dados[] = $valor;
}

// Converta o array de dados em formato JSON
$dados_json = json_encode($dados);

// Retorne os dados como resposta
echo $dados_json;

// Feche a conexão com o banco de dados
mysqli_close($conexao);
?>
