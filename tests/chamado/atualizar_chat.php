<?php
// Arquivo: atualizar_chat.php

// Conecte-se ao banco de dados (mesmo código de conexão do chat.php)
$host = 'localhost';
$db = 'delivery';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Obtenha o ID do restaurante logado com base no email da sessão (mesmo código do chat.php)
$email = $_SESSION['email'];
$sql = "SELECT id FROM restaurantes WHERE email = '$email'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $restauranteID = $row['id'];
} else {
    // Redirecione para a página de login, se necessário (mesmo código do chat.php)
    header("Location: http://localhost/fast-delivery/html/login.php");
    exit();
}

// Obtenha as mensagens do chat entre o restaurante e o administrador (mesmo código do chat.php)
$sql = "SELECT m.*, r.nome AS remetente_nome, r.foto_perfil AS remetente_foto
        FROM mensagens m
        INNER JOIN restaurantes r ON m.id_de = r.id
        WHERE (m.id_de = $restauranteID AND m.id_para = 1) OR (m.id_de = 1 AND m.id_para = $restauranteID)
        ORDER BY m.data ASC";
$result = $conn->query($sql);
$mensagens = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mensagens[] = $row;
    }
}

// Retorne as mensagens em formato JSON
echo json_encode($mensagens);
?>
