<?php

session_start();

require __DIR__ . '/config/db_conection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Caso A: El usuario intenta entrar con DNI (Cliente)
    if (isset($_POST['dni']) && !empty(trim($_POST['dni']))) {
        $dni = trim($_POST['dni']);

        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE dni = :dni LIMIT 1");
        $stmt->execute([':dni' => $dni]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            // Guardamos al cliente en la sesión
            $_SESSION['user_id'] = $cliente['id_cliente'];
            $_SESSION['user_rol'] = 'cliente';
            $_SESSION['user_nombre'] = $cliente['nombre'];

            // Redirigimos al formulario de reserva con sus datos
            header("Location: form_reserva.php");
            exit;
        } else {
            // Si no existe, lo mandamos a registrarse/reservar de cero
            header("Location: form_reserva.php?nuevo=true");
            exit;
        }
    }

    // Caso B: El usuario intenta entrar con Gmail/Password (Admin)
    // Nota: Esto es para un login manual, luego lo uniremos con Google
    if (isset($_POST['gmail']) && isset($_POST['contrasenia'])) {
        $gmail = trim($_POST['gmail']);
        $pass  = $_POST['contrasenia'];

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE gmail = :mail AND rol = 'admin' LIMIT 1");
        $stmt->execute([':mail' => $gmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['contrasenia'])) {
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['user_rol'] = 'admin';

            $_SESSION['user_nombre'] = $user['nombre_usuario'] ?? 'Administrador';
            
            header("Location: panel_reservas.php");
            exit;
        } else {
            echo "<script>alert('Acceso denegado: Credenciales incorrectas'); window.location.href='login.php';</script>";
        }
    }
}