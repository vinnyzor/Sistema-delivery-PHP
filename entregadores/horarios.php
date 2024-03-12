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

    <title>Solicitar Entrega | Fast Delivery</title>

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
            
           
 <li class="menu-item active">
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
 <style>
              table {
            width: 100%;
            text-align: center;
            font-weight: 600;
        }

        th, td {
         
            padding: 8px;
            text-align: center;
        }
        
        .horario-cell {text-align: center;
            width: calc(100% / 7);
        }
    </style>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">

                            
              <div class="card mb-4">
                <div style="margin-bottom:-30px;display: flex;
        justify-content: space-between;"><h5 class="card-header">Seus horários</h5></div>
                <center><div class="card-body d-flex justify-content-between">

                  <div class="col-lg-12 grid-margin stretch-card d-flex justify-content-center">
                <div class="card">
                 
                  <div class="card-body">

                   
                    
                     
       
                    <table class="table table-hover ">
                      <tbody>
                        <tr>
                          


 <!-- Botões para os dias da semana -->

 <td class="horario-cell"><button class="btn btn-primary" onclick="buscarHorarios('Segunda-feira')">Segunda</button> </td>
 <td class="horario-cell"><button class="btn btn-primary" onclick="buscarHorarios('Terca-feira')">Terça</button></td>
 <td class="horario-cell"><button class="btn btn-primary" onclick="buscarHorarios('Quarta-feira')">Quarta</button></td>
 <td class="horario-cell"><button class="btn btn-primary" onclick="buscarHorarios('Quinta-feira')">Quinta</button></td>
 <td class="horario-cell"><button class="btn btn-primary" onclick="buscarHorarios('Sexta-feira')">Sexta</button></td>
 <td class="horario-cell"><button class="btn btn-primary" onclick="buscarHorarios('Sabado')">Sábado</button></td>
 <td class="horario-cell"><button class="btn btn-primary" onclick="buscarHorarios('Domingo')">Domingo</button></td>



                        </tr>
                      </tbody>
                      <tbody>
                        <tr>
                          
                         
                        </tr>
                      </tbody>
                    </table>

                       <span style='text-align: center;' id="diassemana"></span>
                    
                  </div>
                </div>
              </div>
                  
                </div></center>
              </div>


              
            </div>
            <!-- / Content -->

                    

                      

                        <!-- Modal -->
                        <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Alterar Hórario (<span id="divDiaSemana"></span>)</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body" style="display: flex; justify-content: center;">
      <div style="display: flex; flex-direction: column; align-items: center;">
  <label><b>Abertura</b></label>
  <span style="margin: 5px;" id="divHorarios" class="horarios-container"></span>
</div>

  <div style="display: flex; flex-direction: column; align-items: center;">
     <label><b>Fechamento</b></label>
  <span style="margin: 5px;" id="divHorarioss" class="horarios-container"></span>
  </div>
</div>
                              <div class="modal-footer">
                                <span style="margin: 5px;" id="divHorariosss" class="horarios-container"></span>
                                <button type="button" id="salvar" class="btn btn-outline-secondary"><i class="bx bx-timer me-2"></i> Add Horário</button>
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

                        $(document).ready(function() {
            // Dias da semana
            var diasSemana = ["Segunda-feira", "Terca-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sabado", "Domingo"];

            // Faz a requisição AJAX para obter os horários
            $.ajax({
                url: "horarios/exibir_horarios.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    // Cria a tabela
                    var table = $("<table>");

                    // Corpo da tabela
                    var tbody = $("<tbody>");

                    // Cria uma única linha na tabela
                    var row = $("<tr>");

                    // Loop pelos dias da semana para criar as células
                    for (var i = 0; i < diasSemana.length; i++) {
                        var diaSemana = diasSemana[i];
                        var horarioEncontrado = false;

                        // Loop pelos registros de horários
                        for (var j = 0; j < data.length; j++) {
                            var horario = data[j];
                            if (horario.dia_semana === diaSemana) {
                                // Extrai apenas as horas e minutos do horário
                                var abertura = horario.horario_abertura.substring(0, 5);
                                var fechamento = horario.horario_fechamento.substring(0, 5);

                                // Cria a célula com os dados do horário
                                var td = $("<td>").addClass("horario-cell").text(abertura + " - " + fechamento);
                                row.append(td);
                                horarioEncontrado = true;
                                break;
                            }
                        }

                        if (!horarioEncontrado) {
                            // Cria uma célula vazia
                            var emptyTd = $("<td>").addClass("horario-cell").text("Loja fechada");
                            row.append(emptyTd);
                        }
                    }

                    tbody.append(row);
                    table.append(tbody);

                    // Adiciona a tabela à div "diassemana"
                    $("#diassemana").append(table);
                },
                error: function() {
                    alert("Erro ao carregar os horários.");
                }
            });
        });

function fecharModal() {
   var modal = document.getElementById('meuModal');
 // Exibir o modal
  modal.style.display = 'none';

}

// Função para buscar os horários no banco de dados e exibi-los em um modal
function buscarHorarios(diaSemana) {
  // Fazer uma requisição AJAX para buscar os horários no PHP
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'horarios/buscar_horarios.php?diaSemana=' + diaSemana, true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Resposta recebida com sucesso
      var horarios = JSON.parse(xhr.responseText);
      exibirModal(horarios);
    }
  };
  xhr.send();
}

