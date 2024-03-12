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
$sqlEntregasHoje = "SELECT COUNT(*) AS total_entregas_hoje FROM entregas WHERE restaurante_id='$restauranteId' AND DATE(data_hora) = CURDATE()";
$resultEntregasHoje = $conn->query($sqlEntregasHoje);
$rowEntregasHoje = $resultEntregasHoje->fetch_assoc();
$totalEntregasHoje = $rowEntregasHoje['total_entregas_hoje'];

// Consulta para obter o número total de entregas em todos os dias anteriores
$sqlEntregasAnteriores = "SELECT COUNT(*) AS total_entregas_anteriores FROM entregas WHERE restaurante_id='$restauranteId' AND DATE(data_hora) < CURDATE()";
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

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

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
           
             <li class="menu-item active">
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
    <img class="w-px-40 h-auto rounded-circle" src="<?php echo $fotoPerfil; ?>" alt="Foto de Perfil">
  <?php else : ?>
    <img src="images_perfil/padrao.png" class="w-px-40 h-auto rounded-circle" alt="Foto de Perfil Padrão">
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
    <img class="w-px-40 h-auto rounded-circle" src="<?php echo $fotoPerfil; ?>" alt="Foto de Perfil">
  <?php else : ?>
    <img class="w-px-40 h-auto rounded-circle" src="images_perfil/padrao.png" alt="Foto de Perfil Padrão">
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
                <div class="col-lg-8 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Bem vindo, <?php echo $nome_estabelecimento; ?> 🎉</h5>
                          <p class="mb-4">
                            Você fez <span class="fw-bold"><?php echo number_format($porcentagemEntregasHoje, 0); ?>%</span> mais entregas hoje. Confira suas entregas.
                          </p>

                          <a href="entregas.php" class="btn btn-sm btn-outline-primary">Ver entregas</a>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<lottie-player src="https://assets9.lottiefiles.com/packages/lf20_nioprtrk.json"  background="transparent"  speed="1"  style="width: 160px; height: 160px;"  loop  autoplay></lottie-player>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obter a data atual
$date = date("Y-m-d");

// Consulta SQL para o dia atual
$sql = "SELECT COUNT(*) AS total_entregas, SUM(taxa_entrega) AS total_taxa_entrega, SUM(CASE WHEN status = 'aguardando_entregador' THEN 1 ELSE 0 END) AS total_em_andamento, SUM(CASE WHEN status = 'em_rota' THEN 1 ELSE 0 END) AS total_em_rota FROM entregas WHERE restaurante_id='$restauranteId' AND DATE(data_hora) = '$date'";

$result = $conn->query($sql);

// Inicializar variáveis
$totalEntregas = 0;
$totalTaxaEntrega = 0;
$totalEmAndamento = 0;
$totalEmRota = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalEntregas = $row["total_entregas"];
    $totalTaxaEntrega = $row["total_taxa_entrega"];
    $totalEmAndamento = $row["total_em_andamento"];
    $totalEmRota = $row["total_em_rota"];
}

$conn->close();
?>


        


                <div class="col-lg-4 col-md-4 order-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/chart-success.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            </div>
                          <span class="fw-semibold d-block mb-1">Entregas hoje</span>
                          <h4 class="card-title mb-2">
                        <?php echo ($totalEntregas > 0) ? ($totalEntregas == 1 ? "1 entrega" : $totalEntregas . " entregas") : "nenhuma"; ?></h4>
                          
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/chart-success.png"
                                alt="Credit Card"
                                class="rounded"
                              />
                            </div>
                            </div>
                          <span class="fw-semibold d-block mb-1">Esp. Entregador</span>
                          <h4 class="card-title text-nowrap mb-1"><?php echo ($totalEmAndamento > 0) ? ($totalEmAndamento == 1 ? "1 entrega" : $totalEmAndamento . " entregas") : "nenhuma"; ?></h4>
                         
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Total Revenue -->
                <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      <div class="col-md-8">
                        <h5 class="card-header m-0 me-2 pb-3">Entregas na Semana</h5>

                 
<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta SQL para obter os dados das entregas
$sql = "SELECT * FROM entregas WHERE restaurante_id='$restauranteId'";
$result = $conn->query($sql);

