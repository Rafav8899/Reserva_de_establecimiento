<?php
header('Content-Type: application/json');
require __DIR__ . '/config/db_conection.php'; // Tu conexión PDO

$dni = $_GET['dni'] ?? '';

if (empty($dni)) {
    echo json_encode(['existe' => false]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT nombre, apellido, gmail, celular, domicilio, residencia, nacionalidad, dni_frente, dni_dorso FROM clientes WHERE TRIM(dni) = TRIM(:dni) LIMIT 1");
    $stmt->execute([':dni' => $dni]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        echo json_encode([
            'existe' => true,
            'datos'  => $cliente
        ]);
    } else {
        echo json_encode(['existe' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}