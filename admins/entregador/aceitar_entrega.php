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

    <title>Dados Entrega | Fast Delivery</title>

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
              <h4 class="mb-2 titulos">Entrega quentinha!! 🔥</h4>
              <p class="mb-4 titulos">Aqui estão as informações da entrega.</p>
             
                <div class="mb-3">
                  <?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $identificadorEntregador = $_POST['identificador'];
  $entregaId = $_POST['entrega_id'];

  // Verificar se a entrega já possui um entregador
  $sqlVerificarEntregador = "SELECT entregador FROM entregas WHERE id = '$entregaId'";
  $resultVerificarEntregador = $conn->query($sqlVerificarEntregador);

  if ($resultVerificarEntregador->num_rows > 0) {
    $entregaAtual = $resultVerificarEntregador->fetch_assoc();
    $entregadorAtual = $entregaAtual['entregador'];

    // Se a entrega já tiver um entregador, exibir uma mensagem
    if (!empty($entregadorAtual)) {
      echo "<style>.titulos {display:none}</style><h4 class='mb-2'>Poxaa!! 😢</h4>Esta entrega já possui um entregador.";
      exit();
    }
  }

  // Consultar o entregador pelo identificador fornecido
  $sqlEntregador = "SELECT * FROM entregadores WHERE cod_motoboy = '$identificadorEntregador'";
  $resultEntregador = $conn->query($sqlEntregador);

  if ($resultEntregador->num_rows > 0) {
    $entregador = $resultEntregador->fetch_assoc();
    $entregadorId = $entregador['id'];

    // Associar o entregador à entrega correspondente
    $sqlAtualizarEntrega = "UPDATE entregas SET entregador = '$entregadorId' WHERE id = '$entregaId'";
    if ($conn->query($sqlAtualizarEntrega) === TRUE) {
      // Exibir as informações da entrega
      $sql = "SELECT * FROM entregas WHERE id = '$entregaId'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $entrega = $result->fetch_assoc();

         

        echo "<div class='demo-inline-spacing mt-3'>
          <div class='list-group'>
            <a href='javascript:void(0);' class='list-group-item list-group-item-action active'><strong>Taxa de Entrega:</strong><br> <h4><strong>R$ " . $entrega['taxa_entrega'] . "</strong></h4></a>
            <a href='javascript:void(0);' class='list-group-item list-group-item-action'><strong>Restaurante:</strong><br> " . obterNomeRestaurante($entrega['restaurante_id'], $conn) . "</a>
            <a href='javascript:void(0);' class='list-group-item list-group-item-action'><strong>Endereço estabelecimento:</strong><br> " . obterEndereco($entrega['restaurante_id'], $conn) . "</a>
            <a href='javascript:void(0);' class='list-group-item list-group-item-action'><strong>Telefone estabelecimento:</strong><br> " . obterTelefone($entrega['restaurante_id'], $conn) . "</a>
            <a href='javascript:void(0);' class='list-group-item list-group-item-action'><strong>Endereço da entrega:</strong><br> " . $entrega['endereco_cliente'] . "</a>
            <a href='javascript:void(0);' class='list-group-item list-group-item-action'><strong>Distância:</strong><br> " . $entrega['distancia'] . " km</a>
            
          </div>
        </div>";
      } else {
        echo "Informações da entrega não encontradas.";
      }
    } else {
      echo "Erro ao associar o entregador: " . $conn->error;
    }
  } else {
    echo "<style>.titulos {display:none}</style><h4 class='mb-2'>Atenção!! 😳</h4>Seu identificador está incorreto, caso não tenha cadastro como entregador realize agora mesmo!<br><br><a href='link.com'><input class='btn btn-primary d-grid w-100' type='submit' value='Realizar cadastro'></a><br><input onclick='voltarPaginaAnterior()' class='btn btn-outline-primary d-grid w-100' type='submit' value='Voltar'>";
  }
}

// Função para obter o nome do restaurante pelo ID do restaurante
function obterNomeRestaurante($restauranteId, $conn) {
  $sql = "SELECT nome_estabelecimento FROM restaurantes WHERE id = '$restauranteId'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $restaurante = $result->fetch_assoc();
    return $restaurante['nome_estabelecimento'];
  } else {
    return "Restaurante não encontrado";
  }
}

// Função para obter o telefone do restaurante pelo ID do restaurante
function obterTelefone($restauranteId, $conn) {
  $sql = "SELECT telefone FROM restaurantes WHERE id = '$restauranteId'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $restaurante = $result->fetch_assoc();
    return $restaurante['telefone'];
  } else {
    return "Restaurante não encontrado";
  }
}

// Função para obter o endereço do restaurante pelo ID do restaurante
function obterEndereco($restauranteId, $conn) {
  $sql = "SELECT endereco FROM restaurantes WHERE id = '$restauranteId'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $restaurante = $result->fetch_assoc();
    return $restaurante['endereco'];
  } else {
    return "Restaurante não encontrado";
  }
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

   <script type="text/javascript">
      function voltarPaginaAnterior() {
    history.back();
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
