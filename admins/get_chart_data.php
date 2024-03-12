<?php
// Configurações da conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

// Cria a conexão com o banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verifica se ocorreu um erro na conexão
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Obtenha o ano a partir dos parâmetros da solicitação
$anoSelecionado = isset($_GET['ano']) ? $_GET['ano'] : date('Y');

// Consulta SQL para obter o total de entregas por mês no ano selecionado
$query = "SELECT MONTH(data_hora) AS mes, COUNT(*) AS total_entregas FROM entregas WHERE YEAR(data_hora) = $anoSelecionado GROUP BY mes";
$result = mysqli_query($conn, $query);

$totalEntregasPorMes = array_fill(1, 12, 0); // Inicializa o array com 12 meses, todos com valor zero

// Processa os resultados da consulta
while ($row = mysqli_fetch_assoc($result)) {
    $mes = $row['mes'];
    $total = $row['total_entregas'];

    // Arredonda o valor para o inteiro mais próximo
    $total = floor($total);

    // Atualiza o valor do total de entregas para o mês correspondente
    $totalEntregasPorMes[$mes] = $total;
}

// Retorna os dados como um JSON
header('Content-Type: application/json');
echo json_encode(array_values($totalEntregasPorMes));


// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
