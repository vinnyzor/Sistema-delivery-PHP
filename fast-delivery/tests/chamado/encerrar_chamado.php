<?php
// Incluir arquivo de configuração do banco de dados
require_once 'config.php';

// Verificar se foi fornecido um ID de chamado válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de chamado inválido.');
}

// Obter o ID do chamado a partir da URL
$chamadoId = $_GET['id'];

// Atualizar o status do chamado para "Encerrado"
$query = "UPDATE chamados SET status = 'Encerrado' WHERE id = $chamadoId";
$result = mysqli_query($conn, $query);

// Verificar se ocorreu algum erro na atualização
if (!$result) {
    die('Erro ao encerrar o chamado: ' . mysqli_error($conn));
}

// Redirecionar de volta para a página index.php
header('Location: index.php');
exit();
