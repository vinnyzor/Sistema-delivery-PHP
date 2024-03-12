<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

require_once 'conexao.php';

$email = $_SESSION['email'];

$sql = "SELECT * FROM restaurantes WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();
  $restauranteId = $row['id'];
  $endereco = $row['endereco'];
  $fotoPerfil = $row['foto_perfil'];

  // Buscar as coordenadas do usuário logado
  $latitude = $row['latitude'];
  $longitude = $row['longitude'];

  $nome_estabelecimento = $row['nome_estabelecimento'];
  mb_internal_encoding('UTF-8');
  $nome_estabelecimento_maiusculo = mb_strtoupper($nome_estabelecimento, 'UTF-8');
  $fotoPerfil = $row['foto_perfil'];

  // Inserir marcador no primeiro mapa com as coordenadas buscadas
  echo "
  <script>
    var startMarker = L.marker([$latitude, $longitude]).addTo(map1);
  </script>
  ";

} else {
  header("Location: login.php");
  exit();
}
?>

<?php

// Função para gerar o QR code
function gerarQRCodePix($valor, $chavePix) {
    // Dados para a geração do QR code
    $payload = "00020126330014BR.GOV.BCB.PIX0111062579931305204000053039865802BR5924Vinicius Mendes de Sousa6009SAO PAULO610805409000622405206I1Nw0trf38FWP73jtfq6304D065";

    // Nome do arquivo que será gerado
    $arquivoQRCode = 'qrcode_pix.png';

    // Tamanho e margem do QR code
    $tamanho = 180; // Aumente o valor aqui para aumentar o tamanho do QR code
    $margem = 0; // Reduza o valor aqui para diminuir a margem do QR code

    // Incluir a biblioteca PHP QR Code via CDN
    echo '<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>';

    // Gerar o código JavaScript para exibir o QR code
    echo '<script>';
    echo 'var qrcode = new QRCode(document.getElementById("qrcode"), {width: ' . $tamanho . ', height: ' . $tamanho . ', margin: ' . $margem . '});';
    echo 'qrcode.makeCode("' . $payload . '");';
    echo '</script>';
}

?>
<!DOCTYPE html>

<html
  lang="pt-br"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Cobranças | Fast Delivery</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
 <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <style>

      .fade-in {
  animation: fade-in 0.5s ease-in-out;
}

.fade-out {
  animation: fade-out 0.5s ease-in-out;
}

@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fade-out {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
      .bs-toast {
    margin-top: 20px;
    margin-right: 20px;
  }

        #map1, #map2 {
            height: 400px;
            border-radius:6px;

            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
        }
    </style>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img style="width: 40px;" src="../assets/img/icons/logo.png">
              </span>
              <span class="app-brand-text demo menu-text fw-bolder ms-2">Fast Delivery</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
           
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Painel</span>
            </li>
           
             <li class="menu-item">
              <a href="dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                <div data-i18n="Analytics">Desempenho</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Entregas</span>
            </li>

             <li  class="menu-item">
              <a href="entregas.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-ul"></i>
                <div data-i18n="Analytics">Minhas Entregas</div>
              </a>
            </li>
            
           
 <li  class="menu-item">
              <a href="solicitar_entrega.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div data-i18n="Analytics">Solicitar Entrega</div>
              </a>
            </li>

