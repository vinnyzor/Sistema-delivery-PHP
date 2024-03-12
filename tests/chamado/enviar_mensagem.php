<?php
session_start();

// Configurações do banco de dados
$host = "localhost";
$dbname = "delivery";
$username = "root";
$password = "";

try {
  // Conexão com o banco de dados
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

  // Verifique se o restaurante está autenticado
  if (!isset($_SESSION['email'])) {
    die('Restaurante não autenticado');
  }

  // Obtém o ID do restaurante logado
  $email = $_SESSION['email'];
  $query = "SELECT id FROM restaurantes WHERE email = :email";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $restaurante = $stmt->fetch(PDO::FETCH_ASSOC);

  $restauranteId = $restaurante['id'];

  // Obtém a mensagem enviada pelo restaurante
  $mensagem = $_POST['mensagem'];

  // Insere a mensagem na tabela de mensagens
  $query = "INSERT INTO mensagens (id_de, id_chat, mensagem) VALUES (:restauranteId, 1, :mensagem)";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':restauranteId', $restauranteId);
  $stmt->bindParam(':mensagem', $mensagem);
  $stmt->execute();

  // Retorna uma resposta de sucesso
  echo "Mensagem enviada com sucesso!";
} catch (PDOException $e) {
  die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
