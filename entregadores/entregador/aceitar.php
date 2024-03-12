<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Nova Entrega | Fast Delivery</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
  </head>

  <body>
    <style>
    .input-box {
      width: 40px;
      height: 40px;
      text-align: center;
      border: 1px solid #d9dee3;
      border-radius: 6px;
      color:#697a8d;
    }

    
  </style>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a class="app-brand-link gap-2">
                   <span class="app-brand-logo demo">
                <img style="width: 40px;" src="../../assets/img/icons/logo.png">
              </span>
                  <span class="app-brand-text demo text-body fw-bolder">Fast Delivery</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Entrega quentinha!! 🔥</h4>
              <p class="mb-4">Para ter acesso a entrega forneça o seu identificador.</p>
             
                <div class="mb-3">
                  <?php
session_start();
date_default_timezone_set('America/Sao_Paulo');


require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['link'])) {
  $link = $_GET['link'];

  // Verificar se o link existe na tabela de entregas
  $sql = "SELECT * FROM entregas WHERE link = '$link'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $entrega = $result->fetch_assoc();
    $entregaId = $entrega['id'];
    $restauranteId = $entrega['restaurante_id'];

     // Consultar o nome do restaurante pelo ID do restaurante
    $sqlRestaurante = "SELECT nome_estabelecimento, endereco FROM restaurantes WHERE id = '$restauranteId'";
    $resultRestaurante = $conn->query($sqlRestaurante);

    if ($resultRestaurante->num_rows > 0) {
      $restaurante = $resultRestaurante->fetch_assoc();
      $nomeRestaurante = $restaurante['nome_estabelecimento'];
      $endereco = $restaurante['endereco'];
    } else {
      $nomeRestaurante = "Restaurante não encontrado";
    }

    $taxaEntrega = $entrega['taxa_entrega_entregador'];
    $taxaEntregaFormatada = str_replace('.', ',', $taxaEntrega);  

   // Exibir as informações da entrega
    echo "<div class='demo-inline-spacing mt-3'>
      <div class='list-group'>
        <a  class='list-group-item list-group-item-action active'><strong>Taxa de Entrega:</strong><br> <h4><strong>R$ " . $taxaEntregaFormatada . "</strong></h4></a>
        <a  class='list-group-item list-group-item-action'><strong>Restaurante:</strong><br> Será informado ao entregador que aceitar a entrega.</a>
        <a  class='list-group-item list-group-item-action'><strong>Endereço estabelecimento:</strong><br> Será informado ao entregador que aceitar a entrega.</a>
        <a  class='list-group-item list-group-item-action'><strong>Endereço do Cliente:</strong><br> Será informado ao entregador que aceitar a entrega.</a>
        <a  class='list-group-item list-group-item-action'><strong>Distância:</strong><br> " . $entrega['distancia'] . " Km</a>
        
      </div>
    </div>";

    // Exibir o formulário para o entregador inserir o identificador e aceitar a entrega
    echo "<br><form method='POST' action='aceitar_entrega.php'>";
    echo "<center><label for='identificador' class='form-label'>Seu código de entregador</label>";
     echo "<div id='input-container'>
    <input type='text' id='ite' class='input-box' maxlength='1'>
    <input type='text' id='ite' class='input-box' maxlength='1'>
    <input type='text' id='ite' class='input-box' maxlength='1'>
    <input type='text' id='ite' class='input-box' maxlength='1'>
  </div></center>";
    echo "<input type='text' style='display:none' class='form-control' placeholder='insira seu identificador' autofocus type='text' id='identificador' name='identificador' required><br>";
    echo "<input type='hidden' name='entrega_id' value='$entregaId'>";
    echo "<input class='btn btn-primary d-grid w-100' type='submit' value='Aceitar Entrega'>";
    echo "</form>";
  } else {
    // O link não existe na tabela de entregas
    echo "Link inválido!";
  }
} else {
  // Nenhum link foi fornecido
  echo "Link não encontrado!";
}
?>
                 
                </div>
                
              
               
                     
                    
              
            </div>
          </div>
          <!-- /Forgot Password -->
        </div>
      </div>
    </div>

    <!-- / Content -->

     <script>
    // Obtém todos os elementos de entrada
const inputBoxes = document.getElementsByClassName('input-box');
const identificadorInput = document.getElementById('identificador');

// Adiciona um evento de digitação a cada caixa de entrada
for (let i = 0; i < inputBoxes.length; i++) {
  inputBoxes[i].addEventListener('input', function() {
    // Obtém os valores dos campos de entrada
    const inputValues = Array.from(inputBoxes).map(inputBox => inputBox.value);

    // Atualiza o valor do campo "identificador" com os valores dos campos de entrada
    identificadorInput.value = inputValues.join('');
    
    // Preenche a próxima caixa de entrada se houver um valor digitado
    if (this.value.length > 0 && i < inputBoxes.length - 1) {
      inputBoxes[i + 1].focus();
    }
  });

  // Adiciona um evento de tecla pressionada (keydown) a cada caixa de entrada
  inputBoxes[i].addEventListener('keydown', function(event) {
    // Obtém o valor digitado
    const inputValue = this.value;

    // Verifica se a tecla pressionada é a tecla Backspace e a caixa de entrada está vazia
    if (event.key === 'Backspace' && inputValue.length === 0 && i > 0) {
      inputBoxes[i - 1].focus(); // Move o foco para a caixa de entrada anterior
      inputBoxes[i - 1].value = ''; // Apaga o valor da caixa de entrada anterior

      // Atualiza o valor do campo "identificador" com os valores atualizados dos campos de entrada
      const inputValues = Array.from(inputBoxes).map(inputBox => inputBox.value);
      identificadorInput.value = inputValues.join('');
    }
  });
}
  </script>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
