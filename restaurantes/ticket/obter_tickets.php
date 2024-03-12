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

    // Construa a tabela HTML com os tickets e o HTML do accordion com as mensagens
    $table = '<div class="w-100">';
    $table .= '<table style="cursor: pointer; caret-color: transparent;" class="table">';
    $table .= '<tr><th style="caret-color: transparent;">N° ticket</th><th style="caret-color: transparent;">Assunto</th><th style="caret-color: transparent;">Status</th><th style="caret-color: transparent;">Prioridade</th><th style="caret-color: transparent;">Data Abertura</th><th style="caret-color: transparent;">Data Resposta</th></tr>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $table .= '<tr style="caret-color: transparent;" class="ticket-row" data-bs-toggle="collapse" data-bs-target="#accordion-' . $row['id'] . '" aria-expanded="false" aria-controls="accordion-' . $row['id'] . '">';
            $table .= '<td>' . $row['id'] . '</td>';
            $table .= '<td>' . $row['assunto'] . '</td>';
            if ($row['status'] == 'Aguardando Resposta') {
                $table .= '<td><span class="badge bg-warning">' . $row['status'] . '</span></td>';
            } elseif ($row['status'] == 'Respondido') {
                $table .= '<td><span class="badge bg-info">' . $row['status'] . '</span></td>';
            } else {
                $table .= '<td>' . $row['status'] . '</td>';
            }

            if ($row['prioridade'] == 'Alta') {
                $table .= '<td><span class="badge rounded-pill bg-label-danger">' . $row['prioridade'] . '</span></td>';
            } elseif ($row['prioridade'] == 'Média') {
                $table .= '<td><span class="badge rounded-pill bg-label-warning">' . $row['prioridade'] . '</span></td>';
            } elseif ($row['prioridade'] == 'Baixa') {
                $table .= '<td><span class="badge rounded-pill bg-label-info">' . $row['prioridade'] . '</span></td>';
            } else {
                $table .= '<td>' . $row['prioridade'] . '</td>';
            }
            $table .= '<td>' . date('d/m/Y - H:i:s', strtotime($row['data_abertura'])) . '</td>';
            if ($row['data_resposta'] !== null) {
                $table .= '<td>' . date('d/m/Y - H:i:s', strtotime($row['data_resposta'])) . '</td>';
            } else {
                $table .= '<td>Nenhuma resposta</td>';
            }

            $table .= '</tr>';
            $table .= '<tr class="accordion-row">';
            $table .= '<td colspan="12">';
            $table .= '<div id="accordion-' . $row['id'] . '" class="accordion-collapse collapse" aria-labelledby="heading-' . $row['id'] . '" data-bs-parent="#accordionExample">';
            $table .= '<div style="background-color:#f5f5f9" class="accordion-body"><span class="badge bg-label-dark">Ticket:</span> ' . $row['mensagem'] . '</div>';
            // Verificar se o ticket está respondido
            if ($row['status'] == 'Respondido' && $row['resposta'] != '') {
                $table .= '<div style="background-color:#f5f5f9" class="accordion-body"><span class="badge bg-label-secondary">Resposta:</span> ' . $row['resposta'] . '</div>';
            }
            $table .= '</div>';
            $table .= '</td>';
            $table .= '</tr>';
        }
    } else {
        $table .= '<tr><td class="text-center" colspan="6">Nenhum ticket encontrado.</td></tr>';
    }

    $table .= '</table>';
    $table .= '</div>';

    echo $table;
} else {
    echo "Erro ao buscar o ID do restaurante";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