<li class="menu-header small text-uppercase">
              <span class="menu-header-text">Configurações da Loja</span>
            </li>
            
           
 <li class="menu-item">
              <a href="horarios.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-timer"></i>
                <div data-i18n="Analytics">Horário</div>
              </a>
            </li>

             <li class="menu-item">
              <a href="configuracoes.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Minha Loja</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Financeiro</span>
            </li>

             <li class="menu-item active">
              <a href="cobranca.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="Analytics">Acerto de Dívidas</div>
              </a>
            </li>

            <li class="menu-item">
              <a id="botaoRepasse" data-bs-toggle="offcanvas"
                          data-bs-target="#offcanvasEnd"
                          aria-controls="offcanvasEnd" href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div data-i18n="Analytics">Repasse</div>
              </a>
            </li>
           


             <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Suporte</span>
            </li>

             <li class="menu-item">
              <a href="tickets.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-message-rounded-add"></i>
                <div data-i18n="Analytics">Tickets</div>
              </a>
            </li>

             <li class="menu-item">
              <a href="chamados.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-message-dots"></i>
                <div data-i18n="Analytics">Abrir Chamado <span id="mensagensNaoLidas"></span></div>
              </a>
            </li>
            
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <div><strong><?php echo $nome_estabelecimento_maiusculo; ?></strong></div>
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
               <strong><div id="data"></div></strong>&nbsp;-&nbsp;<strong><div id="hora"></div></strong>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                       
  <?php if (!empty($fotoPerfil)) : ?>
    <img class="w-px-40 rounded-circle" src="<?php echo $fotoPerfil; ?>" alt="Foto de Perfil">
  <?php else : ?>
    <img src="images_perfil/padrao.png" class="w-px-40 rounded-circle" alt="Foto de Perfil Padrão">
  <?php endif; ?>

                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              
  <?php if (!empty($fotoPerfil)) : ?>
    <img class="w-px-40 rounded-circle" src="<?php echo $fotoPerfil; ?>" alt="Foto de Perfil">
  <?php else : ?>
    <img class="w-px-40 rounded-circle" src="images_perfil/padrao.png" alt="Foto de Perfil Padrão">
  <?php endif; ?>

                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block"><?php echo $nome_estabelecimento_maiusculo; ?></span>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="dashboard.php">
                        <i class="bx bx-bar-chart me-2"></i>
                        <span class="align-middle">Meu desempenho</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="configuracoes.php">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Configurações Loja</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="cobranca.php">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle">Cobrança</span>
                          
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                   <li>
                      <a class="dropdown-item" href="logout.php">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Sair</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">

                            
            

               <div class="card mb-4">
                <h5 class="card-header">Suas dívidas</h5>
                <div class="card-body">

                                   <div class="table-responsive text-nowrap">
                  <?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

require_once 'conexao.php';

$email = $_SESSION['email'];

$sql = "SELECT * FROM restaurantes WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();
  $restauranteId = $row['id'];
  

  $dividasSql = "SELECT * FROM financas WHERE restaurante_id = $restauranteId";
$dividasResult = $conn->query($dividasSql);

echo '<table class="table">
        <thead>
          <tr class="text-nowrap">
            <th>N° dívida</th>
            <th>Valor devido</th>
            <th>Dia dívida</th>
            <th>Status pagamento</th>
          </tr>
        </thead>
        <tbody>';

while ($divida = $dividasResult->fetch_assoc()) {
  $idDivida = $divida['id'];
  $valorDevido = $divida['valor_devido'];
  $diaDivida = $divida['dia_divida'];
    $dataFormatada = date('d/m/Y', strtotime($diaDivida));
  $statusDivida = $divida['status_divida'];

  echo "<tr>
          <th scope='row'>$idDivida</th>
          <td><span";

  if ($statusDivida === 'em_aberto') {
    echo ' class="badge bg-danger"';
  } elseif ($statusDivida === 'pago') {
    echo ' class="badge bg-success"';
  }

  echo ">R$ $valorDevido</span></td>
          <td>$dataFormatada</td>
          <td>";

  if ($statusDivida === 'em_aberto') {
    echo '<span class="badge bg-label-warning me-1">Em aberto</span>';
  } elseif ($statusDivida === 'pago') {
    echo '<span class="badge bg-label-info me-1">Pago</span>';
  }

  echo "</td>
        </tr>";
}

