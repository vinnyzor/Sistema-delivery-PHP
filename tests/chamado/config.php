<?php
// Configurações do banco de dados
$servername = "localhost";  // Nome do servidor
$username = "root";     // Nome de usuário do banco de dados
$password = "";        // Senha do banco de dados
$dbname = "delivery";  // Nome do banco de dados

// Estabelecer a conexão com o banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar se ocorreu algum erro na conexão
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}
