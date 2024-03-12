<?php
// Verifique se o ano foi fornecido na consulta GET
if (isset($_GET['ano'])) {
    $anoSelecionado = $_GET['ano'];

    // Consulta SQL para obter os novos dados para o ano selecionado
    $query = "SELECT MONTH(data_hora) AS mes, COUNT(*) AS total_entregas FROM entregas WHERE YEAR(data_hora) = $anoSelecionado GROUP BY mes";
    $result = mysqli_query($conn, $query);

    $meses = array();
    $totalEntregas = array_fill(1, 12, 0); // Inicializa o array com 12 meses, todos com valor zero

    // Processa os resultados da consulta
    while ($row = mysqli_fetch_assoc($result)) {
        $mes = $row['mes'];
        $total = $row['total_entregas'];

        // Atualiza o valor do total de entregas para o mês correspondente
        $totalEntregas[$mes] = $total;

        // Adiciona o nome do mês ao array de meses
        $meses[] = date('M', mktime(0, 0, 0, $mes, 1));
    }

    // Retorne os dados como uma resposta JSON
    $response = array(
        'meses' => $meses,
        'totalEntregas' => array_values($totalEntregas)
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
