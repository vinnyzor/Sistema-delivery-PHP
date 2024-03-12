<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve algum erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

session_start();
if (!isset($_SESSION['email'])) {
    die("Usuário não logado.");
}

$email = $_SESSION['email'];

// Consulta SQL para obter os horários do restaurante logado
$sql = "SELECT * FROM horarios 
        INNER JOIN restaurantes ON horarios.id_restaurante = restaurantes.id 
        WHERE restaurantes.email = '$email'";

$result = $conn->query($sql);

// Verifica se há registros retornados
if ($result->num_rows > 0) {
    // Loop pelos registros e cria um array
    $horarios = array();
    while ($row = $result->fetch_assoc()) {
        $horarios[] = $row;
    }

    // Retorna o array como JSON
    echo json_encode($horarios);
} else {
    echo "Nenhum horário encontrado.";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
