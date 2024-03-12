<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Verifique se a sessão contém o email do admin logado
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

// Consulta para obter os tickets abertos
$sqlAbertos = "SELECT * FROM tickets WHERE status = 'Aguardando Resposta'";
$resultAbertos = $conn->query($sqlAbertos);

$ticketsAbertos = '<table>';
$ticketsAbertos .= '<tr><th>ID</th><th>Assunto</th><th>Mensagem</th><th>Status</th><th>Data Abertura</th><th>Opções</th></tr>';

if ($resultAbertos->num_rows > 0) {
    while ($row = $resultAbertos->fetch_assoc()) {
        $ticketsAbertos .= '<tr>';
        $ticketsAbertos .= '<td>' . $row['id'] . '</td>';
        $ticketsAbertos .= '<td>' . $row['assunto'] . '</td>';
        $ticketsAbertos .= '<td>' . $row['mensagem'] . '</td>';
        $ticketsAbertos .= '<td>' . $row['status'] . '</td>';
        $ticketsAbertos .= '<td>' . $row['data_abertura'] . '</td>';
        $ticketsAbertos .= '<td><button class="responderTicketBtn" data-id="' . $row['id'] . '">Responder</button> <button class="excluirTicketBtn" data-id="' . $row['id'] . '">Excluir</button></td>';
        $ticketsAbertos .= '</tr>';
    }
}

$ticketsAbertos .= '</table>';

// Consulta para obter os tickets respondidos
$sqlRespondidos = "SELECT * FROM tickets WHERE status = 'Respondido'";
$resultRespondidos = $conn->query($sqlRespondidos);

$ticketsRespondidos = '<table>';
$ticketsRespondidos .= '<tr><th>ID</th><th>Assunto</th><th>Mensagem</th><th>Status</th><th>Data Abertura</th><th>Opções</th></tr>';

if ($resultRespondidos->num_rows > 0) {
    while ($row = $resultRespondidos->fetch_assoc()) {
        $ticketsRespondidos .= '<tr>';
        $ticketsRespondidos .= '<td>' . $row['id'] . '</td>';
        $ticketsRespondidos .= '<td>' . $row['assunto'] . '</td>';
        $ticketsRespondidos .= '<td>' . $row['mensagem'] . '</td>';
        $ticketsRespondidos .= '<td>' . $row['status'] . '</td>';
        $ticketsRespondidos .= '<td>' . $row['data_abertura'] . '</td>';
        $ticketsRespondidos .= '<td><button class="excluirTicketBtn" data-id="' . $row['id'] . '">Excluir</button></td>';
        $ticketsRespondidos .= '</tr>';
    }
}

$ticketsRespondidos .= '</table>';

// Fecha a conexão com o banco de dados
$conn->close();

// Retorna os tickets abertos e respondidos em formato JSON
echo json_encode(array('abertos' => $ticketsAbertos, 'respondidos' => $ticketsRespondidos));
?>
