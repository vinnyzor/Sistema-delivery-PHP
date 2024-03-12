<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $entregaId = $_GET['entregaId'];

  // Realize as consultas no banco de dados para obter os dados do entregador
  $sql = "SELECT entregadores.nome, entregadores.documento_moto, entregadores.nivel_rank,entregadores.num_entregas,entregadores.imagem_perfil, entregas.data_hora, entregas.distancia
          FROM entregadores
          INNER JOIN entregas ON entregadores.id = entregas.entregador
          WHERE entregas.id = '$entregaId'";

  
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Obtenha a distância e calcule o tempo em minutos
    $distancia = $row['distancia'];
    $tempo = ceil($distancia * 4); // Calcula o tempo arredondando para cima

    // Obtenha a hora formatada adicionando o tempo calculado à hora original
    $dataHora = $row['data_hora'];
    $horaFormatada = date('H:i', strtotime($dataHora . '+' . $tempo . ' minutes'));

    // Adicione a hora formatada ao array $entregador
    $entregador = array(
      'nome' => $row['nome'],
      'placaMoto' => $row['documento_moto'],
      'numEntregas' => $row['num_entregas'],
      'ranking' => $row['nivel_rank'],
      'imagem_perfil' => $row['imagem_perfil'],
      'horaFormatada' => $horaFormatada
    );

    // Retorne os dados do entregador como uma resposta JSON
    header('Content-Type: application/json');
    echo json_encode($entregador);
  } else {
    // Se não encontrar dados do entregador, retorne uma resposta JSON com uma propriedade "error"
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Dados do entregador não encontrados'));
  }
}
?>
