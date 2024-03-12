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

// Consulta para obter os tickets abertos com o nome do restaurante
$sqlAbertos = "SELECT t.*, r.nome_estabelecimento AS nome_restaurante FROM tickets t
               INNER JOIN restaurantes r ON t.restaurante_id = r.id
               WHERE t.status = 'Aguardando Resposta'";
$resultAbertos = $conn->query($sqlAbertos);

$ticketsAbertos = '<table class="rounded-table">';
$ticketsAbertos .= '<tr><th>ID</th><th>Restaurante</th><th>Assunto</th><th>Status</th><th>Data Abertura</th><th>Opções</th></tr>';

if ($resultAbertos->num_rows > 0) {
    while ($row = $resultAbertos->fetch_assoc()) {
        $ticketsAbertos .= '<tr class="ticket-row" data-bs-toggle="collapse" data-bs-target="#accordion-' . $row['id'] . '" aria-expanded="false" aria-controls="accordion-' . $row['id'] . '">';
        $ticketsAbertos .= '<td>' . $row['id'] . '</td>';
        $ticketsAbertos .= '<td>' . $row['nome_restaurante'] . '</td>';
        $ticketsAbertos .= '<td>' . $row['assunto'] . '</td>';
        $ticketsAbertos .= '<td><span class="badge bg-warning">' . $row['status'] . '</span></td>';
        $ticketsAbertos .= '<td>' . $row['data_abertura'] . '</td>';
        $ticketsAbertos .= '<td><button class="btn btn-info responderTicketBtn" data-id="' . $row['id'] . '">Responder</button> <button class="btn btn-danger excluirTicketBtn" data-id="' . $row['id'] . '">Excluir</button></td>';
        $ticketsAbertos .= '</tr>';
        $ticketsAbertos .= '<tr class="accordion-row">';
        $ticketsAbertos .= '<td colspan="7">';
        $ticketsAbertos .= '<div id="accordion-' . $row['id'] . '" class="accordion-collapse collapse" aria-labelledby="heading-' . $row['id'] . '" data-bs-parent="#accordion-' . $row['id'] . '">';
        $ticketsAbertos .= '<div style="text-align: left;" class="accordion-body"><span class="badge bg-label-dark">Ticket: </span> ' . $row['mensagem'] . '</div>';
        $ticketsAbertos .= '</div>';
        $ticketsAbertos .= '</td>';
        $ticketsAbertos .= '</tr>';
    }
}

$ticketsAbertos .= '</table>';


// Consulta para obter os tickets respondidos
$sqlRespondidos = "SELECT t.*, r.nome_estabelecimento AS nome_restaurante FROM tickets t
                   INNER JOIN restaurantes r ON t.restaurante_id = r.id
                   WHERE t.status = 'Respondido'";

$resultRespondidos = $conn->query($sqlRespondidos);

$ticketsRespondidos = '<table id="respond" class="rounded-table">';
$ticketsRespondidos .= '<tr><th>ID</th><th>Restaurante</th><th>Assunto</th><th>Status</th><th>Data Abertura</th><th>Opções</th></tr>';

if ($resultRespondidos->num_rows > 0) {
    while ($row = $resultRespondidos->fetch_assoc()) {
        $ticketsRespondidos .= '<tr class="ticket-row" data-bs-toggle="collapse" data-bs-target="#accordion-' . $row['id'] . '" aria-expanded="false" aria-controls="accordion-' . $row['id'] . '">';
        $ticketsRespondidos .= '<td>' . $row['id'] . '</td>';
        $ticketsRespondidos .= '<td>' . $row['nome_restaurante'] . '</td>';
        $ticketsRespondidos .= '<td>' . $row['assunto'] . '</td>';
        $ticketsRespondidos .= '<td><span class="badge bg-info">' . $row['status'] . '</span></td>';
        $ticketsRespondidos .= '<td>' . $row['data_abertura'] . '</td>';
        $ticketsRespondidos .= '<td><button class="btn btn-danger excluirTicketBtn" data-id="' . $row['id'] . '">Excluir</button></td>';
        $ticketsRespondidos .= '</tr>';
        $ticketsRespondidos .= '<tr class="accordion-row">';
        $ticketsRespondidos .= '<td colspan="6">';
        $ticketsRespondidos .= '<div id="accordion-' . $row['id'] . '" class="accordion-collapse collapse" aria-labelledby="heading-' . $row['id'] . '" data-bs-parent="#accordion-' . $row['id'] . '">';
        $ticketsRespondidos .= '<div class="accordion-body"><span class="badge bg-label-dark">Ticket: </span> ' . $row['mensagem'] . '</div>';
        if ($row['resposta']) {
            $ticketsRespondidos .= '<div class="accordion-body"><span class="badge bg-label-secondary">Resposta: </span> ' . $row['resposta'] . '</div>';
        }
        $ticketsRespondidos .= '</div>';
        $ticketsRespondidos .= '</td>';
        $ticketsRespondidos .= '</tr>';
    }
}

$ticketsRespondidos .= '</table>';

// Fecha a conexão com o banco de dados
$conn->close();

// Retorna os tickets abertos e respondidos em formato JSON
echo json_encode(array('abertos' => $ticketsAbertos, 'respondidos' => $ticketsRespondidos));
?>
