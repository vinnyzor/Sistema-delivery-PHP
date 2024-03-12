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
// Arquivo de conexão com o banco de dados
require_once 'conexao.php';
date_default_timezone_set('America/Sao_Paulo');
// Obter a data atual
$dataAtual = date('Y-m-d');

// Consulta para obter o número total de entregas hoje
$sqlEntregasHoje = "SELECT COUNT(*) AS total_entregas_hoje FROM entregas WHERE restaurante_id='$restauranteId' AND DATE(data_hora) = '$dataAtual'";
$resultEntregasHoje = $conn->query($sqlEntregasHoje);
$rowEntregasHoje = $resultEntregasHoje->fetch_assoc();
$totalEntregasHoje = $rowEntregasHoje['total_entregas_hoje'];

// Consulta para obter o número total de entregas em todos os dias anteriores
$sqlEntregasAnteriores = "SELECT COUNT(*) AS total_entregas_anteriores FROM entregas WHERE restaurante_id='$restauranteId' AND DATE(data_hora) < '$dataAtual'";
$resultEntregasAnteriores = $conn->query($sqlEntregasAnteriores);
$rowEntregasAnteriores = $resultEntregasAnteriores->fetch_assoc();
$totalEntregasAnteriores = $rowEntregasAnteriores['total_entregas_anteriores'];

// Consulta para obter as informações das entregas do dia atual
$sqlEntregas = "SELECT * FROM entregas WHERE restaurante_id='$restauranteId' AND DATE(data_hora) = CURDATE()";
$resultEntregas = $conn->query($sqlEntregas);


