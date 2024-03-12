$(document).ready(function() {
  // Função para atualizar o chat a cada 1 segundo
  setInterval(updateChat, 1000);

  // Enviar mensagem ao clicar no botão "Enviar"
  $('#send').click(function() {
    var sender = 'admin'; // Define o remetente como admin
    var message = $('#message').val();

    if (message !== '') {
      $.post('admin_chat.php', { sender: sender, message: message }, function() {
        // Limpar o campo de entrada de mensagem após enviar
        $('#message').val('');
      });
    }
  });

  function updateChat() {
    var sender = 'admin'; // Define o remetente como admin

    $.get('admin.php', { sender: sender }, function(data) {
      // Atualizar o chat com as mensagens recebidas
      $('#chatbox').html(data);
    });
  }
});