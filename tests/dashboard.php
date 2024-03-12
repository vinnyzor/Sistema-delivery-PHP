<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}
?>

<h1>Painel do Restaurante</h1>
<p>Bem-vindo, <?php echo $_SESSION['email']; ?></p>

<a href="logout.php">Sair</a>
