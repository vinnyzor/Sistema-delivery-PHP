<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "delivery";

$conexao = mysqli_connect($host, $usuario, $senha, $banco);
if (mysqli_connect_errno()) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

$diaSemana = $_POST['diaSemana'];
$horarioAbertura = $_POST['horarioAbertura'];
$horarioFechamento = $_POST['horarioFechamento'];

// Mapear os nomes dos dias para seus respectivos IDs
$diaSemanaIDs = array(
    "Domingo" => 7,
    "Segunda-feira" => 1,
    "Terca-feira" => 2,
    "Quarta-feira" => 3,
    "Quinta-feira" => 4,
    "Sexta-feira" => 5,
    "Sabado" => 6
);

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

    // Verificar se o registro já existe para o restaurante atual
    $queryVerificar = "SELECT * FROM horarios WHERE id_restaurante = '$restauranteId' AND dia_semana = '$diaSemana'";
    $resultadoVerificar = mysqli_query($conexao, $queryVerificar);

    if (mysqli_num_rows($resultadoVerificar) > 0) {
         // Obter o ID do horário existente
    $rowVerificar = mysqli_fetch_assoc($resultadoVerificar);
    $idSemana = $rowVerificar['id'];
        // Atualizar o registro existente
        $queryAtualizar = "UPDATE horarios
              SET horario_abertura = '$horarioAbertura', horario_fechamento = '$horarioFechamento'
              WHERE id_restaurante = '$restauranteId' AND id = '$idSemana'";


        $resultadoAtualizar = mysqli_query($conexao, $queryAtualizar);

        if ($resultadoAtualizar) {
            echo "Horário de funcionamento atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar o horário de funcionamento: " . mysqli_error($conexao);
        }
    } else {
        if (isset($diaSemanaIDs[$diaSemana])) {
            $idSemana = $diaSemanaIDs[$diaSemana];

            // Inserir novo registro
            $queryInserir = "INSERT INTO horarios (id, id_restaurante, dia_semana, horario_abertura, horario_fechamento)
                 VALUES ('$idSemana', '$restauranteId', '$diaSemana', '$horarioAbertura', '$horarioFechamento')";

            $resultadoInserir = mysqli_query($conexao, $queryInserir);

            if ($resultadoInserir) {
                echo "Novo horário de funcionamento inserido com sucesso!";
            } else {
                echo "Erro ao inserir o novo horário de funcionamento: " . mysqli_error($conexao);
            }
        } else {
            echo "Dia da semana inválido.";
        }
    }
} else {
    header("Location: login.php");
    exit();
}

mysqli_close($conexao);
?>