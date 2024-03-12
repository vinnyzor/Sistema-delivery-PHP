<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "delivery";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém a data da dívida informada
    $dataDivida = $_POST["data_divida"];    
    // Consulta as entregas do dia informado agrupadas por restaurante e soma as taxas de entrega
    $query = "SELECT restaurante_id, SUM(taxa_entrega) AS total_taxa_entrega FROM entregas WHERE DATE_FORMAT(data_hora, '%Y-%m-%d') = '$dataDivida' GROUP BY restaurante_id";
    $result = $conn->query($query);

    if ($result) {
        // Verifica se já existe uma dívida para o mesmo restaurante na mesma data
        while ($row = $result->fetch_assoc()) {
            $restauranteId = $row["restaurante_id"];
            $totalTaxaEntrega = $row["total_taxa_entrega"];

            $verificaQuery = "SELECT * FROM financas WHERE restaurante_id = $restauranteId AND dia_divida = '$dataDivida'";
            $verificaResult = $conn->query($verificaQuery);
            
            if ($verificaResult->num_rows > 0) {
              // Se já existir uma dívida no mesmo dia e para o mesmo restaurante, apenas atualiza o valor devido
              $updateQuery = "UPDATE financas SET valor_devido = $totalTaxaEntrega WHERE restaurante_id = $restauranteId AND dia_divida = '$dataDivida'";
              if (!$conn->query($updateQuery)) {
                  echo "Erro ao atualizar na tabela financas: " . $conn->error;
              }
            } else {
              // Se não existir uma dívida no mesmo dia e para o mesmo restaurante, insere um novo registro
              $insertQuery = "INSERT INTO financas (restaurante_id, divida, valor_devido, dia_divida, status_divida) VALUES ($restauranteId, $totalTaxaEntrega, $totalTaxaEntrega, '$dataDivida', 'em_aberto')";
              if (!$conn->query($insertQuery)) {
                  echo "Erro ao inserir na tabela financas: " . $conn->error;
              }
            }
        }
        
        echo "Dívidas calculadas com sucesso!";
    } else {
        echo "Erro na consulta: " . $conn->error;
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>