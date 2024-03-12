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
$sql = "SELECT DATE_FORMAT(data_entrega, '%Y-%m') AS mes, COUNT(*) AS total_entregas FROM entregas GROUP BY mes ORDER BY mes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Array para armazenar os dados formatados
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $mes = date('Y-m', strtotime($row['mes']));
        $totalEntregas = $row['total_entregas'];

        // Calcula o crescimento em relação ao mês anterior
        $crescimento = 0;
        if (count($data) > 0) {
            $mesAnterior = end($data)['total_entregas'];
            $crescimento = ($totalEntregas - $mesAnterior) / $mesAnterior * 100;
        }

        // Adiciona os dados formatados ao array
        $data[] = array(
            'mes' => $mes,
            'crescimento' => $crescimento
        );
    }

    // Converte o array para JSON
    $jsonData = json_encode($data);
} else {
    $jsonData = array(); // Retorna um array vazio se nenhum resultado for encontrado
}

// Fecha a conexão com o banco de dados
$conn->close();

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Retorna os dados como JSON
echo $jsonData;
?>