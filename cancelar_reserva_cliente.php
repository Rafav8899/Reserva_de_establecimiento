<?php
header('Content-Type: application/json');
require __DIR__ . '/config/db_conection.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    echo json_encode(['success' => false, 'error' => 'Token inválido']);
    exit;
}

try {
    // Cambiamos el estado a cancelada usando el UUID
    $sql = "UPDATE reservas SET estado = 'cancelada' WHERE uuid = :token";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([':token' => $token]);

    if ($success && $stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró la reserva o ya estaba cancelada.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}