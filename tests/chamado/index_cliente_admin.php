<!DOCTYPE html>
<html>
<head>
    <title>Chat - Admin</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Carregar as mensagens existentes no início
            carregarMensagensAdmin();

            // Enviar mensagem do admin
            $('#chat-form-admin').submit(function(event) {
                event.preventDefault(); // Impedir o envio tradicional do formulário

                var mensagem = $('#mensagem-admin').val();

                $.ajax({
                    url: 'enviar_mensagem_admin.php',
                    type: 'POST',
                    data: {
                        mensagem: mensagem
                    },
                    success: function(response) {
                        // Limpar o campo de mensagem do admin
                        $('#mensagem-admin').val('');

                        // Atualizar o chat exibindo a nova mensagem do admin
                        mostrarMensagem(response.remetente, response.mensagem);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro ao enviar mensagem do admin: ' + error);
                    }
                });
            });

            // Função para carregar as mensagens enviadas pelo admin
            function carregarMensagensAdmin() {
                $.ajax({
                    url: 'carregar_mensagens_admin.php',
                    type: 'GET',
                    success: function(response) {
                        // Exibir as mensagens do admin no chat
                        response.forEach(function(mensagem) {
                            mostrarMensagem(mensagem.remetente, mensagem.mensagem);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro ao carregar mensagens do admin: ' + error);
                    }
                });
            }

            // Função para exibir a mensagem no chat
            function mostrarMensagem(remetente, mensagem) {
                var chatContainer = $('#chat-container');

                // Verificar se a mensagem é do admin ou do cliente
                var mensagemHtml = '';
                if (remetente === 'admin') {
                    mensagemHtml = '<div class="admin-msg">' + mensagem + '</div>';
                } else {
                    mensagemHtml = '<div class="cliente-msg">' + mensagem + '</div>';
                }

                chatContainer.append(mensagemHtml);

                // Rolar a visualização do chat para a última mensagem
                chatContainer.scrollTop(chatContainer[0].scrollHeight);
            }
        });
    </script>
    <style>
        #chat-container {
            height: 300px;
            overflow-y: scroll;
        }
        .admin-msg {
            text-align: right;
            background-color: #efefef;
        }
        .cliente-msg {
            text-align: left;
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <h1>Chat - Admin</h1>

    <div id="chat-container"></div>

    <form id="chat-form-admin" action="enviar_mensagem_admin.php" method="POST">
        <label for="mensagem-admin">Mensagem:</label><br>
        <textarea id="mensagem-admin" rows="4" cols="50" required></textarea><br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>
