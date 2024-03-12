<?php
// Configurações do banco de dados
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'delivery';

session_start();
$adminEmail = 'vinny@gmail.com'; // Email do admin (pode ser alterado conforme necessário)

// Conexão com o banco de dados
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o admin está logado
if (!isset($_SESSION['email']) || $_SESSION['email'] !== $adminEmail) {
    echo "Acesso não autorizado.";
    exit();
}

// Busca do admin_id com base no email do admin
$sql = "SELECT id FROM restaurantes WHERE email = '$adminEmail'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $adminId = $row['id'];
} else {
    echo "Erro: admin não encontrado.";
    exit();
}

// Busca os restaurantes para exibir na tela de seleção
$sql = "SELECT id, nome_estabelecimento FROM restaurantes";
$restaurantesResult = $conn->query($sql);

$restaurantes = array();
if ($restaurantesResult->num_rows > 0) {
    while ($row = $restaurantesResult->fetch_assoc()) {
        $restaurantes[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender'];
    $message = $_POST['message'];

    if (!empty($sender) && !empty($message)) {
        $timestamp = date('Y-m-d H:i:s');

        // Insere a nova mensagem no banco de dados, usando o ID do admin como remetente
        $sql = "INSERT INTO messages (sender, receiver, message, timestamp) VALUES ('$adminId', '$sender', '$message', '$timestamp')";
        if ($conn->query($sql) === false) {
            echo "Erro ao enviar a mensagem: " . $conn->error;
        }
    }
}

// Obtém a lista de chats com os restaurantes
$sql = "SELECT DISTINCT sender FROM messages WHERE receiver = '$adminId'";
$chatsResult = $conn->query($sql);

$chats = array();
if ($chatsResult->num_rows > 0) {
    while ($row = $chatsResult->fetch_assoc()) {
        $senderId = $row['sender'];
        $senderName = '';

        // Obtem o nome do restaurante com base no ID
        $sql = "SELECT nome_estabelecimento FROM restaurantes WHERE id = '$senderId'";
        $nameResult = $conn->query($sql);

        if ($nameResult->num_rows > 0) {
            $nameRow = $nameResult->fetch_assoc();
            $senderName = $nameRow['nome_estabelecimento'];
        }

        $chats[] = array(
            'senderId' => $senderId,
            'senderName' => $senderName
        );
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
  <title>Chat com o Admin</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<style type="text/css">
    #restaurantes-list {
        margin-bottom: 20px;
    }

    #restaurantes-list label {
        margin-right: 10px;
    }

    #chatbox {
        height: 200px;
        overflow-y: scroll;
        border: 1px solid #ccc;
        padding: 10px;
    }

    input[type="text"],
    button {
        margin-top: 10px;
    }
</style>

<div id="restaurantes-list">
    <label for="restaurante-select">Selecione um restaurante:</label>
    <select id="restaurante-select">
        <option value="">Todos</option>
        <?php foreach ($restaurantes as $restaurante) { ?>
            <option value="<?php echo $restaurante['id']; ?>"><?php echo $restaurante['nome_estabelecimento']; ?></option>
        <?php } ?>
    </select>
</div>


<div id="chatbox"></div>

<input type="text" id="message" placeholder="Digite sua mensagem...">
<button id="send">Enviar</button>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="script.js"></script>

<script>
    $(document).ready(function () {
        // Função para atualizar o chat a cada 1 segundo
        setInterval(updateChat, 1000);

        // Enviar mensagem ao clicar no botão "Enviar"
        $('#send').click(function () {
            var sender = $('#restaurante-select').val();
            var message = $('#message').val();

            if (message !== '') {
                $.post('admin_chat.php', {sender: sender, message: message}, function () {
                    // Limpar o campo de entrada de mensagem após enviar
                    $('#message').val('');
                });
            }
        });

        // Atualizar chat ao selecionar um restaurante
        $('#restaurante-select').change(function () {
            updateChat();
        });

        function updateChat() {
            var sender = $('#restaurante-select').val();

            $.get('admin_chat.php', {sender: sender}, function (data) {
                // Atualizar o chat com as mensagens recebidas
                $('#chatbox').html(data);
            });
        }
    });
</script>
</body>
</html>