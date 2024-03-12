$(document).ready(function() {
    var chatId = 0; // ID da conversa atualmente selecionada





// Exemplo de uso: chame a função 'updateAndScrollToBottom()' sempre que houver uma atualização de conteúdo no contêiner de mensagens


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
       
        var messages = response.messages;
        var messageContainer = $('#messages');
        messageContainer.empty();
        for (var i = 0; i < messages.length; i++) {
          var message = messages[i];
          var messageItem = $('<div class="message">');
          var messageText = $('<p>');
          
          if (message.id_de_admin == 1) {
            // Mensagem do administrador à esquerda
            messageItem.addClass('alert alert-primary');
            messageText.html('(Suporte) Fast Delivery: ' + message.mensagem + '<br><span class="time-ago card-header text-light medium fw-semibold" data-datetime="' + message.data + '">' + message.data + '</span>');
          } else {
            // Mensagem do restaurante à direita
            messageItem.addClass('alert alert-secondary');
            messageText.html('('+ message.nome_estabelecimento + '): ' + message.mensagem + '<br><span class="time-ago card-header text-light medium fw-semibold" data-datetime="' + message.data + '">' + message.data + '</span>');
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


  // Obtém o elemento do contêiner de mensagens
  var messagesContainer = document.getElementById('vertical-example');

  // Função para rolar o contêiner de mensagens para a parte inferior
  function scrollMessagesContainerToBottom() {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
  }

  // Chamada inicial para rolar para a parte inferior
  scrollMessagesContainerToBottom();

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
                    setTimeout(scrollMessagesContainerToBottom, 100);
                }
            });
        }
    });

    // Carregar as conversas iniciais
    loadConversations();
     // Carregar as mensagens do chat inicialmente
  loadMessages();

  // Atualizar as mensagens a cada 2 segundos
  setInterval(loadMessages, 2000);




// Exemplo de uso: chame a função 'scrollMessagesContainerToBottom()' sempre que houver uma atualização de conteúdo no contêiner de mensagens


function buscarUltimoLogin() {
    // Faça uma requisição AJAX para buscar a data do último login
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Obtém a resposta da requisição
            var lastLogin = this.responseText;
            
            // Obtém a data atual
            var dataAtual = new Date();

            // Calcula a diferença em segundos entre as datas
            var diferenca = (dataAtual - new Date(lastLogin)) / 1000;

            // Obtém o elemento com o ID "status"
            var statusElement = document.getElementById("status");

            // Verifica se a diferença é maior que 10 segundos
            if (diferenca > 10) {
                // Define o texto como "Offline"
                 statusElement.className = "avatar avatar-offline";
            } else {
                // Define o texto como "Online"
                 statusElement.className = "avatar avatar-online";
            }

            // Aguarda 5 segundos
            setTimeout(buscarUltimoLogin, 5000);
        }
    };
    xhttp.open("GET", "buscar_ultimo_login.php", true);
    xhttp.send();
}

// Chama a função pela primeira vez
buscarUltimoLogin();






});


window.addEventListener('load', function() {
  var messagesContainer = document.getElementById('vertical-example');
  messagesContainer.scrollTop = messagesContainer.scrollHeight;
});
