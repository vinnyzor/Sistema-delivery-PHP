<?php
// Conectar ao banco de dados
$host = "localhost";
$user = "root";
$senha_db = "";
$database = "delivery";

$conn = mysqli_connect($host, $user, $senha_db, $database);

if (!$conn) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

// Obter o token e a nova senha fornecidos pelo usuário
$token = $_POST['token'];
$novaSenha = $_POST['nova_senha'];

// Verificar se o token existe no banco de dados
$query = "SELECT * FROM restaurantes WHERE token = '$token'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // Token válido, atualizar a senha do usuário
    $row = mysqli_fetch_assoc($result);
    $id = $row['id'];

    $query = "UPDATE restaurantes SET senha = '$novaSenha', token = NULL WHERE id = $id";
    mysqli_query($conn, $query);

    echo "Senha redefinida com sucesso.";
} else {
    echo "Token inválido.";
}

// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>