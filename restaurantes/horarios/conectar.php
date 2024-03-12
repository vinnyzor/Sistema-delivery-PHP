<?php
session_start();
$servername = "177.234.152.18";
$username = "eyeingde_admin_plataforma";
$password = "Vinicius1234#";
$dbname = "eyeingde_ebamais";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha ao conectar com o banco de dados: " . $conn->connect_error);
}

mysqli_set_charset($conn, "utf8mb4");

$coluna = $_POST["coluna"];
$valor = $_POST["valor"];

$nome_coluna = array(
  'acai_250ml' => 'Açaís de 250ml',
  'acai_350ml' => 'Açaís de 350ml',
  'acai_500ml' => 'Açaís de 500ml',
  'acai_700ml' => 'Açaís de 700ml',
  'granola' => 'Granola',
  'amendoim' => 'Amendoim',
  'pacoca' => 'Paçoca',
  'neston' => 'Neston',
  'c_frutas' => 'Cereal de Frutas',
  'farinha_lactea' => 'Farinha lactea',
  'flocos_arroz' => 'Flocos de Arroz',
  'leite_condensado_50ml' => 'Leite condensado 50ml',
  'leite_condensado_100ml' => 'Leite condensado 100ml',
);

$sql = "SELECT $coluna FROM estoque WHERE id = 1";
$resultado = $conn->query($sql);
if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $valor_atual = $row[$coluna];
    if ($_POST['action'] === 'adicionar') {
        $novo_valor = $valor_atual + $valor;
        $mensagem = "Foi adicionado <strong>" . $valor . " " . ($nome_coluna[$coluna] ?? $coluna) . "</strong> com sucesso!";
    } else {
        $novo_valor = $valor_atual - $valor;
        $mensagem = "Foi removido <strong>" . $valor . " " . ($nome_coluna[$coluna] ?? $coluna) . "</strong> com sucesso!";
    }
    
    $sql = "UPDATE estoque SET $coluna = $novo_valor WHERE id = 1";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['mensagem'] = $mensagem;
        $_SESSION['sucesso'] = ($_POST['action'] === 'adicionar') ? true : false;
        header('Location: estoque.php');
    } else {
        $_SESSION['mensagem'] = "Erro ao adicionar/remover estoque: " . $conn->error;
        $_SESSION['sucesso'] = false;
        header('Location: estoque.php');
    }
} else {
    $_SESSION['mensagem'] = "Escolha um item para atualizar seu estoque.";
    $_SESSION['sucesso'] = false;
    header('Location: estoque.php');
}

$conn->close();
?>