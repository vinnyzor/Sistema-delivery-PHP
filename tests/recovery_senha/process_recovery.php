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

// Obter o e-mail fornecido pelo usuário
$email = $_POST['email'];

// Verificar se o e-mail existe no banco de dados
$query = "SELECT * FROM restaurantes WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // Gerar um token único
    $token = uniqid();

    // Inserir o token no banco de dados
    $query = "UPDATE restaurantes SET token = '$token' WHERE email = '$email'";
    mysqli_query($conn, $query);

    // Enviar o e-mail de recuperação de senha
    $to = $email;
    $subject = "Recuperação de Senha";
    $message = "Para redefinir sua senha, clique no link a seguir:\n\n";
    $message .= "http://seusite.com/redefinir_senha.php?token=$token";
    $headers = "From: viniciusmendesdesousa@gmail.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "Um e-mail de recuperação de senha foi enviado para o endereço fornecido.";
    } else {
        echo "Ocorreu um erro ao enviar o e-mail de recuperação de senha.";
    }
} else {
    echo "O endereço de e-mail fornecido não foi encontrado.";
}

// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>
