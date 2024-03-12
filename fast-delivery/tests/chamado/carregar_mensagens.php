<?php
// Realizar a conexão com o banco de dados (utilizando PDO)
$pdo = new PDO('mysql:host=localhost;dbname=delivery', 'root', '');

// Consultar as mensagens relacionadas a um chamado (cliente e admin)
$stmt = $pdo->prepare("SELECT remetente, mensagem FROM mensagens WHERE chamado_id = :chamadoId ORDER BY data_envio ASC");
$stmt->bindParam(':chamadoId', $_GET['chamado_id']);
$stmt->execute();

// Armazenar as mensagens em um array
$mensagens = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $mensagem = array(
        'remetente' => $row['remetente'],
        'mensagem' => $row['mensagem']
    );
    $mensagens[] = $mensagem;
}

// Retornar as mensagens em formato JSON
header('Content-Type: application/json');
echo json_encode($mensagens);
?>
