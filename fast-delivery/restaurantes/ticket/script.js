$(document).ready(function() {
    // Manipulador de envio do formulário
    $('#abrirTicketForm').submit(function(event) {
        event.preventDefault();

        var assunto = $('#assunto').val();
        var mensagem = $('#mensagem').val();

        // Enviar solicitação AJAX para abrir um novo ticket
        $.ajax({
            url: 'abrir_ticket.php',
            type: 'POST',
            data: {
                assunto: assunto,
                mensagem: mensagem
            },
            success: function(response) {
                alert('Ticket aberto com sucesso!');
                // Redirecionar para outra página ou executar outras ações necessárias
            },
            error: function() {
                alert('Erro ao abrir o ticket.');
            }
        });
    });

    // Função para obter e exibir a lista de tickets
    function obterListaTickets() {
        $.ajax({
            url: 'obter_tickets.php',
            type: 'GET',
            success: function(response) {
                $('#listaTickets').html(response);
            },
            error: function() {
                alert('Erro ao obter a lista de tickets.');
            }
        });
    }

    // Chamar a função para obter e exibir a lista de tickets ao carregar a página
    obterListaTickets();
});
