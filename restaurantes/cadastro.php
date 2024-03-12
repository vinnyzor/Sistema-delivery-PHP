<?php
session_start();
if (isset($_SESSION['email'])) {
  header("Location: dashboard.php");
  exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome_estabelecimento = $_POST['nome_estabelecimento'];
  $cnpj = $_POST['cnpj'];
  $endereco = $_POST['endereco'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  $telefone = $_POST['telefone'];

  $sql = "INSERT INTO restaurantes (nome_estabelecimento, cnpj, endereco, email, senha, telefone) VALUES ('$nome_estabelecimento', '$cnpj', '$endereco', '$email', '$senha', '$telefone')";

  if ($conn->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso! Aguarde a aprovação do cadastro.";
  } else {
    echo "Erro ao cadastrar o restaurante: " . $conn->error;
  }
}
?>
<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
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

    <title>Cadastro restaurante | Fast Delivery</title>

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
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                <img style="width: 40px;" src="../assets/img/icons/logo.png">
              </span>
                  <span class="app-brand-text demo text-body fw-bolder">Fast Delivery</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">O delivery rápido começa aqui 🚀</h4>
              <p class="mb-4">Torne o gerenciamento de entregas fácil e divertido!</p>

              <form id="formAuthentication" class="mb-3" action="cadastro.php" method="POST">
                <div class="mb-3">
                  <label for="nome_estabelecimento" class="form-label">Nome do Estabelecimento</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nome_estabelecimento"
                    name="nome_estabelecimento"
                    placeholder="Digite o nome do estabelecimento"
                    autofocus
                  />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" />
                </div>
                <div class="mb-3">
                  <label for="cnpj" class="form-label">CNPJ</label>
                  <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="Digite seu CNPJ" />
                </div>
                <div class="mb-3">
                  <label for="telefone" class="form-label">Telefone estabelecimento</label>
                  <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Digite seu telefone" />
                </div>
                <div class="mb-3">
                  <label for="endereco" class="form-label">Endereço estabelecimento</label>
                  <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Digite seu endereço" />
                </div>
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="senha">Escolha uma senha</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="senha"
                      class="form-control"
                      name="senha"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
                    <label class="form-check-label" for="terms-conditions">
                      Eu concordo
                      <a href="javascript:void(0);">política de privacidade & termos</a>
                    </label>
                  </div>
                </div>
                <button class="btn btn-primary d-grid w-100">Cadastrar restaurante</button>
              </form>

              <p class="text-center">
                <span>Já tem uma conta?</span>
                <a href="login.php">
                  <span>Faça login em vez disso</span>
                </a>
              </p>
            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>

    <!-- / Content -->

   

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

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
