<?php
session_start();

require_once 'vendor/autoload.php';
require_once 'config/db_conection.php';

// Configuración de Google (Cambiá estos valores por los tuyos)
$clientID = 'TU_CLIENT_ID_DE_GOOGLE';
$clientSecret = 'TU_CLIENT_SECRET_DE_GOOGLE';
$redirectUri = 'http://localhost/reserva_estudio/auth_google.php';



$client = new Google_Client();

// Forzar la desactivación de verificación SSL en cURL para entorno local
$client->setHttpClient(new GuzzleHttp\Client(['verify' => false]));

$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    // 1. Intercambiar el código por un token de acceso
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // VALIDACIÓN CRÍTICA
    if (isset($token['error'])){
        die("Error de autenticación con Google" . ($token['error_description'] ?? $token['error']));
    }

    $client->setAccessToken($token);

    // 2. Obtener datos del perfil de Google
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    
    $email = $google_account_info->email;
    $nombre = $google_account_info->givenName;
    // $google_id = $google_account_info->id;

    // 3. Lógica de Verificación en la Base de Datos
    // Primero buscamos si el email está registrado como ADMIN
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE gmail = :email AND rol = 'admin' LIMIT 1");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        // Es Administrador: Iniciamos sesión de admin
        $_SESSION['user_id'] = $admin['id_usuario'];
        $_SESSION['user_rol'] = $admin['rol'];
        $_SESSION['user_nombre'] = $nombre;
        
        header("Location: panel_reservas.php");
        exit;

    } else {
        // No es admin, entonces lo tratamos como CLIENTE
        // Buscamos si ya existe un cliente con ese email
        $stmtC = $pdo->prepare("SELECT * FROM clientes WHERE gmail = :email LIMIT 1");
        $stmtC->execute([':email' => $email]);
        $cliente = $stmtC->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            // Cliente existente: Guardamos sus datos en la sesión
            $_SESSION['user_id'] = $cliente['id_cliente'];
            $_SESSION['user_rol'] = 'cliente';
            $_SESSION['user_nombre'] = $cliente['nombre'];

            header("Location: form_reserva.php");
            exit;
            
        } else {
            // En auth_google.php, cuando no es admin ni cliente conocido:
            // Podés guardar un mensaje para mostrar en el login
        
            // Cliente nuevo: Podés crear una sesión temporal con su nombre/mail
            // para que el formulario de reserva ya aparezca con esos datos.
            $_SESSION['user_rol'] = 'cliente';
            $_SESSION['temp_email'] = $email;
            $_SESSION['temp_nombre'] = $nombre;
            $_SESSION['error_login'] = "La cuenta $email no tiene permisos de administrador.";
            header("Location: form_reserva.php");
            exit;
        }
    }
} else {
    // Si no hay código, redirigir al login
    header("Location: login.php");
    exit;
}