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
  echo "<script>
    var startMarker = L.marker([$latitude, $longitude]).addTo(map1);
  </script>";

  $dividasSql = "SELECT * FROM financas WHERE restaurante_id = $restauranteId";
  $dividasResult = $conn->query($dividasSql);

  echo '<table class="table">
          <thead>
            <tr class="text-nowrap">
              <th>N° dívida</th>
              <th>Valor devido</th>
              <th>Dia dívida</th>
              <th>Status</th>
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
            <td>$valorDevido</td>
            <td>$dataFormatada</td>
            <td>$statusDivida</td>
          </tr>";
  }

  echo '</tbody>
    </table>';

} else {
  header("Location: login.php");
  exit();
}
?>