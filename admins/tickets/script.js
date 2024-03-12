$(document).ready(function() {
    // Manipulador de envio do formulário
    $('#responderTicketForm').submit(function(event) {
        event.preventDefault();

        var idTicket = $('#idTicket').val();
        var resposta = $('#resposta').val();

        // Enviar solicitação AJAX para responder ao ticket
        $.ajax({
            url: 'tickets/responder_ticket.php',
            type: 'POST',
            data: {
                idTicket: idTicket,
                resposta: resposta
            },
            success: function(response) {
                alert('Ticket respondido com sucesso!');
                // Redirecionar para outra página ou executar outras ações necessárias
            },
            error: function() {
                alert('Erro ao responder ao ticket.');
            }
        });
    });
});


$(document).ready(function () {

        // Abre o modal de confirmação para exclusão ao clicar no botão "Excluir"
    $(document).on('click', '.excluirTicketBtn', function () {
        var ticketId = $(this).data('id');
        $('#excluirTicketModal').data('ticket-id', ticketId);
        $('#excluirTicketModal').modal('show');
    });

    // Exclui o ticket ao confirmar a exclusão no modal
    $('#confirmarExclusaoBtn').on('click', function () {
        var ticketId = $('#excluirTicketModal').data('ticket-id');
        excluirTicket(ticketId);
    });

    // Função para excluir um ticket
    function excluirTicket(ticketId) {
        $.ajax({
            type: 'POST',
            url: 'tickets/excluir_ticket.php',
            data: { idTicket: ticketId },
            success: function (response) {
                if (response === 'success') {
                    // Atualiza a lista de tickets após a exclusão
                    carregarTickets();
                } else {
                    // Exibie uma mensagem de erro caso ocorra um problema na exclusão
                    alert('Erro ao excluir o ticket.');
                }
            }
        });
        $('#excluirTicketModal').modal('hide');
    }



    // Função para carregar os tickets abertos e respondidos
    function carregarTickets() {
        $.ajax({
            type: 'GET',
            url: 'tickets/carregar_tickets.php',
            dataType: 'json',
            success: function (data) {
                $('#ticketsAbertos').html(data.abertos);
                $('#ticketsRespondidos').html(data.respondidos);
            }
        });
    }

    // Chama a função para carregar os tickets ao carregar a página
    carregarTickets();

    // Abre o modal de resposta ao clicar no botão "Responder"
    $(document).on('click', '.responderTicketBtn', function () {
        var ticketId = $(this).data('id');
        $('#idTicket').val(ticketId);
        $('#responderTicketModal').modal('show');
    });
});



