$(document).ready(function() {
  // Função para atualizar o chat a cada 1 segundo
  setInterval(updateChat, 1000);

  // Enviar mensagem ao clicar no botão "Enviar"
  $('#send').click(function() {
    var receiver = 'Usuario2'; // Define o destinatário
    var message = $('#message').val();

    if (message !== '') {
      $.post('chat.php', { receiver: receiver, message: message }, function() {
        // Limpar o campo de entrada de mensagem após enviar
        $('#message').val('');
      });
    }
  });

  function updateChat() {
    var receiver = 'Usuario2'; // Define o destinatário

    $.get('chat.php', { receiver: receiver }, function(data) {
      // Atualizar o chat com as mensagens recebidas
      $('#chatbox').html(data);
    });
  }
});
