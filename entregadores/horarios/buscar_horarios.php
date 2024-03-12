<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "delivery";

$conexao = mysqli_connect($host, $usuario, $senha, $banco);
if (mysqli_connect_errno()) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

$diaSemana = $_GET['diaSemana'];

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Buscar o ID do restaurante logado
$queryRestaurante = "SELECT id FROM restaurantes WHERE email = '$email'";
$resultadoRestaurante = mysqli_query($conexao, $queryRestaurante);

if ($resultadoRestaurante && mysqli_num_rows($resultadoRestaurante) === 1) {
    $rowRestaurante = mysqli_fetch_assoc($resultadoRestaurante);
    $restauranteId = $rowRestaurante['id'];

    // Verificar se o registro existe para o restaurante atual
    $queryVerificar = "SELECT * FROM horarios WHERE id_restaurante = '$restauranteId' AND dia_semana = '$diaSemana'";
    $resultadoVerificar = mysqli_query($conexao, $queryVerificar);

    $horarios = array();

    if (mysqli_num_rows($resultadoVerificar) > 0) {
        // O registro existe, obter os horários
        $row = mysqli_fetch_assoc($resultadoVerificar);
        $horarios[] = $row;
    } else {
        // O registro não existe, adicionar o dia da semana ao array
        $horarios[] = array('dia_semana' => $diaSemana, 'horario_abertura' => '', 'horario_fechamento' => '');
    }

    // Enviar os horários como resposta no formato JSON
    header('Content-Type: application/json');
    echo json_encode($horarios);
} else {
    header("Location: login.php");
    exit();
}

mysqli_close($conexao);
?>
