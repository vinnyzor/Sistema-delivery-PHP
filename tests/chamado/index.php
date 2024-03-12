<!DOCTYPE html>
<html>
<head>
  <title>Chat entre 2 pessoas</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <style type="text/css">
    #chatbox {
  height: 200px;
  overflow-y: scroll;
  border: 1px solid #ccc;
  padding: 10px;
}

input[type="text"], button {
  margin-top: 10px;
}
  </style>
  <div id="chatbox"></div>
  <input type="text" id="message" placeholder="Digite sua mensagem...">
  <button id="send">Enviar</button>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="script.js"></script>
</body>
</html>
