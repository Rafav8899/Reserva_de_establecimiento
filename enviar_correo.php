<?php

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function enviarConfirmacionReserva($cliente, $reserva) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor (Usando Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sr.rafa0219@gmail.com'; 
        $mail->Password   = 'euys davb aqqb akgr'; // La clave de 16 letras de Google
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Destinatarios
        $mail->setFrom('tu-estudio@gmail.com', 'Rafaga Fotográfica Estudio');
        $mail->addAddress($cliente['gmail'], $cliente['nombre']);

        // El Token de gestión (lo usamos para los botones)
        $token = $reserva['uuid']; // Usaremos el UUID que ya generamos antes
        $urlGestion = "http://localhost/reserva_estudio/gestion_reserva.php?token=" . $token;

        // Contenido HTML del correo
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de tu Reserva - Turno ' . ucfirst($reserva['turno']);
        
        $mail->Body = "
            <div style='font-family: sans-serif; max-width: 600px; padding: 20px; border: 1px solid #eee;'>
                <h2>¡Hola {$cliente['nombre']}!</h2>
                <p>Tu turno ha sido reservado correctamente.</p>
                <div style='background: #f9f9f9; padding: 15px; border-radius: 5px;'>
                    <p><strong>Fecha:</strong> {$reserva['fecha']}</p>
                    <p><strong>Turno:</strong> {$reserva['turno']}</p>
                    
                </div>
                <p>Podés gestionar tu reserva (editar o cancelar) aquí:</p>
                <a href='{$urlGestion}' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Gestionar mi Reserva</a>
            </div>
        ";
// <p><strong>Equipos:</strong> {$reserva['equipos']}</p>

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar el correo: " . $e->getMessage());
        return false;
    }
}