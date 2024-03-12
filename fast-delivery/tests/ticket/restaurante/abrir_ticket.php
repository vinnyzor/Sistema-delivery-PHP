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

    // Recupere os dados do formulário
    $assunto = $_POST['assunto'];
    $mensagem = $_POST['mensagem'];
    $status = 'Aguardando Resposta';

    // Inserir o novo ticket no banco de dados
    $sql = "INSERT INTO tickets (restaurante_id, assunto, mensagem, status) VALUES ('$restauranteId', '$assunto', '$mensagem', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo "Erro ao buscar o ID do restaurante";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
