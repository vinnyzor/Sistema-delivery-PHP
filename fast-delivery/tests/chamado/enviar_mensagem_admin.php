<?php
// Realizar a conexão com o banco de dados (utilizando PDO)
$pdo = new PDO('mysql:host=localhost;dbname=delivery', 'root', '');

// Recuperar a mensagem enviada pelo admin
$mensagem = $_POST['mensagem'];

// Definir o remetente como admin
$remetente = 'admin';

// Inserir a mensagem no banco de dados
$stmt = $pdo->prepare("INSERT INTO mensagens (chamado_id, remetente, msg_admin) VALUES (:chamadoId, :remetente, :mensagem)");
$stmt->bindParam(':chamadoId', $chamadoId); // ID do chamado ao qual a mensagem pertence
$stmt->bindParam(':remetente', $remetente);
$stmt->bindParam(':mensagem', $mensagem);
$stmt->execute();

// Retornar a mensagem enviada com o remetente
$response = array(
    'remetente' => $remetente,
    'mensagem' => $mensagem
);

header('Content-Type: application/json');
echo json_encode($response);
?>
