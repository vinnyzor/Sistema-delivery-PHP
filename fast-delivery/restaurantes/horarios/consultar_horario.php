<?php
// Supondo que você tenha uma tabela chamada 'horarios_funcionamento' com as colunas 'dia_semana', 'horario_abertura' e 'horario_fechamento'
// e esteja usando MySQLi para se conectar ao banco de dados

// Realize a conexão com o banco de dados
$host = "localhost"; // Endereço do servidor do banco de dados
$usuario = "root"; // Usuário do banco de dados
$senha = ""; // Senha do banco de dados
$banco = "delivery"; // Nome do banco de dados
$conexao = new mysqli($host, $usuario, $senha, $banco);

// Verifique se a conexão foi estabelecida com sucesso
if ($conexao->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Buscar o ID do restaurante logado
$queryRestaurante = "SELECT id FROM restaurantes WHERE email = '$email'";
$resultadoRestaurante = $conexao->query($queryRestaurante);

if ($resultadoRestaurante && $resultadoRestaurante->num_rows === 1) {
    $rowRestaurante = $resultadoRestaurante->fetch_assoc();
    $restauranteId = $rowRestaurante['id'];

    // Obtenha o dia da semana enviado via POST
    $diaSemana = $_POST['diaSemana'];

    // Consulte o banco de dados para obter os horários configurados para o dia da semana selecionado e restaurante logado
    $query = "SELECT horario_abertura, horario_fechamento FROM horarios_funcionamento WHERE id_restaurante = '$restauranteId' AND dia_semana = '$diaSemana'";
    $resultado = $conexao->query($query);

    if ($resultado) {
        if ($resultado->num_rows > 0) {
            $horarios = $resultado->fetch_assoc();
            echo json_encode($horarios);
        } else {
            echo json_encode(null);
        }
    } else {
        echo "Erro na consulta dos horários de funcionamento: " . $conexao->error;
    }
} else {
    header("Location: login.php");
    exit();
}

// Feche a conexão com o banco de dados
$conexao->close();
?>