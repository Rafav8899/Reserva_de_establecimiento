<?php

session_start();
require __DIR__ . '/config/db_conection.php';

// Variables vacías por defecto
$dni = "";
$nombre = "";
$apellido = "";
$gmail = ""; 
$celular =  "";
$domicilio = "";
$residencia = "";
$nacionalidad = "";


// Si el usuario ya está logueado, traemos sus datos de la base de datos
if (isset($_SESSION['id_usuario']) && $_SESSION['rol'] === 'cliente') {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id_cliente = :id");
    $stmt->execute([':id' => $_SESSION['id_usuario']]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        $dni = $cliente['dni'];
        $nombre = $cliente['nombre'];
        $apellido = $cliente['apellido'];
        $gmail = $cliente['gmail'];
        $celular = $cliente['celular'];
        $domicilio = $cliente['domicilio'];
        $residencia = $cliente['residencia'];
        $nacionalidad = $cliente['nacionalidad'];
        // Agregá acá los otros campos que necesites (domicilio, etc.)
    }
} elseif(isset($_SESSION['temp_email'])){
    $gmail = $_SESSION['temp_email'];
    // $nombre = $_SESSION['temp_nombre'] ?? "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    
    <title>RESERVAS</title>
</head>
<body class="body">
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
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
    
    <section class="formulario">

        <form class="formReserva" action="procesar_reservas.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="dni">DNI: </label>
                <input type="text" name="dni" value="<?php echo htmlspecialchars($dni); ?>" required>
            </p>
            <p>
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="<?php echo $nombre; ?>" required>
            </p>
            <p>
                <label for="apellido">Apellido: </label>
                <input type="text" name="apellido" value="<?php echo $apellido; ?>" required>
            </p>
            
            <p>
                <label for="gmail">Gmail: </label>
                <input type="email" name="gmail" value="<?php echo $gmail; ?>" required>
            </p>
            <p>
                <label for="celular">Nro Celular: </label>
                <input type="text" name="celular" value="<?php echo $celular; ?>" required>
            </p>
            <p>
                <label for="domicilio">Domicilio: </label>
                <input type="text" name="domicilio" value="<?php echo $domicilio; ?>" required>
            </p>
            <p>
                <label for="residencia">Residencia: </label>
                <input type="text" name="residencia" value="<?php echo $residencia; ?>" required>
            </p>
            <p>
                <label for="nacionalidad">Nacionalidad: </label>
                <input type="text" name="nacionalidad" value="<?php echo $nacionalidad; ?>" required>
            </p>

            <!-- T U R N O S -->
            <p>
                <label>Turno:
                    <select id="turno_reserva" name="turno" required>
                        <option value="">--Elegir turno--</option>
                            <option value="mañana">Mañana</option>
                            <option value="tarde">Tarde</option>
                    </select>
                </label>
            </p>
            
            <!-- C A L E N D A R I O -->
            <div class="calendar">

                <header>
                    <button type="button" id="prev">◀</button>
                    <span id="monthYear"></span>
                    <button type="button" id="next">▶</button>
                </header>

                <!-- Encabezado de días -->
                <div class="weekdays">
                    <div>Dom</div>
                    <div>Lun</div>
                    <div>Mar</div>
                    <div>Mié</div>
                    <div>Jue</div>
                    <div>Vie</div>
                    <div>Sáb</div>
                </div>

                <!-- Días dinámicos -->
                <div class="days" id="days"></div>

            </div>

            <input type="hidden" name="fecha" id="fechaSeleccionada" required>

            <div class="equipos-grid">
                <h3>Seleccioná el equipo que vas a usar:</h3>
                
                <label>
                    <input type="checkbox" name="equipos[]" value="Sin equipo extra">
                    <img src="img/tripode.jpg" width="50"> No necesito Equipo
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Flash Visico 2">
                    <img src="img/canon.jpg" width="50"> Flash Visico 2
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Flash Visico IV">
                    <img src="img/softbox.jpg" width="50"> Flash Visico IV
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Flash Visico V">
                    <img src="img/tripode.jpg" width="50"> Trípode
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Luces Led">
                    <img src="img/tripode.jpg" width="50"> Luces Led
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Emisor">
                    <img src="img/tripode.jpg" width="50"> Emisor (compatible con Canon)
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Pie de luces">
                    <img src="img/tripode.jpg" width="50"> Pie de luces
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Snoot">
                    <img src="img/tripode.jpg" width="50"> Snoot
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Softbox 35cm x 140cm">
                    <img src="img/tripode.jpg" width="50"> Softbox 35cm x 140cm
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Softbox 90 x 120cm">
                    <img src="img/tripode.jpg" width="50"> Softbox 90 x 120cm
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Jirafa">
                    <img src="img/tripode.jpg" width="50"> Jirafa
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Softbox Octogonal 150cm">
                    <img src="img/tripode.jpg" width="50"> Softbox Octogonal 150cm
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Reflector curvo Eyerlighter">
                    <img src="img/tripode.jpg" width="50"> Reflector curvo Eyerlighter
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Portafondo">
                    <img src="img/tripode.jpg" width="50"> Portafondo
                </label>
                <p></p>
                <label>
                    <input type="checkbox" name="equipos[]" value="Pantalla reflectora 5 en 1">
                    <img src="img/tripode.jpg" width="50"> Pantalla reflectora 5 en 1
                </label>
            </div>
            
            <h3>Documentación</h3>
            
            <div class="seccion-dni" id="seccion-dni">
                <p>
                    <label for="dni_frente">DNI Frente: </label>
                    <input type="file" name="dni_frente" accept="image/*" required>
                </p>
                <p>
                    <label for="dni_dorso">DNI Dorso: </label>
                    <input type="file" name="dni_dorso" accept="image/*" required>
                </p>
            </div>

            <button type="submit">Guardar Cliente</button>

        </form>
    </section>

    <!-- Mensajes -->
    <div id="mensaje"></div>

    <script>
            fetch("obtenerReservas.php")
            .then(res => res.json())
            .then(data =>{
                window.fechasReservadas = data;
                cargarCalendario();
            });
        </script>

  <!-- JS al final del body -->
    <script src="script.js"></script>
</body>
</html>