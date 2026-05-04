<?php
header('Content-Type: application/json');
require __DIR__ . '/config/db_conection.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
    exit;
}

try {
    // Cambiamos el estado a 'cancelada'. 
    // Como tu calendario solo busca las 'confirmada', esta fecha se libera automáticamente.
    $sql = "UPDATE reservas SET estado = 'cancelada' WHERE id_reserva = :id";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([':id' => $id]);

    echo json_encode(['success' => $success]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}