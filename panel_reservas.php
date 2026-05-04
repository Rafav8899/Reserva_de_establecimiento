<?php 

// =========================================
// CADA ARCHIVO A PROTEGER TIENE ESTE BLOQUE
// =========================================

session_start();

// Si no hay una sesión iniciada, lo mandamos al login
if(!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin'){
    
    header("Location: login.php");
    exit;
}

// =========================================
// =========================================

require __DIR__ . '/config/db_conection.php'; // Tu conexión PDO

try{
    $sql = "SELECT r.id_reserva, r.fecha, r.turno, r.equipos, r.estado, c.nombre, c.apellido, c.celular, c.dni_frente, c.dni_dorso FROM reservas r JOIN clientes c ON r.id_cliente = c.id_cliente ORDER BY r.fecha DESC";

    $stmt = $pdo->query($sql);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch (PDOException $e){
    die("Error en la base de datos: " . $e->getMessage());
}
// $stmt = $pdo->query($sql);
// $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body class="body">

    <header class="header">
        <span>Bienvenido
            <strong> 
                <?php 
                if (isset($_SESSION['user_id'])):
                    echo $_SESSION['user_nombre'];
                endif;
                ?>
            </strong>
        </span>
        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="form_reserva.php">Reservar</a>

            <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin'): ?>
                <!-- Este botón SOLO lo verá el admin -->
                <a href="panel_reservas.php" class="btn-admin">Panel de Control</a>
                <!-- <a href="estadisticas.php" class="btn-admin">Reportes</a> -->
                
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- <a href="logout.php">Cerrar Sesión</a> -->
                <a href="logout.php" style="color: #ff4d4d; text-decoration: none; font-weight: bold;">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php">Iniciar Sesión</a>
            <?php endif; ?>
        </nav>
    </header>
    
    <H1>Panel de reservas</H1>

        <table class="table">
            <thead class="thead">
                <tr>
                    <th>Fecha</th>
                    <th>Turno</th>
                    <th>Cliente</th>
                    <th>Contacto</th>
                    <th>Equipos</th>
                    <th>DNI (Fotos)</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservas as $r) : ?>
                <tr class="estado-<?php echo $r['estado']; ?> ">
                    <td><?php echo date('d/m/Y', strtotime($r['fecha'])) ; ?></td>
                    <td><?php echo ucfirst($r['turno']) ; ?></td>
                    <td><?php echo $r['nombre'] . " ". $r['apellido'] ; ?></td>
                    <td><?php echo $r['celular']; ?></td>
                    <td><?php echo $r['equipos']; ?></td>
                    <td>
                        <a href="<?php echo $r['dni_frente']; ?>" target="_blank">Frente</a> | 
                        <a href="<?php echo $r['dni_dorso']; ?>" target="_blank">Dorso</a>
                    </td>
                    <td><strong><?php echo strtoupper($r['estado']); ?></strong></td>
                    <td>
                    <?php if ($r['estado'] == 'confirmada'): ?>
                        <button class="btn-cancelar " onclick="cancelarReserva(<?php echo $r['id_reserva']; ?>)">Cancelar</button>
                    <?php endif; ?>
                </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

<script>
        /**
         * Función AJAX para cancelar la reserva sin recargar
         */
        async function cancelarReserva(id) {
            if (!confirm("¿Estás seguro de que deseas cancelar esta reserva? Esto liberará el turno en el calendario inmediatamente.")) {
                return;
            }

            try {
                const response = await fetch(`cancelar_reserva.php?id=${id}`);
                const data = await response.json();

                if (data.success) {
                    alert("Reserva cancelada con éxito.");
                    // Recargamos la página para actualizar la tabla y los estilos
                    location.reload(); 
                } else {
                    alert("Error: " + (data.error || "No se pudo cancelar la reserva."));
                }
            } catch (error) {
                console.error("Error en la comunicación con el servidor:", error);
                alert("Hubo un problema al procesar la cancelación.");
            }
        }
    </script>

    <script src="script.js"> </script>
</body>
</html>