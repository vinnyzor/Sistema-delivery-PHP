<?php
$servername = "177.234.152.18";
$username = "eyeingde_admin_plataforma";
$password = "Vinicius1234#";
$dbname = "eyeingde_ebamais";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

$diaSemana = $_POST['diaSemana'];

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Buscar o ID do usuário logado
$sqlUsuario = "SELECT id FROM usuarios WHERE email = '$email'";
$resultadoUsuario = $conn->query($sqlUsuario);

if ($resultadoUsuario->num_rows > 0) {
    $rowUsuario = $resultadoUsuario->fetch_assoc();
    $usuarioId = $rowUsuario['id'];

    // Verificar se o registro existe para o usuário atual
    $sqlVerificar = "SELECT * FROM horarios WHERE dia_semana = '$diaSemana' AND usuario_id = '$usuarioId'";
    $resultadoVerificar = $conn->query($sqlVerificar);

    if ($resultadoVerificar->num_rows > 0) {
        // Excluir o registro do dia
        $sqlExcluir = "DELETE FROM horarios WHERE dia_semana = '$diaSemana' AND usuario_id = '$usuarioId'";
        $resultadoExcluir = $conn->query($sqlExcluir);

        if ($resultadoExcluir) {
            // Exclusão bem-sucedida
            echo 'sucesso';
        } else {
            // Erro ao excluir o registro
            echo 'erro';
        }
    } else {
        // Registro não encontrado
        echo 'registro_nao_encontrado';
    }
} else {
    header("Location: login.php");
    exit();
}

$conn->close();
?>
