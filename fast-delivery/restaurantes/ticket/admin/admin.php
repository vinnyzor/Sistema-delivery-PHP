<!DOCTYPE html>
<html>
<head>
    <title>Admin - Tickets</title>
    <!-- Inclua o link para o CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <h1>Admin - Tickets</h1>

    <h2>Tickets Abertos</h2>
    <div id="ticketsAbertos">
        <!-- Aqui serão exibidos os tickets abertos -->
    </div>

    <h2>Tickets Respondidos</h2>
    <div id="ticketsRespondidos">
        <!-- Aqui serão exibidos os tickets respondidos -->
    </div>

    <!-- Modal para responder o ticket -->
    <div class="modal fade" id="responderTicketModal" tabindex="-1" aria-labelledby="responderTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responderTicketModalLabel">Responder Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="responderTicketForm">
                        <input type="hidden" id="idTicket" name="idTicket">
                        <div class="mb-3">
                            <label for="resposta" class="form-label">Resposta:</label>
                            <textarea class="form-control" id="resposta" name="resposta" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Responder Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ... Código anterior ... -->

<!-- Modal de confirmação para exclusão -->
<div class="modal fade" id="excluirTicketModal" tabindex="-1" aria-labelledby="excluirTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excluirTicketModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja excluir este ticket?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarExclusaoBtn">Excluir</button>
            </div>
        </div>
    </div>
</div>

<!-- ... Código anterior ... -->

    <!-- Inclua os scripts do jQuery e do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
