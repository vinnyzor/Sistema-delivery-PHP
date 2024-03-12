<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Obtenha o dia da semana atual
$diaSemana = date('N');


// Consulta SQL para obter o horário de funcionamento
$sql = "SELECT horario_abertura, horario_fechamento
        FROM horarios
        WHERE id = $diaSemana";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Retorne os resultados como um objeto JSON
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    // Caso não haja registros encontrados, exiba a mensagem "Loja fechada"
    $mensagem = "Loja fechada";
    echo json_encode(["mensagem" => $mensagem]);
}

$conn->close();
?>