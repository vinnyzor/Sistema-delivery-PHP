<?php
// Incluir arquivo de configuração do banco de dados
require_once 'config.php';

// Verificar se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $chamadoId = $_POST['chamado_id'];
    $remetente = $_POST['remetente'];
    $mensagem = $_POST['mensagem'];

    // Inserir a nova mensagem no banco de dados
    $query = "INSERT INTO mensagens (chamado_id, remetente, mensagem) VALUES ('$chamadoId', '$remetente', '$mensagem')";
    $result = mysqli_query($conn, $query);

    // Verificar se ocorreu algum erro na inserção
    if (!$result) {
        die('Erro ao inserir a mensagem: ' . mysqli_error($conn));
    }

    // A mensagem foi enviada com sucesso
    echo 'Mensagem enviada.';
} else {
    // Caso a requisição não seja do tipo POST
    echo 'Método inválido.';
}
