<?php
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

// Consulta SQL para obter os dados das entregas
$sql = "SELECT DATE_FORMAT(data_hora, '%Y-%m-%d') AS data, COUNT(*) AS total_entregas FROM entregas GROUP BY data ORDER BY data";
$result = $conn->query($sql);

$data = array(); // Array para armazenar os dados formatados
$primeiroMes = null; // Variável para armazenar o primeiro mês com entregas

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dataEntrega = $row['data'];
        $totalEntregas = $row['total_entregas'];

        // Se for o primeiro mês com entregas, define o valor como zero para o cálculo do crescimento
        if ($primeiroMes === null) {
            $primeiroMes = $totalEntregas;
            $crescimento = 0;
        } else {
            // Calcula o crescimento em relação ao primeiro mês com entregas
            $crescimento = (($totalEntregas - $primeiroMes) / $primeiroMes) * 100;
        }

        // Adiciona os dados formatados ao array
        $data[] = array(
            'data' => $dataEntrega,
            'crescimento' => $crescimento
        );
    }
}

// Converte o array para JSON
$jsonData = json_encode($data);

// Fecha a conexão com o banco de dados
$conn->close();

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Retorna os dados como JSON
echo $jsonData;
?>
