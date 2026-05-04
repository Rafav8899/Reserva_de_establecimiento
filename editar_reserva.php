<?php
require __DIR__ . '/config/db_conection.php';
$token = $_GET['token'] ?? '';

// Buscamos los datos actuales
$sql = "SELECT r.*, c.* FROM reservas r JOIN clientes c ON r.id_cliente = c.id_cliente WHERE r.uuid = :token";
$stmt = $pdo->prepare($sql);
$stmt->execute([':token' => $token]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reserva) die("Reserva no encontrada.");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar mi Reserva</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container">
        <h1>Editar Reserva</h1>
        <form action="actualizar_reserva.php" method="POST" class="formReserva">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            
            <p>
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $reserva['nombre']; ?>" required>
            </p>

            <p>
                <label>Apellido:</label>
                <input type="text" name="apellido" value="<?php echo $reserva['apellido']; ?>" required>
            </p>

            <p>
                <label>Gmail:</label>
                <input type="email" name="gmail" value="<?php echo $reserva['gmail']; ?>" required>
            </p>

            <label>Celular:</label>
            <input type="text" name="celular" value="<?php echo $reserva['celular']; ?>" required>

            <p>
                <label>Equipos adicionales:</label>
                <textarea name="equipos"><?php echo $reserva['equipos']; ?></textarea>
            </p>

            <p>
                <small>* Nota: Por seguridad, para cambiar la fecha o el DNI, debes cancelar y crear una nueva reserva. </small>
            </p>

            <button type="submit" class="btn-guardar">Guardar Cambios</button>
            <a href="gestion_reserva.php?token=<?php echo $token; ?>">Volver atrás</a>
        </form>
    </div>
</body>
</html>