// Array para armazenar o total de entregas de cada dia da semana
$totalEntregas = array(
    'Domingo' => 0,
    'Segunda-feira' => 0,
    'Terça-feira' => 0,
    'Quarta-feira' => 0,
    'Quinta-feira' => 0,
    'Sexta-feira' => 0,
    'Sábado' => 0
);

// Array para mapear os nomes dos dias da semana em inglês para português
$traducaoDiasSemana = array(
    'Sunday' => 'Domingo',
    'Monday' => 'Segunda-feira',
    'Tuesday' => 'Terça-feira',
    'Wednesday' => 'Quarta-feira',
    'Thursday' => 'Quinta-feira',
    'Friday' => 'Sexta-feira',
    'Saturday' => 'Sábado'
);

// Obtém a semana atual
$semanaAtual = date('W');

// Processa os resultados da consulta
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Obtém o dia da semana da data_hora
        $dataHora = $row['data_hora'];
        $diaSemana = date('l', strtotime($dataHora));

        // Verifica se a entrega está na semana atual
        $semanaEntrega = date('W', strtotime($dataHora));
        if ($semanaEntrega == $semanaAtual) {
            // Traduz o nome do dia da semana para português, se necessário
            if (isset($traducaoDiasSemana[$diaSemana])) {
                $diaSemanaTraduzido = $traducaoDiasSemana[$diaSemana];
                // Incrementa o total de entregas do dia da semana traduzido
                $totalEntregas[$diaSemanaTraduzido]++;
            }
        }
    }
}

// Fecha a conexão com o banco de dados
$conn->close();

// Codifica o array como JSON
$totalEntregasJSON = json_encode(array_values($totalEntregas));
?>



 <div id="totalRevenueChart" class="px-2"></div>

 <script type="text/javascript">
  const totalRevenueChartEl = document.querySelector('#totalRevenueChart');

const totalRevenueChartOptions = {
  series: [
    {
      name: 'Entregas',
      data: JSON.parse('<?php echo $totalEntregasJSON; ?>')
    }
  ],
  chart: {
    height: 270,
    type: 'bar',
    toolbar: {
      show: false
    }
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '85%',
      borderRadius: 12,
      startingShape: 'rounded',
      endingShape: 'rounded'
    }
  },
  colors: ['#ea1d2c'],
  dataLabels: {
    enabled: false
  },
  xaxis: {
    categories: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
    labels: {
      style: {
        fontSize: '13px',
        colors: 'rgb(161, 172, 184)',
      }
    },
    axisTicks: {
      show: false
    },
    axisBorder: {
      show: false
    }
  },
  yaxis: {
    labels: {
      style: {
        fontSize: '13px',
        colors: 'rgb(161, 172, 184)',
      },
      formatter: function (value) {
        return value.toFixed(0);
      }
    }
  },
  grid: {
    borderColor: '#DCE1E9',
    padding: {
      top: 0,
      bottom: -8,
      left: 20,
      right: 20
    }
  },
  legend: {
    show: false
  },
// Dentro do options do gráfico
options: {
  // ...
  tooltip: {
    x: {
      show: false
    },
    formatter: function (series, { dataPointIndex }) {
      return 'Total de Entregas: ' + series[0].data[dataPointIndex] + 'FDF';
    },
    style: {
      colors: '#000000' // Define a cor do texto para preto
    }
  }
}



};

if (typeof totalRevenueChartEl !== 'undefined' && totalRevenueChartEl !== null) {
  const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
  totalRevenueChart.render();
}


 </script>

        


                      </div>
                      <div class="col-md-4">
                        <div class="card-body">
                          <div class="text-center">
                           

                          </div>
                        </div><br>
                       

                            
                        <div class="text-center fw-semibold pt-3 mb-2">Mensal aos anteriores</div>

                        
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Total Revenue -->
                <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                  <div class="row">
                    <div class="col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/cc-warning.png" alt="Credit Card" class="rounded" />
                            </div>
                            </div>
                          <span class="fw-semibold d-block mb-1">Devido hoje</span>
                          <h4 style="color: #ea1d2c !important" class="card-title text-nowrap mb-2">-R$ 
                            <?php echo number_format($totalTaxaEntrega, 2, ",", "."); ?></h4>
                           
                         
                        </div>
                      </div>
                    </div>
                    <div class="col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded" />
                            </div>
                            </div>
                          <span class="fw-semibold d-block mb-1">Em rota</span>
                          <h4 class="card-title mb-2"><?php echo ($totalEmRota > 0) ? ($totalEmRota == 1 ? "1 entrega" : $totalEmRota . " entregas") : "nenhuma"; ?></h4>
                          
                        </div>
                      </div>
                    </div>
                    
                      <?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta SQL para obter os dados das entregas nos últimos 6 meses
