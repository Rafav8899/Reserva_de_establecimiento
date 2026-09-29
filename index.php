<?php

    session_start();
    require __DIR__ . '/config/db_conection.php';

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
    <main class="contenido">
        <div class="informacion">
            <h2>Reserva - Estudio Fotográfico Midi</h2>
            <p>
                <b>Solicitud de reserva del estudio fotográfico Midi, para proyectos experimentales y/o profesionales</b>
            </p>
            <h3>Pasos</h3>
            <p>
                <ol>
                    <li>
                        Completa los campos con tus datos personales y envía éste formulario
                    </li>
                    <li>
                        Aguarda la confirmación vía mail desde el correo del Fotolab Midi.
                    </li>
                    <li>
                        Abona el alquiler del espacio con el <b>QR (de Mercado Pago)</b> el día de la sesión. Consulte al encargado del Fotolab de turno, dónde abonar.
                    </li>
                </ol> 
            </p>

            <h3>En caso de: </h3>
            <p>
                No recibir confirmación: comunícate al correo <b>fotolabmidi@gmail.com</b> o por WhatsApp al <b><a href="https://wa.me/543764723926" class="btn btn--secondary">+54 9 3764 735604</a></b> (Coordinadora Suan) <br>
                Si ya recibiste la confirmación: Preséntate el día del turno concedido, con tu DNI en mano.
            </p>
            <h3>Aclaración: </h3>
            <!-- <p> -->
                <ul>
                    <li>
                        *IMPORTANTE: tener conocimiento y experiencia en el manejo de equipos de iluminación fotográfica. En caso de no poseerlo, se le recomienda buscar a un fotógrafo profesional que pueda hacer la reserva y asistir/acompañar al lugar para realizar la sesión fotográfica. No obstante, desde el Midi, pronto se habilitarán cursos de iluminación en Estudio con certificación para uso de los equipos Midi del FotoLab que lo habilitarán a realizar las reservas del estudio y equipos para su correcta manipulación.
                    </li> 
                    <li>
                        <b>*Este formulario es una pre-inscripción. Los cupos son limitados, por lo que serán informadas por mail o WhatsApp a las personas cuya solicitud sea confirmada.</b>
                    </li>
                    <li>
                        Las reservas son para el uso del espacio fotográfico del Midi con los equipos disponibles en el lugar.
                    </li>
                    <li>
                        Los equipos fotográficos estarán habilitados solamente para uso dentro del espacio y no podrán ser retirados fuera del Parque Industrial.
                    </li>
                    <li>
                        El acceso y uso del espacio y equipos está pensado para fines experimentales y/o de producción profesional. En caso de solicitar el espacio y/o equipos del Fotolab, se podrá <b>abonar el alquiler el día de la sesión en el turno confirmado. Valor: $10.000 por turno otorgado.</b>
                    </li>
                    <li>
                        La persona solicitante será responsable del cuidado del espacio y equipos durante el uso otorgado.
                    </li>
                    <li>
                        Se podrá solicitar la reserva hasta 72hs hábiles antes de la fecha solicitada, aguardando la confirmación de la misma. 
                    </li>
                    <li>
                        Se solicita a quienes reserven para producciones experimentales  o comerciales contemplar llevar protectores de pisadas, para las personas que pisan los fondos de papel, de ésta manera se pretende evitar manchar o ensuciar los mismos.
                    </li>
                </ul>
            <!-- </p> -->
            <!-- <div class="btn-reservas"> -->
                <a href="form_reserva.php" name="reservar" class="btn-reservas">Reservar</a>
            <!-- </div> -->
        </div>
    </main>
    

    <!-- 
    <p>
        <h2>CONTENIDO PARA BORRAR Y HACER COMMITS (por las dudas)</h2>
    </p>
    
    <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus culpa quam voluptatem eaque suscipit animi numquam itaque blanditiis, rem temporibus corrupti totam dignissimos soluta illum quas harum sint perspiciatis inventore.
    </p>

    <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias beatae debitis omnis, voluptatum, exercitationem reprehenderit voluptatibus eligendi iste, ratione architecto consequuntur quod laborum nostrum quisquam minima voluptatem amet vero nesciunt.

    </p>

    <p>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus beatae deleniti quidem totam. Eum nisi sunt quibusdam maiores dolorem pariatur aliquid blanditiis, enim, necessitatibus consequatur ipsum error facere architecto temporibus.
    </p> 
    -->
</body>
</html>