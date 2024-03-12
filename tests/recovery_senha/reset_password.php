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

// Obter o token da URL
$token = $_GET['token'];

// Verificar se o token existe no banco de dados
$query = "SELECT * FROM restaurantes WHERE token = '$token'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // Token válido, exibir formulário para redefinir asenha:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Redefinir Senha</title>
</head>
<body>
    <h2>Redefinir Senha</h2>
    <form action="process_reset.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label>Nova Senha:</label>
        <input type="password" name="nova_senha" required>
        <br>
        <input type="submit" value="Redefinir Senha">
    </form>
</body>
</html>