$sql = "SELECT DATE_FORMAT(data_hora, '%Y-%m') AS mes, COUNT(*) AS total_entregas FROM entregas WHERE restaurante_id='$restauranteId' AND data_hora >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 6 MONTH), '%Y-%m-01') GROUP BY mes";
$result = $conn->query($sql);

// Array para armazenar o total de entregas de cada mês
$totalEntregasMeses = array();

// Inicializa o array com os últimos 6 meses
for ($i = 5; $i >= 0; $i--) {
    $mes = date('Y-m', strtotime("-$i months"));
    $totalEntregasMeses[$mes] = 0;
}

// Processa os resultados da consulta
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mes = $row['mes'];
        $totalEntregas = $row['total_entregas'];

        // Verifica se o mês está nos últimos 6 meses
        if (isset($totalEntregasMeses[$mes])) {
            // Armazena o total de entregas do mês
            $totalEntregasMeses[$mes] = $totalEntregas;
        }
    }
}

// Array para armazenar os nomes dos meses
$nomesMeses = array(
    '01' => 'Janeiro',
    '02' => 'Fevereiro',
    '03' => 'Março',
    '04' => 'Abril',
    '05' => 'Maio',
    '06' => 'Junho',
    '07' => 'Julho',
    '08' => 'Agosto',
    '09' => 'Setembro',
    '10' => 'Outubro',
    '11' => 'Novembro',
    '12' => 'Dezembro'
);

// Obtém o mês atual
$mesAtual = date('Y-m');

// Calcula o total de entregas do mês atual
$totalEntregasMesAtual = 0;
if (isset($totalEntregasMeses[$mesAtual])) {
    $totalEntregasMesAtual = $totalEntregasMeses[$mesAtual];
}

// Calcula o crescimento em porcentagem em relação aos meses anteriores
$porcentagensCrescimento = array();
$meses = array_keys($totalEntregasMeses);
$totalMeses = count($meses);

for ($i = 1; $i < $totalMeses; $i++) {
    $mesAnterior = $meses[$i - 1];
    $mesAtual = $meses[$i];

    $totalEntregasMesAnterior = $totalEntregasMeses[$mesAnterior];
    $totalEntregasMesAtual = $totalEntregasMeses[$mesAtual];

    $porcentagemCrescimento = 0;

    if ($totalEntregasMesAnterior != 0) {
        $porcentagemCrescimento = (($totalEntregasMesAtual - $totalEntregasMesAnterior) / $totalEntregasMesAnterior) * 100;
    }

    $porcentagensCrescimento[] = $porcentagemCrescimento;
}

// Converte o array de meses para exibir os nomes
$totalEntregasMesesNomes = array();
foreach ($totalEntregasMeses as $mes => $totalEntregas) {
    $nomeMes = $nomesMeses[date('m', strtotime($mes))];
    $totalEntregasMesesNomes[$nomeMes] = $totalEntregas;
}

// Codifica os arrays como JSON
$totalEntregasMesesJSON = json_encode(array_values($totalEntregasMesesNomes));
$porcentagensCrescimentoJSON = json_encode(array_values($porcentagensCrescimento));
?>
   
                    <div class="col-12 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                              <div class="card-title">
                                <h5 class="text-nowrap mb-2">Relatório Mensal</h5>
                                <span class="badge bg-label-warning rounded-pill">Ano <?php echo date('Y'); ?></span>

                              </div>
                              <div class="mt-sm-auto">
                                <small class="text-success text-nowrap fw-semibold"
                                  ><i class="bx bx-chevron-up"></i> <?php echo round($porcentagemCrescimento, 2); ?>%</small
                                >
                                <h4 id="totalEntregasMesAtual" class="mb-0"><?php echo ($totalEntregasMesAtual > 0) ? ($totalEntregasMesAtual == 1 ? "1 entrega" : $totalEntregasMesAtual . " entregas") : "nenhuma"; ?></h4>
                              </div>
                            </div>


