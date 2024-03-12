<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
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

// Recupere o ID do ticket a ser excluído
$idTicket = $_POST['idTicket'];

// Excluir o ticket do banco de dados
$sql = "DELETE FROM tickets WHERE id = $idTicket";

if ($conn->query($sql) === TRUE) {
    echo 'success';
} else {
    echo 'error';
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
