
<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}



?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Horário de Funcionamento</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
       <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

  
    <link rel="stylesheet" href="../../css/style.css" <!-- End layout styles -->
       <link rel="shortcut icon" href="../../images/logon.png" />
  

  </head>
  <body>
    <style type="text/css">
      
.mensagem {
  position: fixed;
  font-size: 20px;
  top: 10px;
  left: 0;

  right: 0;
  margin-left: auto;
  margin-right: auto;
  z-index: 999999999;
  max-width: 600px;
}

#mensagem_estoque {
  position: fixed;
  font-size: 20px;
  bottom: 10px;
  right: 10px;
  z-index: 999999999;
  max-width: 300px;
}

    </style>
 <style>
              table {
            width: 100%;
            border-collapse: collapse;
            font-weight: 600;
        }

        th, td {
         border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 8px;
            text-align: center;
        }
        
        .horario-cell {
            width: calc(100% / 7);
        }
    </style>
        <script>
                        $(document).ready(function() {
            // Dias da semana
            var diasSemana = ["Segunda-feira", "Terca-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sabado", "Domingo"];

            // Faz a requisição AJAX para obter os horários
            $.ajax({
                url: "exibir_horarios.php",
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
  xhr.open('GET', 'buscar_horarios.php?diaSemana=' + diaSemana, true);
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
  xhr.open('GET', 'buscar_horarios.php?diaSemana=' + diaSemana, true);
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
  var modal = document.getElementById('meuModal');
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

  modal.style.display = 'block';
  modal.classList.add('show');

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
    xhr.open('POST', 'excluir_dia.php', true);
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
    url: "configurar_horario.php",
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
                url: "exibe_horario.php",
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
    <div class="container-scroller">
      <!-- partial:../../partials/_navbar.html -->
                <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
       
        <div class="navbar-menu-wrapper d-flex align-items-center flex-grow-1">
          <img style="margin-left: 70px;width: 70px;" src="../../images/log.png" alt="logo" /> <h5 class="mb-0 font-weight-medium d-none d-lg-flex">Painel Eba+ Açaí!</h5>
          <ul class="navbar-nav navbar-nav-right ml-auto">
             <!-- Botão para abrir o modal -->



<a href="/ebamais/pages/vendas/adicionar_venda.php"><button style="font-size: 13px; padding: 15px; margin-right: 100px;"  class="btn btn-success btn-fw">Adicionar Venda</button></a>


          

    <style type="text/css">  input::placeholder {
  color: #4f5051 !important;
}</style>
  
   
      <input style="border-radius: 4px;border:1px solid #1bdbe0; margin-right: 5px;color:#4f5051 !important"  id="nomeCliente" type="search" class="form-control" placeholder="Buscar cliente" title="Buscar cliente">
       <button type="button" class="btn btn-primary fazQuery"><i class="icon-magnifier"></i></button>
  



         

            <li style="margin-left: 70px;" class="nav-item"><a href="/ebamais/pages/vendas/vendas.php" class="nav-link"><i class="icon-basket-loaded"></i></a></li>
            <li class="nav-item"><a href="/ebamais/" class="nav-link"><i class="icon-chart"></i></a></li>
            
            
          
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
          </button>
        </div>
      </nav>
      
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
               <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
                       
             <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="profile-image">
                  <img class="img-xs rounded-circle" src="../../images/faces/<?php echo $imagem; ?>" alt="profile image">
                  <div class="dot-indicator bg-success"></div>
                </div>
                <div class="text-wrapper">
                  <p class="profile-name"><?php echo $usuario['nome']; ?></p>
                  <p class="designation">Administrador</p>
                </div>
                
              </a>
            </li>
           
            <li class="nav-item nav-category">
              <span class="nav-link">Dashboard</span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://localhost/ebamais/">
                <span class="menu-title">Resumos</span>
                <i class="icon-screen-desktop menu-icon"></i>
              </a>
            </li>
            <li class="nav-item nav-category"><span class="nav-link">Pedidos</span></li>
           
            
            <li class="nav-item">
              <a class="nav-link" href="http://localhost/ebamais/pages/pedidos/index.php">
                <span style="font-weight: 600; color:#1bdbe0" class="menu-title">Pedidos Plataforma</span>
                <i class="icon-people menu-icon"></i>
              </a>
            </li>
            <li class="nav-item nav-category"><span class="nav-link">Clientes</span></li>
           
            
            <li class="nav-item">
              <a class="nav-link" href="http://localhost/ebamais/pages/clientes/clientes.php">
                <span class="menu-title">Clientes Eba+ Açaí</span>
                <i class="icon-people menu-icon"></i>
              </a>
            </li>
           
           
            <li class="nav-item nav-category"><span class="nav-link">Vendas</span></li>
             <li class="nav-item">
              <a class="nav-link" href="http://localhost/ebamais/pages/vendas/adicionar_venda.php">
                <span class="menu-title">Adicionar Venda</span>
                <i class="icon-plus menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://localhost/ebamais/pages/vendas/vendas.php">
                <span class="menu-title">Nossas Vendas</span>
                <i class="icon-basket-loaded menu-icon"></i>
              </a>
            </li>

             <li class="nav-item nav-category"><span class="nav-link">Horários</span></li>
             <li class="nav-item">
              <a class="nav-link"href="http://localhost/ebamais/pages/horarios/horarios.php">
                <span class="menu-title">Horário funcionamento</span>
                <i class="icon-clock menu-icon"></i>
              </a>
            </li>

             <li class="nav-item nav-category"><span class="nav-link">Relatórios</span></li>
             <li class="nav-item">
             <a class="nav-link"href="<?php
$url = "http://localhost/ebamais/pages/relatorios/relatorios.php?data_inicio=" . date('Y-m-d') . "&data_fim=" . date('Y-m-d', strtotime('+1 day'));
echo $url;
?>">
                <span class="menu-title">Relatorios</span>
                <i class="icon-pie-chart menu-icon"></i>
              </a>
            </li>

             <li class="nav-item nav-category"><span class="nav-link">Estoque</span></li>
             <li class="nav-item">
              <a class="nav-link"href="http://localhost/ebamais/pages/estoque/estoque.php">
                <span class="menu-title">Controle de Estoque</span>
                <i class="icon-layers menu-icon"></i>
              </a>
            </li>
           
           
            <li class="nav-item pro-upgrade">
              <span class="nav-link">
                <a class="btn btn-block px-0 btn-rounded btn-upgrade" href="http://localhost/ebamais/logout.php" target="_blank"> <i class="icon-share-alt mx-2"></i> Logout</a>
              </span>
            </li>
          </ul>
        </nav>


        <!-- partial -->
        <div   class="main-panel">
          <div class="content-wrapper">
          


            <!-- Quick Action Toolbar Starts-->
            <div class="row quick-action-toolbar">
              <div class="col-md-12 grid-margin">
                <div class="card">
                  <div style=" display: flex;justify-content: center;align-items: center;" class="card-header d-block d-md-flex text-center">
  <h3>Horário de Funcionamento</h3>
                  </div>



                </div>
              </div>
            </div>

<!-- Modal -->
<div class="modal fade" id="meuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Alterar Hórario (<span id="divDiaSemana"></span>)</h5>
        <button style="border:none" type="button" data-bs-dismiss="modal"  onclick="fecharModal()" aria-label="Close"><img style="width: 30px;" src="close.png"></button>
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
        <button type="button" id="salvar" class="btn btn-success">Salvar Horário</button>
      </div>
    </div>
  </div>
</div>


            <div class="row">
              <?php if (isset($mensagem_estoque_baixo)) : ?>
    <div id="mensagem_estoque" class="alert alert-danger" role="alert">
        <?php echo $mensagem_estoque_baixo; ?>
    </div>
<?php endif; ?>
              <div class="col-lg-12 grid-margin stretch-card d-flex justify-content-center">
                <div class="card">
                 
                  <div class="card-body">

                   
                    
                     
       
                    <table class="table table-hover ">
                      <tbody>
                        <tr>
                          


 <!-- Botões para os dias da semana -->

 <td class="horario-cell"><button style="background-color: #f7711f; border:none" class="btn btn-primary" onclick="buscarHorarios('Segunda-feira')">Segunda</button> </td>
 <td class="horario-cell"><button style="background-color: #1bdbe0; border:none" class="btn btn-primary" onclick="buscarHorarios('Terca-feira')">Terça</button></td>
 <td class="horario-cell"><button style="background-color: #ff4d6b; border:none" class="btn btn-primary" onclick="buscarHorarios('Quarta-feira')">Quarta</button></td>
 <td class="horario-cell"><button style="background-color: #2cb430; border:none" class="btn btn-primary" onclick="buscarHorarios('Quinta-feira')">Quinta</button></td>
 <td class="horario-cell"><button style="background-color: #e13deb; border:none" class="btn btn-primary" onclick="buscarHorarios('Sexta-feira')">Sexta</button></td>
 <td class="horario-cell"><button style="background-color: #f9bf50; border:none" class="btn btn-primary" onclick="buscarHorarios('Sabado')">Sábado</button></td>
 <td class="horario-cell"><button style="background-color: #852838; border:none" class="btn btn-primary" onclick="buscarHorarios('Domingo')">Domingo</button></td>



                        </tr>
                      </tbody>
                      <tbody>
                        <tr>
                          
                         
                        </tr>
                      </tbody>
                    </table>

                       <span id="diassemana"></span>
                    
                  </div>
                </div>
              </div>

                             

                    

                         
              
              
             



            </div>
          </div>

<?php if (isset($_SESSION['mensagem'])): ?>
  <div id="mensagem" class="mensagem">
    <center><div class="alert <?php echo ($_SESSION['sucesso']) ? 'alert-success' : 'alert-danger'; ?>">
      <?php echo $_SESSION['mensagem']; ?>
    </div></center>
  </div>


  <script>
    // Define um temporizador para remover o alerta após 3 segundos
    setTimeout(function() {
      $('#mensagem').fadeOut('slow', function() {
        $(this).remove();
      });
    }, 2500);
  </script>

  <?php unset($_SESSION['mensagem']); ?>
  <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?>
          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block"></span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Plataforma desenvolvida por<a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank"> Vinícius Mendes</a> </span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <!-- End custom js for this page -->
    
  </body>
</html>

