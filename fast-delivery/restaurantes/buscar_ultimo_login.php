<?php

// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";
date_default_timezone_set('America/Sao_Paulo');
// Cria uma conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão foi estabelecida corretamente
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Executa a consulta SQL para obter a data do último login
$sql = "SELECT last_login FROM admins";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Obtém o resultado da consulta
    $row = $result->fetch_assoc();
    $lastLogin = $row["last_login"];

    // Retorna a data do último login como resposta
    echo $lastLogin;
}

// Fecha a conexão com o banco de dados
$conn->close();

?>
