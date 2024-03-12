<?php
// Configurações do banco de dados
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'delivery';

// Conexão com o banco de dados
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sender = $_GET['sender'];

    $whereClause = '';
    if (!empty($sender)) {
        $whereClause = "WHERE sender = '$sender'";
    }

    // Obtém as mensagens do banco de dados com base no remetente (restaurante) selecionado
    $sql = "SELECT * FROM messages $whereClause ORDER BY timestamp ASC";
    $messagesResult = $conn->query($sql);

    if ($messagesResult->num_rows > 0) {
        while ($row = $messagesResult->fetch_assoc()) {
            $messageSender = $row['sender'];
            $messageContent = $row['message'];

            // Exibe as mensagens no formato desejado (por exemplo, usando <div>, <p>, etc.)
            echo "<div><strong>Remetente:</strong> $messageSender</div>";
            echo "<div><strong>Mensagem:</strong> $messageContent</div>";
            echo "<hr>";
        }
    } else {
        echo "<div>Nenhuma mensagem encontrada.</div>";
    }
}

$conn->close();
?>
