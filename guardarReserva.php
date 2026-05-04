<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli("localhost", "root", "", "reserva_estudio");
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}


// Recibir datos del formulario
$fecha = $_POST["fecha"];
// $nombre = $_POST["nombre"];
$equiposArray = $_POST["equipos"] ?? [];

// 2. Convertirlo a un solo string para guardarlo en el LONGTEXT
// Ejemplo: "Camara Canon R6, Softbox"
$equiposString = implode(", ", $equiposArray);

// 3. Si el usuario no eligió nada, podrías poner un mensaje por defecto
if (empty($equiposString)) {
    $equiposString = "Ninguno / Solo estudio";
}

// LIMITE DE RESERVA POR DÍA
$limitePorDia = 2;


// Contar cuántas reservas hay en esa fecha
$check = $conn->prepare("SELECT COUNT(*) as total FROM reservas WHERE fecha = ?");
$check->bind_param("s", $fecha);
$check->execute();
$result = $check->get_result();
$row = $result->fetch_assoc();
$total = $row['total'] ?? 0;

if($total >= $limitePorDia){
    echo '<script>alert("Esta fecha ya alcazó el limite de reservas.");history.go(-1);</script>';
}

try{
    $stmt = $conn->prepare("INSERT INTO reservas (fecha, equipos) VALUES (?, ?)");
    $stmt->bind_param("ss", $fecha, $equipo);
    $stmt->execute();

    echo '<script>alert("Reserva bien hecha");window.location.href = "index.html";</script>';
}catch (mysqli_sql_exception $e){
    echo "Error en la base de datos: " . $e->getMessage();
}

$conn->close();

?>