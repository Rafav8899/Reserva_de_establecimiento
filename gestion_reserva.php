<?php

// =========================================

session_start();

// Si no hay una sesión iniciada, lo mandamos al login
if (!isset($_SESSION['user_rol'])) {
    header("Location: login.php");
    exit;
}

// Opcional: Si el archivo es SOLO para admin
if ($_SESSION['user_rol'] !== 'admin' && basename($_SERVER['PHP_SELF']) == 'panel_reservas.php') {
    header("Location: form_reserva.php"); // Si un cliente intenta entrar al panel admin, lo mandamos a reservar
    exit;
}

// =========================================

require __DIR__ . '/config/db_conection.php';

// 1. Validar que llegue un token
$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Acceso denegado: Token no proporcionado.");
}

// 2. Buscar la reserva y los datos del cliente vinculados
try {
    $sql = "SELECT r.*, c.nombre, c.apellido, c.gmail 
            FROM reservas r 
            JOIN clientes c ON r.id_cliente = c.id_cliente 
            WHERE r.uuid = :token LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':token' => $token]);
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reserva) {
        die("Lo sentimos, no encontramos ninguna reserva asociada a este enlace.");
    }

    // Verificar si la reserva ya pasó (opcional, pero profesional)
    $fechaReserva = strtotime($reserva['fecha']);
    $hoy = strtotime(date('Y-m-d'));
    $esPasada = ($fechaReserva < $hoy);

} catch (Exception $e) {
    die("Error en el sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar mi Reserva</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-width: 400px; width: 90%; text-align: center; }
        h1 { color: #333; font-size: 1.5rem; }
        .info { background: #f9f9f9; padding: 1rem; border-radius: 8px; text-align: left; margin: 1.5rem 0; }
        .info p { margin: 5px 0; color: #555; }
        .btn { display: block; width: 100%; padding: 12px; margin-top: 10px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; text-decoration: none; font-size: 1rem; }
        .btn-cancelar { background-color: #e74c3c; color: white; }
        .btn-cancelar:hover { background-color: #c0392b; }
        .btn-editar { background-color: #3498db; color: white; }
        .btn-editar:hover { background-color: #2980b9; }
        .badge-cancelada { color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>

    <div style="text-align: right; margin-bottom: 10px;">
        <a href="logout.php" style="font-size: 0.9rem; color: #666;">Salir / Cerrar sesión</a>
    </div>


    <div class="card">
        <h1>Hola, <?php echo htmlspecialchars($reserva['nombre']); ?></h1>
        
        <?php if ($reserva['estado'] === 'cancelada'): ?>
            <p class="badge-cancelada">Esta reserva ya ha sido cancelada.</p>
            <a href="index.html" class="btn btn-editar">Volver al inicio</a>
        <?php else: ?>
            <p>Aquí puedes gestionar los detalles de tu turno.</p>
            
            <div class="info">
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($reserva['fecha'])); ?></p>
                <p><strong>Turno:</strong> <?php echo ucfirst($reserva['turno']); ?></p>
                <p><strong>Equipos:</strong> <?php echo htmlspecialchars($reserva['equipos']); ?></p>
            </div>

            <?php if ($esPasada): ?>
                <p style="color: #777;">Esta reserva ya ha pasado y no puede ser modificada.</p>
            <?php else: ?>
                
                <a href="editar_reserva.php?token=<?php echo $token; ?>" class="btn btn-editar">Editar datos</a>
                
                <a class="btn btn-cancelar" onclick="confirmarCancelacion('<?php echo $token; ?>')">Cancelar Reserva</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        async function confirmarCancelacion(token) {
            if (confirm("¿Estás seguro de que deseas cancelar tu reserva? Esta acción liberará el turno para otros clientes.")) {
                try {
                    // Reutilizamos el archivo cancelar_reserva.php que hicimos para el admin
                    // Pero enviamos el TOKEN en lugar del ID por seguridad
                    const response = await fetch(`cancelar_reserva_cliente.php?token=${token}`);
                    const data = await response.json();

                    if (data.success) {
                        alert("Tu reserva ha sido cancelada correctamente.");
                        location.reload();
                    } else {
                        alert("Hubo un error: " + data.error);
                    }
                } catch (error) {
                    console.error("Error:", error);
                }
            }
        }
    </script>
</body>
</html>