<!-- Gráfico de Entregas por Mês -->
<div id="totalEntregasChart" class="px-2"></div>

<script type="text/javascript">
const totalEntregasChartEl = document.querySelector('#totalEntregasChart');

const totalEntregasChartOptions = {
  series: [
    {
      name: 'Entregas',
      data: JSON.parse('<?php echo $totalEntregasMesesJSON; ?>')
    }
  ],
  chart: {
    height: 110,
    type: 'line',
    toolbar: {
      show: false
    },
    sparkline: {
      enabled: true
    }
  },
  stroke: {
    curve: 'smooth'
  },
  colors: ['#ea1d2c'],
  dataLabels: {
    enabled: false
  },
  xaxis: {
    categories: <?php echo json_encode(array_keys($totalEntregasMesesNomes)); ?>,
    labels: {
      show: false
    }
  },
  yaxis: {
    labels: {
      show: false
    }
  },
  grid: {
    show: false
  },
  legend: {
    show: false
  },
  tooltip: {
    x: {
      format: 'MMM'
    },
    formatter: function (value, { series, seriesIndex, dataPointIndex, w }) {
      const nomeMes = w.globals.labels[dataPointIndex];
      return nomeMes + ': ' + value;
    }
  }
};

if (typeof totalEntregasChartEl !== 'undefined' && totalEntregasChartEl !== null) {
  const totalEntregasChart = new ApexCharts(totalEntregasChartEl, totalEntregasChartOptions);
  totalEntregasChart.render();
}
</script>





                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-12">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Entregas do dia</h5>
                        <small class="text-muted"><?php echo $totalEntregasHoje; ?> entregas</small>
                      </div>
                      </div>
                    <div class="card-body">
                      <div class="table-responsive text-nowrap">
  <table class="table">
    <thead>
      <tr>
        <th>N° Entrega</th>
        <th>Endereço do Cliente</th>
        <th>Distância</th>
        <th>Taxa de Entrega</th>
        <th>Hora</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody class="table-border-bottom-0">
      <?php
if ($resultEntregas->num_rows > 0) {
  while ($rowEntrega = $resultEntregas->fetch_assoc()) {
    ?>
    <tr>
      <td><?php echo $rowEntrega['id']; ?></td>
      <td><?php echo $rowEntrega['endereco_cliente']; ?></td>
      <td><?php echo number_format($rowEntrega['distancia'], 1) . " Km"; ?></td>
      <td>R$ <?php echo $rowEntrega['taxa_entrega']; ?></td>
      <td><?php echo date("H:i:s", strtotime($rowEntrega['data_hora'])); ?></td>
      <td><?php
        $status = $rowEntrega['status'];

        if ($status === 'aguardando_entregador') {
          echo '<span class="badge bg-label-warning me-1">Aguardando entregador</span>';
        } elseif ($status === 'em_rota') {
          echo '<span class="badge bg-label-info me-1">Em rota</span>';
        } elseif ($status === 'concluida'){
          echo '<span class="badge bg-label-success me-1">Concluída</span>';
        }
      ?></td>
    </tr>
    <?php
  }
} else {
  ?>
  <tr>
  <td colspan="6" class="text-center">Nenhuma entrega encontrada.</td>
</tr>
  <?php
}
?>

    </tbody>
  </table>
</div>
                    </div>
                  </div>
                </div>
                <!--/ Order Statistics -->

              </div>
            </div>
            <!-- / Content -->

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
                              O restaurante deve repassar os seguintes valores para continuar usando os nossos serviços. Caso seja mais de um dia efetue o pagamento separado.<br><br><strong id="resultadoRepasse"></strong><br>Escaneie o <strong>QRcode</strong> e efetue o pagamento.
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
