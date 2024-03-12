<!DOCTYPE html>
<html>
<head>
    <title>Tickets - Restaurante</title>
</head>
<body>
    <h1>Tickets - Restaurante</h1>
    <form id="abrirTicketForm">
        <label for="assunto">Assunto:</label>
        <input type="text" id="assunto" name="assunto" required><br>

        <label for="mensagem">Mensagem:</label>
        <textarea id="mensagem" name="mensagem" required></textarea><br>

        <button type="submit">Abrir Ticket</button>
    </form>

    <h2>Tickets Abertos</h2>
    <div id="listaTickets"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
