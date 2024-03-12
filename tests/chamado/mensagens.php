<?php
// Incluir arquivo de configuração do banco de dados
require_once 'config.php';

// Verificar se foi fornecido um ID de chamado válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de chamado inválido.');
}

// Obter o ID do chamado a partir da URL
$chamadoId = $_GET['id'];

// Consultar as mensagens relacionadas a esse chamado
$query = "SELECT * FROM mensagens WHERE chamado_id = $chamadoId ORDER BY data_envio";
$result = mysqli_query($conn, $query);

// Verificar se ocorreu algum erro na consulta
if (!$result) {
    die('Erro ao consultar as mensagens: ' . mysqli_error($conn));
}

// Construir o HTML com as mensagens
$html = '';
while ($mensagem = mysqli_fetch_assoc($result)) {
    $html .= '<p><strong>' . $mensagem['remetente'] . '</strong>: ' . $mensagem['mensagem'] . '</p>';
}

// Retornar o HTML das mensagens
echo $html;