echo '</tbody>
      </table>';
  

} else {
  header("Location: login.php");
  exit();
}
?>
                </div>   
  
                   

                   
                </div>
              </div>

              
            </div>
            <!-- / Content -->


                      

                        <!-- Modal -->
                        <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Entrega</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <div id="distanciaDiv"></div>
                                  </div>
                                </div>
                                <div class="row g-2">
                                  <div class="col mb-0">
                                    <div id="taxaDiv"></div>
                                    </div>
                                 
                                </div>
                                <div class="row g-2">
                                  <div class="col mb-0">
                                    <label for="start">Informe o endereço do cliente:</label>
                                    <input type="text" class="form-control" id="endereco_cliente" name="endereco_cliente" data-restaurante-id="<?php echo $restauranteId; ?>">

                                    </div>
                                 
                                </div>
                              </div>
                              <div class="modal-footer">
                                
                                <button type="button" id="solicitarEntrega" class="btn btn-primary"><i class="bx bx-package me-2"></i> Solicitar Entrega</button>
                              </div>
                            </div>
                          </div>
                        </div>


                            <div id="toastContainer"></div>              
                   
                       
                        <div
                          class="offcanvas offcanvas-end"
                          tabindex="-1"
                          id="offcanvasEnd"
                          aria-labelledby="offcanvasEndLabel"
                        >
                          <div class="offcanvas-header">
                            <h5 id="offcanvasEndLabel" class="offcanvas-title">Conta de Repasse</h5>
                            <button
                              type="button"
                              class="btn-close text-reset"
                              data-bs-dismiss="offcanvas"
                              aria-label="Close"
                            ></button>
                          </div>
                          
                          <div class="offcanvas-body my-auto mx-0 flex-grow-0">
                            <p class="text-center">
                              O restaurante deve repassar <strong id="resultadoRepasse"></strong> hoje. Faça o repasse até o final do dia para continuar usando os nossos serviços no dia seguinte.<br>Escaneie o <strong>QRcode</strong> e efetue o pagamento.
                              </p>
                              <center><div id="qrcode"></div></center><br>
    
    <?php
        
            gerarQRCodePix($valor, $chavePix);
       
    ?>
                            <button type="button" class="btn btn-primary mb-2 d-grid w-100">Notifique o pagamento</button>
                            <button
                              type="button"
                              class="btn btn-outline-secondary d-grid w-100"
                              data-bs-dismiss="offcanvas"
                            >
                              Fechar
                            </button>
                          </div>
                        </div>
                    


            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div>
                </div>
                <div class="mb-2 mb-md-0">
                  © Fast Delivery,
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                </div>
                
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
<script src="scripts.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
     <script>
        var map1 = L.map('map1').setView([<?= $latitude ?>, <?= $longitude ?>], 12);
        var map2 = L.map('map2').setView([-15.6121, -47.6176], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map1);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map2);
        
        var startMarker = null;
        var endMarker = null;
        
        var startCoords = [<?= $latitude ?>, <?= $longitude ?>]; // Substitua $latitude e $longitude pelas variáveis PHP corretas
