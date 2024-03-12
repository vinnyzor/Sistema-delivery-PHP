<?php
require_once 'db_connection.php';

// Atualiza o valor do last_login para a data e hora atual
$sql = "UPDATE admins SET last_login = NOW()";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "Último login atualizado com sucesso!";
} else {
    echo "Ocorreu um erro ao atualizar o último login: " . mysqli_error($conn);
}
?>
