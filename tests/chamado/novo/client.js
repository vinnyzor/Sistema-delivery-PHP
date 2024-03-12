$(document).ready(function() {
    var chatId = 0; // ID da conversa atualmente selecionada

    // Função para atualizar as conversas
    function loadConversations() {
        $.ajax({
            url: 'get_conversations.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var conversations = response.conversations;
                var conversationList = $('#conversations');
                conversationList.empty();
                for (var i = 0; i < conversations.length; i++) {
                    var conversation = conversations[i];
                    var listItem = $('<div>');
                    listItem.text(conversation.id_de);
                    listItem.data('chatId', conversation.id);
                    listItem.click(function() {
                        chatId = $(this).data('chatId');
                        loadMessages();
                    });
                    conversationList.append(listItem);
                }
            }
        });
    }


function updateMessageTimeAgo() {
            $('.time-ago').each(function() {
                var datetime = $(this).data('datetime');
                var formattedTime = moment(datetime).fromNow();
                $(this).text(formattedTime);
            });
        }


        
    // Função para atualizar as mensagens
  function loadMessages() {
    $.ajax({
      url: 'get_messages.php',
      type: 'GET',
      data: { chatId: chatId },
      dataType: 'json',
      success: function(response) {
        console.log(response); // Verificar a resposta do servidor no console
        var messages = response.messages;
        var messageContainer = $('#messages');
        messageContainer.empty();
        for (var i = 0; i < messages.length; i++) {
          var message = messages[i];
          var messageItem = $('<div class="message">');
          var messageText = $('<p>');
          
          if (message.id_de_admin == 1) {
            // Mensagem do administrador à esquerda
            messageItem.addClass('admin-message');
            messageText.html('<span class="time-ago" data-datetime="' + message.data + '">' + message.data + '</span> (Admin) Fast Delivery: ' + message.mensagem);
          } else {
            // Mensagem do restaurante à direita
            messageItem.addClass('restaurant-message');
            messageText.html('<span class="time-ago" data-datetime="' + message.data + '">' + message.data + '</span> ('+ message.nome_estabelecimento + '): ' + message.mensagem);
          }
          
          messageItem.append(messageText);
          messageContainer.append(messageItem);
          updateMessageTimeAgo();
        }
      },
      error: function(xhr, status, error) {
        console.log(xhr.responseText); // Verificar o erro no console
      }
    });
  }
loadMessages();

    // Função para enviar uma mensagem
    $('#messageForm').submit(function(e) {
        e.preventDefault();
        var messageInput = $('#messageInput');
        var message = messageInput.val();
        if (message.trim() !== '') {
            $.ajax({
                url: 'send_message.php',
                type: 'POST',
                data: { chatId: chatId, message: message },
                success: function() {
                    messageInput.val('');
                    loadMessages();
                }
            });
        }
    });

    // Carregar as conversas iniciais
    loadConversations();
     // Carregar as mensagens do chat inicialmente
  loadMessages();

  // Atualizar as mensagens a cada 2 segundos
  setInterval(loadMessages, 1000);
});
