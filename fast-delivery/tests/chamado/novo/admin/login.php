<?php
session_start();
require_once 'db_connection.php';

// Verificar se o admin já está logado
if (isset($_SESSION['email'])) {
  header("Location: chats.php");
  exit();
}

// Processar o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  // Consultar o banco de dados para verificar as credenciais do admin
  $query = "SELECT * FROM admins WHERE email = '$email' AND senha = '$senha'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) === 1) {
    // Login bem-sucedido, definir a sessão e redirecionar para a página principal
    $_SESSION['email'] = $email;
    header("Location: index.php");
    exit();
  } else {
    // Credenciais inválidas, exibir uma mensagem de erro
    $error = "Email ou senha incorretos";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>
  <?php if (isset($error)): ?>
    <p><?php echo $error; ?></p>
  <?php endif; ?>
  <form method="POST" action="">
    <label for="email">Email:</label>
    <input type="email" name="email" required><br>
    <label for="senha">Senha:</label>
    <input type="password" name="senha" required><br>
    <input type="submit" value="Entrar">
  </form>
</body>
</html>
