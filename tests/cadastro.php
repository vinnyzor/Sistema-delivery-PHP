<?php
session_start();
if (isset($_SESSION['email'])) {
  header("Location: dashboard.php");
  exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome_estabelecimento = $_POST['nome_estabelecimento'];
  $cnpj = $_POST['cnpj'];
  $endereco = $_POST['endereco'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  $telefone = $_POST['telefone'];

  $sql = "INSERT INTO restaurantes (nome_estabelecimento, cnpj, endereco, email, senha, telefone) VALUES ('$nome_estabelecimento', '$cnpj', '$endereco', '$email', '$senha', '$telefone')";

  if ($conn->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso! Aguarde a aprovação do cadastro.";
  } else {
    echo "Erro ao cadastrar o restaurante: " . $conn->error;
  }
}
?>

<h1>Cadastro de Restaurante</h1>

<form action="cadastro.php" method="POST">
  <label for="nome_estabelecimento">Nome do Estabelecimento:</label>
  <input type="text" name="nome_estabelecimento" required><br>

  <label for="cnpj">CNPJ:</label>
  <input type="text" name="cnpj" required><br>

  <label for="endereco">Endereço:</label>
  <input type="text" name="endereco" required><br>

  <label for="email">E-mail:</label>
  <input type="email" name="email" required><br>

  <label for="senha">Senha:</label>
  <input type="password" name="senha" required><br>

  <label for="telefone">Telefone:</label>
  <input type="text" name="telefone" required><br>

  <input type="submit" value="Cadastrar">
</form>
