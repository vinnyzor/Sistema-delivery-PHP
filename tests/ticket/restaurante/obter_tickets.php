<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Verifique se a sessão contém o email do restaurante logado
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se ocorreu algum erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Recupere o email do restaurante logado da sessão
$email = $_SESSION['email'];

// Buscar o ID do restaurante logado
$queryRestaurante = "SELECT id FROM restaurantes WHERE email = '$email'";
$resultadoRestaurante = $conn->query($queryRestaurante);

if ($resultadoRestaurante && $resultadoRestaurante->num_rows === 1) {
    $rowRestaurante = $resultadoRestaurante->fetch_assoc();
    $restauranteId = $rowRestaurante['id'];

    // Consulta para obter a lista de tickets do restaurante
    $sql = "SELECT * FROM tickets WHERE restaurante_id = $restauranteId";
    $result = $conn->query($sql);

    // Construa a tabela HTML com os tickets
    $table = '<table>';
    $table .= '<tr><th>ID</th><th>Assunto</th><th>Mensagem</th><th>Status</th><th>Data Abertura</th></tr>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $table .= '<tr>';
            $table .= '<td>' . $row['id'] . '</td>';
            $table .= '<td>' . $row['assunto'] . '</td>';
            $table .= '<td>' . $row['mensagem'] . '</td>';
            $table .= '<td>' . $row['status'] . '</td>';
            $table .= '<td>' . $row['data_abertura'] . '</td>';
            $table .= '</tr>';
        }
    }

    $table .= '</table>';

    echo $table;
} else {
    echo "Erro ao buscar o ID do restaurante";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
