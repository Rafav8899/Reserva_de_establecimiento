<?php

require 'db_conection.php';

$stmt = $pdo->query("SELECT NOW() as fecha_actual");

$result = $stmt->fetch();

echo "Conectado correctamente. Fecha del servidor: " . $result['fecha_actual'];
echo "<p></p>";

?>