// Função para formatar o horário no formato "hh:mm"
function formatarHorario(horario) {
  var horas = horario.slice(0, 2);
  var minutos = horario.slice(3, 5);
  return horas + ':' + minutos;
}
// Função para buscar os horários no banco de dados e exibi-los em um modal
function buscarHorarios(diaSemana) {
  // Fazer uma requisição AJAX para buscar os horários no PHP
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'horarios/buscar_horarios.php?diaSemana=' + diaSemana, true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Resposta recebida com sucesso
      var horarios = JSON.parse(xhr.responseText);
      exibirModal(horarios);
    }
  };
  xhr.send();
}

// Função para formatar o horário no formato "hh:mm"
function formatarHorario(horario) {
  var horas = horario.slice(0, 2);
  var minutos = horario.slice(3, 5);
  return horas + ':' + minutos;
}

// Função para exibir os horários em um modal
function exibirModal(horarios) {
  var modal = document.getElementById('modalCenter');
  var diaSemana = document.getElementById('divDiaSemana');
  var divHorarios = document.getElementById('divHorarios');
  divHorarios.innerHTML = '';
  divHorarioss.innerHTML = '';
    divHorariosss.innerHTML = '';
  horarios.forEach(function(horario) {
    diaSemana.textContent = horario.dia_semana;
    var inputAbertura = document.createElement('input');
    inputAbertura.type = 'time';
    inputAbertura.value = formatarHorario(horario.horario_abertura);
    inputAbertura.classList.add('form-control');
    inputAbertura.id = 'horarioAbertura';

    var inputFechamento = document.createElement('input');
    inputFechamento.type = 'time';
    inputFechamento.value = formatarHorario(horario.horario_fechamento);
    inputFechamento.classList.add('form-control');
    inputFechamento.id = 'horarioFechamento';

    var divHorario1 = document.createElement('span');
    var divHorario2 = document.createElement('span');

    divHorario1.appendChild(inputAbertura);
    divHorario2.appendChild(inputFechamento);

    divHorarios.appendChild(divHorario1);
    divHorarioss.appendChild(divHorario2);
  });

  // Criação do botão de exclusão
  var buttonExcluir = document.createElement('button');
  buttonExcluir.textContent = 'Excluir Dia';
  buttonExcluir.classList.add('btn', 'btn-danger');
  buttonExcluir.onclick = function() {
    confirmarExclusao(diaSemana.textContent);
     alert("Horário removido com sucesso!");
    location.reload();
  };

  // Adiciona o botão de exclusão à divHorarios
  divHorariosss.appendChild(buttonExcluir);

  var modalElement = document.getElementById('modalCenter');
      var modal = new bootstrap.Modal(modalElement);
       modal.show();

  var modalDialog = modal.querySelector('.modal-dialog');
  if (modalDialog) {
    modalDialog.classList.remove('fade');
  }
}

// Função para confirmar a exclusão do dia
function confirmarExclusao(diaExcluir) {
  if (confirm('Tem certeza que deseja excluir o dia ' + diaExcluir + '?')) {
    // Requisição AJAX para excluir o dia
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'horarios/excluir_dia.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.success) {
            // Dia excluído com sucesso, faça qualquer ação adicional necessária
            alert(response.message);

            // Atualize a página ou realize outras ações necessárias
          } else {
            // Ocorreu um erro ao excluir o dia
            alert(response.message);
          }
        } else {
          // Ocorreu um erro na requisição AJAX
          alert('Erro na requisição AJAX');
        }
      }
    };
    xhr.send('diaSemana=' + encodeURIComponent(diaExcluir));
  }
}




$(document).on("click", "#salvar", function() {
  var diaSemana = $("#divDiaSemana").text();
  var horarioAbertura = $("#horarioAbertura").val();
  var horarioFechamento = $("#horarioFechamento").val();
  
  $.ajax({
    url: "horarios/configurar_horario.php",
    type: "POST",
    data: {
      diaSemana: diaSemana,
      horarioAbertura: horarioAbertura,
      horarioFechamento: horarioFechamento
    },
    success: function(response) {
      alert("Horário de funcionamento configurado com sucesso!");
      location.reload();
    },
    error: function(xhr, status, error) {
      console.log(error);
    }
  });
});

       $(document).ready(function() {
            // Requisição AJAX para obter o horário de funcionamento
            $.ajax({
                url: "horarios/exibe_horario.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.mensagem) {
                        // Exibir a mensagem "Loja fechada" se não houver horário de funcionamento encontrado
                        $("#horario-funcionamento").text(data.mensagem);
                    } else {
                        // Exibir o horário de funcionamento no elemento com o ID "horario-funcionamento"
                        $("#horario-funcionamento").text("Aberto das " + data.horario_abertura + " às " + data.horario_fechamento + ".");
                    }
                },
                error: function() {
                    // Em caso de erro na requisição AJAX
                    $("#horario-funcionamento").text("Erro ao obter o horário de funcionamento.");
                }
            });
        });

    </script>
  </body>
</html>
