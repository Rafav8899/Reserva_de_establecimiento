<?php

session_start();

// Si el usuario ya inició sesión, lo redirigimos para que no vea el login
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin') {
        header("Location: panel_reservas.php"); // Si es admin, al panel de control
        exit;
    } else {
        header("Location: form_reserva.php"); // Si es cliente, al formulario de reservas
        exit;
    }
}

require_once 'vendor/autoload.php';

// Necesitás repetir la configuración del $client aquí para generar la URL
$client = new Google_Client();
$client->setClientId('TU_CLIENT_ID_DE_GOOGLE');
$client->setRedirectUri('http://localhost/reserva_estudio/auth_google.php');
$client->addScope("email");
$client->addScope("profile");

$authUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body class="body">
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">

            <strong> 
                <?php 
                if (isset($_SESSION['user_id'])):
                    echo $_SESSION['user_nombre'];
                endif;
                ?>
            </strong>
        
        <nav class="nav-menu">
            <ul>
                <li><a href="index.php" class="active">Inicio</a></li>
                <li><a href="#reservar">Reservar</a></li>
                <li>
                    <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin'): ?>
                    <!-- Este botón SOLO lo verá el admin -->
                    <a href="panel_reservas.php" class="btn-admin">Panel de Control</a>
                    <!-- <a href="estadisticas.php" class="btn-admin">Reportes</a> -->

                <?php endif; ?>
                </li>
                <li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                <!-- <a href="logout.php">Cerrar Sesión</a> -->
                <a href="logout.php" style="color: #ff4d4d; text-decoration: none; font-weight: bold;">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="login.php">Iniciar Sesión</a>
                <?php endif; ?>
                </li>
                
            </ul>
        </nav>
    </header>

        <div class="login-container">
            <h2>Ingresar al Estudio</h2> por favor
        
            <form action="validar_acceso.php" method="POST">
                <p>
                    <label>Ingresá con tu DNI para ver tus reservas:</label>
                </p>
                <input type="text" name="dni" placeholder="Tu número de documento" required>
                <p></p>
                <button type="submit" class="btn-submit">Ver mis turnos</button>
            </form>
            <p></p>
            <a href="<?php echo $authUrl; ?>" style="display: inline-block; padding: 10px; background: #4285F4; color: white; text-decoration: none; border-radius: 5px;">
            Entrar con Google
            </a>
        </div>
</body>
</html>