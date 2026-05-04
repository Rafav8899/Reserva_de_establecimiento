<?php
require_once 'vendor/autoload.php';

$clientID = 'TU_CLIENT_ID_DE_GOOGLE';
$clientSecret = 'TU_CLIENT_SECRET_DE_GOOGLE';
$redirectUri = 'http://localhost/tu_proyecto/auth_google.php';

// Crear el cliente de Google
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

?>