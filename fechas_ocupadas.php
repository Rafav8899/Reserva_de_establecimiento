<?php 

header('Content-Type: application/json');

require __DIR__ . '/config/db_conection.php';

// try{
//     // SOLO RESERVAS ACTIVAS
//     $sql = "SELECT fecha, turno FROM reservas WHERE estado = 'activa' AND fecha >= CURDATE() ";
//     $stmt = $pdo->query($sql);
//     $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     echo json_encode($reservas);
// } catch(Exception $e){
//     echo json_encode(['error' => $e->getMessage()]);
// }

$turnoSeleccionado = $_GET['turno'] ?? '';

try{
    if (!empty($turnoSeleccionado)){
        $sql = "SELECT fecha FROM reservas WHERE estado = 'confirmada' AND turno = :turno AND fecha >= CURDATE() ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':turno' => $turnoSeleccionado]);
    } else{
        $sql = "SELECT fecha, turno FROM reservas WHERE estado = 'confirmada' AND fecha >=CURDATE() ";
        $stmt = $pdo->query($sql);
    }
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reservas);
} catch(Exception $e){
    echo json_encode(['error' => $e->getMessage()]);
}



?>