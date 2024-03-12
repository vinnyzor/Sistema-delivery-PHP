<?php
// Configurações do banco de dados
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'delivery';

session_start();
$senderEmail = $_SESSION['email'];

// Conexão com o banco de dados
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Busca do restaurante_id com base no email do remetente
$sql = "SELECT id FROM restaurantes WHERE email = '$senderEmail'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $senderRestauranteId = $row['id'];
} else {
    // Caso o email do remetente não seja encontrado na tabela de usuários
    echo "Erro: remetente não encontrado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver = $_POST['receiver'];
    $message = $_POST['message'];

    if (!empty($receiver) && !empty($message)) {
        $timestamp = date('Y-m-d H:i:s');

        // Insere a nova mensagem no banco de dados, usando o ID do restaurante como remetente
        $sql = "INSERT INTO messages (sender, receiver, message, timestamp) VALUES ('$senderRestauranteId', 'admin', '$message', '$timestamp')";
        if ($conn->query($sql) === false) {
            echo "Erro ao enviar a mensagem: " . $conn->error;
        }
    }
}

$receiver = $_GET['receiver'];

// Obtém as mensagens da conversa entre os usuários
$sql = "SELECT * FROM messages WHERE (sender = '$senderRestauranteId' AND receiver = '$receiver') OR (sender = '$receiver' AND receiver = '$senderRestauranteId') ORDER BY timestamp ASC";
$result = $conn->query($sql);

$chatLog = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chatLog .= $row['timestamp'] . ' - ' . $row['sender'] . ': ' . $row['message'] . '<br>';
    }
}

$conn->close();

// Retorna as mensagens como resposta
echo $chatLog;
?>