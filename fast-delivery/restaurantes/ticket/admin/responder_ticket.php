<?php
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

// Receber os dados do formulário
$idTicket = $_POST['idTicket'];
$resposta = $_POST['resposta'];

// Atualizar o ticket no banco de dados com a resposta e status "Respondido"
$status = 'Respondido';
$dataResposta = date("Y-m-d H:i:s"); // Obter a data e hora atual
atualizarTicket($conn, $idTicket, $resposta, $status, $dataResposta);

// Função para atualizar o ticket no banco de dados
function atualizarTicket($conn, $idTicket, $resposta, $status, $dataResposta) {
    // Utilize prepared statements para prevenir injeção de SQL
    $sql = "UPDATE tickets SET resposta = ?, status = ?, data_resposta = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $resposta, $status, $dataResposta, $idTicket);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}
?>