// Calcular a porcentagem de entregas feitas hoje em relação ao total de entregas dos dias anteriores
if ($totalEntregasAnteriores > 0) {
  $porcentagemEntregasHoje = ($totalEntregasHoje / $totalEntregasAnteriores) * 100;
} else {
  $porcentagemEntregasHoje = 0; // Caso não haja entregas anteriores, a porcentagem será 0.
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
  lang="en"
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

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0"></script>
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
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

             <li  class="menu-item active">
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

             <li class="menu-item">
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
              
              <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-12">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      
                      <div class="col-md-12">
                        <div class="card-body">
                          <div class="text-center">
                            <div style="z-index: 9999999999999;" class="btn-group">
                      <button
                        type="button"
                        class="btn btn-primary dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                      >
                        Escolha o período
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" id="diaAtual" href="javascript:void(0);">Dia atual</a></li>
                        <li><a class="dropdown-item" id="semanaAtual" href="javascript:void(0);">Semana atual</a></li>
                        <li><a class="dropdown-item" id="mesAtual" href="javascript:void(0);">Mês atual</a></li>
                        <li>
                          <hr class="dropdown-divider" />
                        </li>
                        <li><a class="dropdown-item" id="todasEntregas" href="javascript:void(0);">Todas entregas</a></li>
                      </ul>
                    </div><br><br><center><div id="data-container" class="text-center" style="width: 40%;"></div></center><br>
                    <div id="table-container"></div>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Order Statistics -->

              </div>
            </div>
            <!-- / Content -->

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

<script type="text/javascript">
  $(document).ready(function() {
    // Função para buscar as entregas com base no período selecionado
    function buscarEntregas(periodo) {
      // Fazer uma requisição AJAX para buscar as entregas do banco de dados
      $.ajax({
        url: 'buscar_entregas.php', // Substitua 'seu_script.php' pelo nome do script que lidará com a busca no banco de dados
        method: 'POST',
        data: { periodo: periodo }, // Enviar o período selecionado como parâmetro para o script
        // ...
        success: function(response) {
          // Processar a resposta do servidor (os dados das entregas) aqui
          var entregas = JSON.parse(response); // Converter a resposta JSON em um array de objetos

          // Criar a tabela HTML dinamicamente
          var table = $('<table>').addClass('table');
          var thead = $('<thead>').appendTo(table);
          var tbody = $('<tbody>').appendTo(table);

          // Criar a linha de cabeçalho da tabela
          var headerRow = $('<tr>').appendTo(thead);
          $('<th>').text('N° entrega').appendTo(headerRow);
          $('<th>').text('Endereço do Cliente').appendTo(headerRow);
          $('<th>').text('Distância').appendTo(headerRow);
          $('<th>').text('Taxa de Entrega').appendTo(headerRow);
          $('<th>').text('Data e Hora').appendTo(headerRow);
          $('<th>').text('Status').appendTo(headerRow);

          // Preencher a tabela com os dados das entregas
          entregas.forEach(function(entrega) {
            var dataHora = new Date(entrega.data_hora);
            var dataFormatada = dataHora.toLocaleString();

            var row = $('<tr>').appendTo(tbody);
            $('<td>').text(entrega.id).appendTo(row);
            $('<td>').text(entrega.endereco_cliente).appendTo(row);
            $('<td>').text(entrega.distancia + ' Km').appendTo(row);
            $('<td>').prepend('R$ ').append(entrega.taxa_entrega).appendTo(row);
            $('<td>').text(dataFormatada).appendTo(row);
            var status = entrega.status;

            var badgeClass = '';
            var statusText = '';

            if (status === 'aguardando_entregador') {
              badgeClass = 'badge bg-label-warning me-1';
              statusText = 'Aguardando entregador';
            } else if (status === 'em_rota') {
              badgeClass = 'badge bg-label-info me-1';
              statusText = 'Em rota';
            } else if (status === 'concluida') {
              badgeClass = 'badge bg-label-success me-1';
              statusText = 'Concluída';
            }

            $('<td>').append($('<span>').addClass(badgeClass).text(statusText)).appendTo(row);
          });

          // Verificar se nenhuma entrega foi encontrada
          if (entregas.length === 0) {
            var emptyRow = $('<tr>').appendTo(tbody);
            $('<td>').attr('colspan', '6').addClass('text-center').text('Nenhuma entrega encontrada.').appendTo(emptyRow);
          }

          // Anexar a tabela ao elemento desejado na página HTML
          $('#table-container').html(table); // Substitua 'table-container' pelo ID ou seletor do elemento onde você deseja exibir a tabela
        },
        // ...

        error: function(xhr, status, error) {
          // Lidar com erros de requisição aqui
          console.log(error);
        }
      });
    }

    // Função para exibir a data correspondente à opção selecionada
    function exibirData(opcao) {
      var data;

      if (opcao === 'semanaAtual') {
  var startDate = moment().startOf('week').format('DD/MM/YYYY');
  var endDate = moment().endOf('week').format('DD/MM/YYYY');
  data = "<div class='alert alert-danger alert-dismissible' role='alert'>" + startDate + " - " + endDate + "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
} else if (opcao === 'diaAtual') {
  data = "<div class='alert alert-danger alert-dismissible' role='alert'>" + moment().format('DD/MM/YYYY') + "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
} else if (opcao === 'todasEntregas') {
  data = "<div class='alert alert-danger alert-dismissible' role='alert'>Exibindo todas as entregas.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
} else if (opcao === 'mesAtual') {
  var startDate = moment().startOf('month').format('DD/MM/YYYY');
  var endDate = moment().endOf('month').format('DD/MM/YYYY');
  data = "<div class='alert alert-danger alert-dismissible' role='alert'>" + startDate + " - " + endDate + "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
}





      $('#data-container').html(data);
    }

    // Chamar a função para buscar todas as entregas ao carregar a página
    buscarEntregas("todasEntregas");
    $('#data-container').html("<div class='alert alert-danger alert-dismissible' role='alert'>" + moment().format('DD/MM/YYYY') + "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
    // Lidar com o evento de clique nos itens do menu suspenso
    $('.dropdown-menu a.dropdown-item').click(function() {
      var opcao = $(this).attr('id'); // Obter o ID do item clicado
      exibirData(opcao); // Chamar a função para exibir a data correspondente à opção selecionada
      buscarEntregas(opcao); // Chamar a função para buscar as entregas com base no período selecionado
    });
  });
</script>

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

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="scripts.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
