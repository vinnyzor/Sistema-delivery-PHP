<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION['telefone'])) {
  header("Location: login.php");
  exit();
}

require_once 'conexao.php';

$telefone = $_SESSION['telefone'];

$sql = "SELECT * FROM entregadores WHERE telefone='$telefone'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();
  $entregadorId = $row['id'];
  $imagem_perfil = $row['imagem_perfil'];
  $cpf = $row['cpf'];
  $carteira_motorista = $row['num_carteira_motorista'];
  $placa_moto = $row['documento_moto'];
  $endereco = $row['endereco'];
  $email = $row['email'];
  $senha = $row['senha'];
  $telefone = $row['telefone'];
  $nome = $row['nome'];
  mb_internal_encoding('UTF-8');
  $nome_entregador_maiusculo = mb_strtoupper($nome, 'UTF-8');
} else {
  header("Location: login.php");
  exit();
}

$mensagem = '';

// Função para escrever no arquivo de log
function writeToLog($message, $entregadorId = null, $tipoAlteracao = null, $valorAntigo = null, $valorNovo = null) {
  $logDir = 'logs/'; // Diretório onde o arquivo de log será armazenado
  $logFile = $logDir . 'logs.txt';
  $timestamp = date('Y-m-d H:i:s');
  $logMessage = "\n[{$timestamp}] {$message}";

  if ($entregadorId !== null) {
    $logMessage .= " | Entregador ID: {$entregadorId}";
  }

  if ($tipoAlteracao !== null && $valorAntigo !== null && $valorNovo !== null) {
    $logMessage .= " | {$tipoAlteracao}: {$valorAntigo} -> {$valorNovo}";
  }

  $logMessage .= "\n";
  file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Verifica se há uma mensagem de sucesso na URL
if (isset($_GET['msg_image']) && $_GET['msg_image'] == 1) {
  $mensagem = "<div class='alert alert-success alert-dismissible' role='alert'>
                Imagem de perfil atualizada com sucesso!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
}

// Verifica se há uma mensagem de sucesso na URL
if (isset($_GET['msg_entregador']) && $_GET['msg_entregador'] == 1) {
  $mensagem = "<div class='alert alert-success alert-dismissible' role='alert'>
                  Informações do entregador atualizadas com sucesso!
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
  $imagem = $_FILES['imagem'];

  // Verifica o tamanho da imagem
  $maxFileSize = 2 * 1024 * 1024; // 2MB (ajuste o valor conforme necessário)
  if ($imagem['size'] > $maxFileSize) {
    $mensagem = "<div class='alert alert-danger alert-dismissible' role='alert'>
                  O tamanho máximo permitido para a imagem é de 2MB.
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
  } else {
    // Verifica o tipo de arquivo
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (in_array($imagem['type'], $allowedTypes)) {
      // Diretório onde a imagem será armazenada
      $uploadDir = 'images_perfil/';
      // Gera um nome único para a imagem
      $nomeArquivo = uniqid() . '.' . pathinfo($imagem['name'], PATHINFO_EXTENSION);
      $destino = $uploadDir . $nomeArquivo;

      // Obtém o caminho antigo da imagem no banco de dados
      $sql = "SELECT imagem_perfil FROM entregadores WHERE id='$entregadorId'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $valorAntigoImagem = $row['imagem_perfil'];

      // Move a imagem para o diretório de upload
      if (move_uploaded_file($imagem['tmp_name'], $destino)) {
        // Atualiza o caminho da imagem no banco de dados
        $sql = "UPDATE entregadores SET imagem_perfil='$destino' WHERE id='$entregadorId'";
        if ($conn->query($sql) === TRUE) {
          // Salva a mensagem no log
          writeToLog("Imagem de perfil atualizada com sucesso para o usuário: {$email}", $entregadorId, "Imagem de Perfil", $valorAntigoImagem, $destino);

          header("Location: configuracoes.php?msg_image=1");
          exit();
        } else {
          $mensagem = "<div class='alert alert-danger alert-dismissible' role='alert'>
                        Ocorreu um erro ao atualizar a imagem de perfil. Por favor, tente novamente mais tarde.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>";
        }
      } else {
        $mensagem = "<div class='alert alert-danger alert-dismissible' role='alert'>
                      Ocorreu um erro ao fazer o upload da imagem. Por favor, tente novamente mais tarde.
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
      }
    } else {
      $mensagem = "<div class='alert alert-danger alert-dismissible' role='alert'>
                    O formato de arquivo não é suportado. Por favor, selecione uma imagem JPEG ou PNG.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
  $novoNomeEstabelecimento = $_POST['nome'];
  $novoEmail = $_POST['email'];

  // Obtém os valores antigos das informações do usuário
  $sql = "SELECT nome, telefone, email FROM entregadores WHERE id='$entregadorId'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $valorAntigoNome = $row['nome'];
  $valorAntigoTelefone = $row['telefone'];
  $valorAntigoEmail = $row['email'];

  // Atualizar as informações do usuário no banco de dados
  $sql = "UPDATE entregadores SET nome='$novoNomeEstabelecimento', email='$novoEmail' WHERE id='$entregadorId'";
  if ($conn->query($sql) === TRUE) {
    $mensagem = "<div class='alert alert-success alert-dismissible' role='alert'>
                  Informações do restaurante atualizadas com sucesso!
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";

    // Salva as informações no log
    writeToLog("Nome Atualizado - ", $entregadorId, "Dados:", "Nome Antigo: {$valorAntigoNome}", "Novo Nome: {$novoNomeEstabelecimento}");
    writeToLog("Telefone Atualizado - ", $entregadorId, "Dados:", "Email Antigo: {$valorAntigoEmail}", "Novo Email: {$novoEmail}");
    header("Location: configuracoes.php?msg_entregador=1");
  } else {
    $mensagem = "<div class='alert alert-danger alert-dismissible' role='alert'>
                  Ocorreu um erro ao atualizar as informações do usuário. Por favor, tente novamente mais tarde.
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
  }
}

$conn->close();
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

    <title>Configurações | Fast Delivery</title>

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

           

             <li  class="menu-item">
              <a href="entregas.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-ul"></i>
                <div data-i18n="Analytics">Minhas Entregas</div>
              </a>
            </li>



             <li class="menu-item active">
              <a href="configuracoes.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Analytics">Meus dados</div>
              </a>
            </li>

            
             <li class="menu-item">
              <a href="lucros.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="Analytics">Meus lucros</div>
              </a>
            </li>

            

          

             <li class="menu-item">
              <a href="tickets.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-message-rounded-add"></i>
                <div data-i18n="Analytics">Tickets</div>
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
                 <div><strong><?php echo $nome_entregador_maiusculo; ?></strong></div>
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
               <strong><div id="data"></div></strong>&nbsp;-&nbsp;<strong><div id="hora"></div></strong>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                       
  <?php if (!empty($imagem_perfil)) : ?>
    <img  class="w-px-40 rounded-circle" src="<?php echo $imagem_perfil; ?>" alt="Foto de Perfil">

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
                              
  <?php if (!empty($imagem_perfil)) : ?>
    <img class="w-px-40 rounded-circle"  src="<?php echo $imagem_perfil; ?>" alt="Foto de Perfil">
  <?php else : ?>
    <img class="w-px-40 rounded-circle" src="images_perfil/padrao.png" alt="Foto de Perfil Padrão">
  <?php endif; ?>

                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block"><?php echo $nome_entregador_maiusculo; ?></span>
                            <small class="text-muted">Entregador</small>
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
                <div class="col-md-12">
                  <div class="card mb-4">
                    <div style="margin:10px">
                    <?php echo $mensagem; ?>
                    </div>
                    <h5 class="card-header">Detalhes de perfil</h5>
                    <!-- Account -->
                    <div class="card-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4">
                         <?php if (!empty($imagem_perfil)) : ?>
    <img class="d-block rounded"
                          height="100"
                          width="100"
                          id="uploadedAvatar" src="<?php echo $imagem_perfil; ?>" alt="Foto de Perfil">
  <?php else : ?>
    <img class="d-block rounded"
                          height="100"
                          width="100"
                          id="uploadedAvatar" src="images_perfil/padrao.png" alt="Foto de Perfil Padrão">
  <?php endif; ?>
                        

                        <div class="button-wrapper">
                          <form action="" method="POST" enctype="multipart/form-data">
                          <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Carregar nova imagem</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input
                              type="file" name="imagem"
                               id="upload"
                              class="account-file-input"
                              hidden
                              accept="image/png, image/jpeg"
                            />
                          </label>
                          
                           <input class="btn btn-primary me-2 mb-4" type="submit" value="Salvar imagem">
                          </form>
                          <p class="text-muted mb-0">JPG, GIF ou PNG permitidos. Tamanho máximo de 2MB</p>
                        </div>
                      </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="nome_estabelecimento" class="form-label">Seu nome</label>
                            <input
                              class="form-control"
                              type="text"
                              id="nome" name="nome"
                              value="<?php echo $nome; ?>"
                              autofocus
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="cpf" class="form-label">CPF</label>
                            <input disabled class="form-control" type="text" name="cpf" id="cpf" value="<?php echo $cpf; ?>" />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input
                              class="form-control"
                              type="text"
                              id="endereco"
                              disabled name="endereco"
                              value="<?php echo $endereco; ?>"
                              placeholder="john.doe@example.com"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="organization" class="form-label">Email</label>
                            <input
                              type="text"
                              class="form-control"
                              id="email" name="email"
                              value="<?php echo $email; ?>"
                            />
                          </div>
                           <div class="mb-3 col-md-6">
                            <label for="carteira_motorista" class="form-label">Carteira motorista</label>
                            <input
                              class="form-control"
                              type="text"
                              id="carteira_motorista"
                              disabled name="carteira_motorista"
                              value="<?php echo $carteira_motorista; ?>"
                              placeholder="john.doe@example.com"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="placa_moto" class="form-label">Placa moto</label>
                            <input disabled
                              type="text"
                              class="form-control"
                              id="placa_moto" name="placa_moto"
                              value="<?php echo $placa_moto; ?>"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="phoneNumber">Telefone</label>
                            <div class="input-group input-group-merge">
                              <span class="input-group-text">BR (+55)</span>
                              <input
                                type="text" disabled
                                id="telefone" name="telefone"
                                class="form-control"
                                value="<?php echo $telefone; ?>"
                                placeholder="202 555 0111"
                              />
                            </div>
                          </div>

                                          <div class="mb-3 col-md-6 form-password-toggle">
<label class="form-label" for="phoneNumber">Senha</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="senha"
                      class="form-control"
                      name="senha"
                      value="<?php echo $senha; ?>"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                          
                          
                          
                          
                          
                        </div>
                        <div class="mt-2">
                          <button type="submit" class="btn btn-primary me-2">Salvar alterações</button>
                          
                        </div>
                      </form>

                        
                    </div>
                    <!-- /Account -->
                  </div>
                  <div class="card">
                    <h5 class="card-header">Deletar conta</h5>
                    <div class="card-body">
                      <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                          <h6 class="alert-heading fw-bold mb-1">Tem certeza de que deseja excluir sua conta?</h6>
                          <p class="mb-0">Depois de excluir sua conta, não há como voltar atrás. Por favor, tenha certeza.</p>
                        </div>
                      </div>
                      <form id="formAccountDeactivation" onsubmit="return false">
                        <div class="form-check mb-3">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            name="accountActivation"
                            id="accountActivation"
                          />
                          <label class="form-check-label" for="accountActivation"
                            >Confirmo a desativação da minha conta</label
                          >
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account">Desativar conta</button>
                      </form>
                    </div>
                  </div>
                </div>
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
<script src="scripts.js"></script>
    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