if (startMarker) {
    map1.removeLayer(startMarker);
}
startMarker = L.marker(startCoords).addTo(map1);
map1.setView(startCoords, 15); // Ajustar a visualização do mapa para as coordenadas iniciais
$('#start').val(startCoords[0] + ', ' + startCoords[1]);
        
        $('#bairro').on('change', function() {
            var bairro = $(this).val();
            if (bairro) {
                // Lógica para preencher o select de conjunto/modulo
                var conjuntos = getConjuntosByBairro(bairro);
                $('#conjunto').empty().append('<option value="">Selecione o conjunto/modulo</option>');
                for (var i = 0; i < conjuntos.length; i++) {
                    var conjunto = conjuntos[i];
                    $('#conjunto').append('<option value="' + conjunto.coordinates + '">' + conjunto.name + '</option>');
                }
            } else {
                $('#conjunto').empty().append('<option value="">Selecione o conjunto/modulo</option>');
            }
        });
        
        $('#conjunto').on('change', function() {
    var coordinates = $(this).val();
    if (coordinates) {
        if (endMarker) {
            map2.removeLayer(endMarker);
        }
        var latLng = coordinates.split(',').map(function(coord) {
            return parseFloat(coord.trim());
        });
        endMarker = L.marker(latLng).addTo(map2);
        $('#end').val(coordinates);

        // Ajustar o zoom e posição do mapa para exibir a área marcada
        var bounds = L.latLngBounds([latLng, latLng]); // Criar um limite de área com base no ponto selecionado
        var padding = [50, 50]; // Espaçamento adicional ao redor da área marcada
        map2.fitBounds(bounds, { padding: padding, maxZoom: 15 }); // Ajustar o zoom e posição do mapa com padding e zoom máximo
    } else {
        if (endMarker) {
            map2.removeLayer(endMarker);
        }
        $('#end').val('');
    }
});
        

 $(document).ready(function() {
  var roundedDistance, deliveryCost; // Declare as variáveis fora das funções

  $('#calculate').on('click', function() {
    var startCoords = $('#start').val().split(',').map(function(coord) {
      return parseFloat(coord.trim());
    });
    var endCoords = $('#end').val().split(',').map(function(coord) {
      return parseFloat(coord.trim());
    });

    var bairro = $('#bairro').val();
    var conjunto = $('#conjunto').val();

    if (bairro === '' || conjunto === '') {
      const toastContainer = document.getElementById('toastContainer');
      toastContainer.innerHTML = `
        <div class="bs-toast toast fade show bg-danger position-fixed top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">Atenção!!</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
            Por favor escolha o endereço do cliente antes de solicitar uma entrega.
          </div>
        </div>
      `;

      const toast = document.querySelector('.bs-toast');
      toast.classList.add('fade-in');

      setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => {
          toast.remove();
        }, 500); // Tempo de espera para a animação de fade-out ser concluída
      }, 7000);

      return;
    }

    if (startCoords.length === 2 && endCoords.length === 2) {
      var lat1 = startCoords[0];
      var lng1 = startCoords[1];
      var lat2 = endCoords[0];
      var lng2 = endCoords[1];

      var distance = calculateDistance(lat1, lng1, lat2, lng2);
      roundedDistance = distance.toFixed(1);
      deliveryCost = calculateDeliveryCost(distance);

      var distanciaDiv = document.getElementById('distanciaDiv');
      var taxaDiv = document.getElementById('taxaDiv');

      distanciaDiv.innerHTML = "Distância: " + roundedDistance + " km.";
      taxaDiv.innerHTML = "Custo de entrega: R$ " + deliveryCost.toFixed(2) + ".";

      var modalElement = document.getElementById('modalCenter');
      var modal = new bootstrap.Modal(modalElement);
      var toast = document.querySelector('.bs-toast');

      if (toast && toast.classList.contains('show')) {
        toast.remove();
      }

      modal.show();
    }
  });

  $('#modalCenter').on('shown.bs.modal', function() {
    $('#solicitarEntrega').off('click'); // Remover todos os manipuladores de eventos antes de adicionar novamente
    $('#solicitarEntrega').on('click', function() {
      var enderecoCliente = $('#endereco_cliente').val();
      var restauranteId = $('#endereco_cliente').data('restaurante-id');
      var distancia = parseFloat(roundedDistance);
      var taxaEntrega = parseFloat(deliveryCost.toFixed(2));
      var dataHora = new Date().toISOString();

      $.ajax({
        type: 'POST',
        url: 'enviar_entrega.php', // Arquivo PHP para salvar os dados
        data: {
          restauranteId: restauranteId,
          enderecoCliente: enderecoCliente,
          distancia: distancia,
          taxaEntrega: taxaEntrega,
          dataHora: dataHora
        },
        success: function(response) {
          // Lógica de sucesso, se necessário
          console.log(response);
        },
        error: function(xhr, status, error) {
          // Lógica de tratamento de erro, se necessário
          console.error(error);
        }
      });
    });
  });
});




