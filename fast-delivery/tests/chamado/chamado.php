<?php
// Incluir arquivo de configuração do banco de dados
require_once 'config.php';

// Verificar se foi fornecido um ID de chamado válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de chamado inválido.');
}

// Obter o ID do chamado a partir da URL
$chamadoId = $_GET['id'];

// Consultar as informações do chamado
$query = "SELECT * FROM chamados WHERE id = $chamadoId";
$result = mysqli_query($conn, $query);

// Verificar se ocorreu algum erro na consulta
if (!$result) {
    die('Erro ao consultar o chamado: ' . mysqli_error($conn));
}

// Verificar se o chamado existe
if (mysqli_num_rows($result) === 0) {
    die('Chamado não encontrado.');
}

// Obter os detalhes do chamado
$chamado = mysqli_fetch_assoc($result);

// Consultar as mensagens relacionadas a esse chamado
$query = "SELECT * FROM mensagens WHERE chamado_id = $chamadoId ORDER BY data_envio";
$result = mysqli_query($conn, $query);

// Verificar se ocorreu algum erro na consulta
if (!$result) {
    die('Erro ao consultar as mensagens: ' . mysqli_error($conn));
}

// Obter o número de mensagens encontradas
$numMensagens = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalhes do Chamado</title>
    <!-- Incluir os estilos CSS -->
    <link rel="stylesheet" type="text/css" href="styles.css">
    <!-- Incluir o script JavaScript para atualizar as mensagens em tempo real -->
    <script src="script.js"></script>
</head>
<body>
    <h1>Detalhes do Chamado</h1>

    <h2><?php echo $chamado['assunto']; ?></h2>
    <p><strong>Status:</strong> <?php echo $chamado['status']; ?></p>
    <p><strong>Descrição:</strong> <?php echo $chamado['descricao']; ?></p>

    <hr>

    <h2>Bate-papo</h2>

    <div id="mensagens-container">
        <?php
        // Verificar se existem mensagens
        if ($numMensagens > 0) {
            // Exibir as mensagens
            while ($mensagem = mysqli_fetch_assoc($result)) {
                echo '<p><strong>' . $mensagem['remetente'] . '</strong>: ' . $mensagem['mensagem'] . '</p>';
            }
        } else {
            // Caso não haja mensagens
            echo '<p>Nenhuma mensagem encontrada.</p>';
        }
        ?>
    </div>

    <form id="mensagem-form">
        <input type="hidden" name="chamado_id" value="<?php echo $chamadoId; ?>">
        <input type="text" name="remetente" placeholder="Seu nome" required>
        <input type="text" name="mensagem" placeholder="Digite sua mensagem" required>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
