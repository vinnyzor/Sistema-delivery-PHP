<?php
session_start();
if (isset($_SESSION['email'])) {
  header("Location: dashboard.php");
  exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = $_POST['usuario'];
  $senha = $_POST['senha'];

  $sql = "SELECT * FROM restaurantes WHERE (cnpj='$usuario' OR email='$usuario') AND senha='$senha' AND status='aprovado'";
  $result = $conn->query($sql);

  if ($result->num_rows === 1) {
    $_SESSION['email'] = $usuario;
    header("Location: dashboard.php");
    exit();
  } else {
    echo "Login inválido. Verifique suas credenciais ou aguarde a aprovação do cadastro.";
  }
}
?>

<h1>Login</h1>

<form action="login.php" method="POST">
  <label for="usuario">CNPJ ou E-mail:</label>
  <input type="text" name="usuario" required><br>

  <label for="senha">Senha:</label>
  <input type="password" name="senha" required><br>

  <input type="submit" value="Entrar">
</form>