function calculateDeliveryCost(distance) {
    var costPer100m = 0.11; // Custo por cada 100 metros
    var minimumCost = 7.0; // Custo mínimo para distâncias menores que 100 metros

if (distance > 1.3) {
    // Cálculo do custo de entrega com base na distância
    var cost = Math.ceil(distance * 10) * costPer100m + minimumCost; // Arredonda para cima o número de centenas de metros e multiplica pelo custo
}



    // Verifica se a distância é menor que 100 metros e aplica o custo mínimo se necessário
    if (distance < 1.3) {
        cost = 8.0;
    }

    return cost;
}

        
        function calculateDistance(lat1, lng1, lat2, lng2) {
            var earthRadius = 6371;
            
            var dLat = deg2rad(lat2 - lat1);
            var dLng = deg2rad(lng2 - lng1);
            
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLng / 2) * Math.sin(dLng / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            
            var distance = earthRadius * c;
            return distance;
        }
        
        function deg2rad(deg) {
            return deg * (Math.PI / 180);
        }
        
        function getConjuntosByBairro(bairro) {
    var conjuntos = [];

    // Exemplo de dados de conjuntos por bairro
    var data = {
        "Jardim Roriz": [
            { name: "Quadra 1", coordinates: "-15.603197562890616, -47.65302658081055" },
            { name: "Quadra 2", coordinates: "-15.603445565594583, -47.65096664428711" },
            { name: "Quadra 3", coordinates: "-15.60352823309596, -47.64907836914063" },
            { name: "Quadra 4", coordinates: "-15.603548899966107, -47.647233009338386" },
            { name: "Quadra 5", coordinates: "-15.60352823309596, -47.64538764953613" },
            { name: "Quadra 6", coordinates: "-15.603734901703668, -47.64356374740601" },
            { name: "Quadra 7", coordinates: "-15.603817569088456, -47.64216899871827" },
            // Adicione mais conjuntos conforme necessário
        ],
        "Vila Buritis": [
            { name: "Quadra 1", coordinates: "-15.613902740056405, -47.64671802520753" },
            { name: "Quadra 2", coordinates: "-15.61700259048542, -47.64678239822388" },
            { name: "Quadra 3", coordinates: "-15.619792415790746, -47.64695405960083" },
            { name: "Quadra 4", coordinates: "-15.623160820919493, -47.64716863632203" },
            { name: "Quadra 5", coordinates: "-15.626033220811545, -47.64742612838746" },
            { name: "Quadra 6", coordinates: "-15.629112223451175, -47.6479196548462" },
            // Adicione mais conjuntos conforme necessário
        ],
        "Vila Buritis II": [
            { name: "Quadra 10 Conjunto A ao G", coordinates: "-15.613809743819358, -47.64183640480042" },
            { name: "Quadra 10 Conjunto H ao P", coordinates: "-15.615566332281091, -47.638531923294074" },
            { name: "Quadra 20 Conjunto A ao G", coordinates: "-15.616568614247257, -47.64161109924317" },
            { name: "Quadra 20 Conjunto H ao I", coordinates: "-15.616279296244702, -47.63834953308106" },
            { name: "Entre Quadra 10/20", coordinates: "-15.615648994896716, -47.64224410057068" },
            { name: "Caveral Conjunto A ao E", coordinates: "-15.611112834596177, -47.64457225799561" },
            // Adicione mais conjuntos conforme necessário
        ],
        "Vila Buritis III": [
            { name: "Quadra 11", coordinates: "-15.614595044050782, -47.63578534126282" },
            { name: "Quadra 12", coordinates: "-15.613825243195128, -47.63413310050965" },
            { name: "Quadra 13", coordinates: "-15.612962442827651, -47.633247971534736" },
            { name: "Quadra 14", coordinates: "-15.611908478170783, -47.63248622417451" },
            { name: "Quadra 15", coordinates: "-15.611019837093728, -47.631611824035645" },
            { name: "Quadra 16", coordinates: "-15.610343020666676, -47.630372643470764" },
            // Adicione mais conjuntos conforme necessário
        ],
        "Vila Buritis IV": [
            { name: "Quadra 18", coordinates: "-15.618769484254768, -47.64112830162049" },
            { name: "Quadra 19", coordinates: "-15.6178705402053, -47.63781309127808" },
            { name: "Quadra 21", coordinates: "-15.618325179067927, -47.630345821380615" },
            { name: "Quadra 22", coordinates: "-15.618252850225424, -47.63257741928101" },
            { name: "Quadra 23", coordinates: "-15.619162125246085, -47.63464808464051" },
            { name: "Quadra 24", coordinates: "-15.621311304712584, -47.63706207275391" },
            { name: "Quadra 25", coordinates: "-15.62190025861243, -47.639615535736084" },
            { name: "Quadra 26", coordinates: "-15.622416883446625, -47.64194369316102" },
            // Adicione mais conjuntos conforme necessário
        ],
        "Vila de Fátima": [
            { name: "Quadra 18", coordinates: "-15.618769484254768, -47.64112830162049" },
            { name: "Quadra 19", coordinates: "-15.6178705402053, -47.63781309127808" },
            { name: "Quadra 21", coordinates: "-15.618325179067927, -47.630345821380615" },
            { name: "Quadra 22", coordinates: "-15.618252850225424, -47.63257741928101" },
            { name: "Quadra 23", coordinates: "-15.619162125246085, -47.63464808464051" },
            { name: "Quadra 24", coordinates: "-15.621311304712584, -47.63706207275391" },
            { name: "Quadra 25", coordinates: "-15.62190025861243, -47.639615535736084" },
            { name: "Quadra 26", coordinates: "-15.622416883446625, -47.64194369316102" },
            // Adicione mais conjuntos conforme necessário
        ]
        // Adicione mais bairros e seus respectivos conjuntos
    };

    if (data.hasOwnProperty(bairro)) {
        conjuntos = data[bairro];
    }

    return conjuntos;
}

    </script>
  </body>
</html>
