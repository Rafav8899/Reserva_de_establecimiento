<?php
require __DIR__ . '/config/db_conection.php'; // Tu conexión con $pdo
require __DIR__ . '/enviar_correo.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acceso no permitido');
}

// 1. CAPTURAR DATOS (De forma organizada)
$datos = [
    'DNI'          => trim($_POST['dni'] ?? ''),
    'Nombre'       => trim($_POST['nombre'] ?? ''),
    'Apellido'     => trim($_POST['apellido'] ?? ''),
    'Gmail'        => trim($_POST['gmail'] ?? ''),
    'Celular'      => trim($_POST['celular'] ?? ''),
    'Domicilio'    => trim($_POST['domicilio'] ?? ''),
    'Residencia'   => trim($_POST['residencia'] ?? ''),
    'Nacionalidad' => trim($_POST['nacionalidad'] ?? ''),
    'Fecha'        => $_POST['fecha'] ?? '',
    'Turno'        => $_POST['turno'] ?? '',
    'Equipos'      => $_POST['equipos'] ?? []
];

// 2. VALIDACIÓN INTELIGENTE
$faltantes = [];

foreach ($datos as $nombreCampo => $valor) {
    // Si el valor está vacío (y no es el de equipos, que puede ser opcional)
    if (empty($valor)) {
        $faltantes[] = $nombreCampo;
    }
}

// 3. SI HAY FALTANTES, MOSTRAR CUÁLES
if (!empty($faltantes)) {
    // implode convierte el array ['DNI', 'Fecha'] en un texto "DNI, Fecha"
    exit('Faltan datos obligatorios: ' . implode(', ', $faltantes));
}

// 4. EXTRAER VARIABLES (Para seguir usando tu código como antes)
// Esto crea las variables $dni, $nombre, etc., automáticamente desde el array
extract([
    'dni'       => $datos['DNI'],
    'nombre'    => $datos['Nombre'],
    'apellido'  => $datos['Apellido'],
    'gmail'     => $datos['Gmail'],
    'celular'   => $datos['Celular'],
    'domicilio' => $datos['Domicilio'],
    'residencia'=> $datos['Residencia'],
    'nacionalidad'  => $datos['Nacionalidad'],
    'fecha'     => $datos['Fecha'],
    'turno'     => $datos['Turno']
]);

$equiposRaw = $datos['Equipos'];

try {
    $pdo->beginTransaction();

    // 2. BUSCAR SI EL CLIENTE EXISTE
    $stmt = $pdo->prepare("SELECT id_cliente FROM clientes WHERE dni = :dni LIMIT 1");
    $stmt->execute([':dni' => $dni]);
    $cliente = $stmt->fetch();

    if ($cliente) {
        $id_cliente = $cliente['id_cliente'];
        // Si el cliente existe, podrías hacer un UPDATE aquí si quieres actualizar su mail/teléfono
    } else {
        // 3. SI NO EXISTE: PROCESAR FOTOS Y CREAR CLIENTE
        $rutaFrente = subirArchivo($_FILES['dni_frente'], $dni, 'frente');
        $rutaDorso  = subirArchivo($_FILES['dni_dorso'], $dni, 'dorso');

        if (!$rutaFrente || !$rutaDorso) {
            throw new Exception("Error al subir las imágenes del DNI.");
        }

        $sqlIns = "INSERT INTO clientes (nombre, apellido, dni, gmail, celular, domicilio, residencia, nacionalidad, dni_frente, dni_dorso) VALUES (:nom, :ape, :dni, :mail, :cel, :dom, :resi, :nacio, :frente, :dorso)";
        $stmtIns = $pdo->prepare($sqlIns);
        $stmtIns->execute([
            ':nom' => $nombre, ':ape' => $apellido, ':dni' => $dni,
            ':mail' => $gmail, ':cel' => $celular, ':dom' => $domicilio, ':resi' => $residencia, ':nacio' => $nacionalidad, ':frente' => $rutaFrente, ':dorso' => $rutaDorso
        ]);
        $id_cliente = $pdo->lastInsertId();
    }

    // 4. CREAR LA RESERVA
    $equiposString = implode(", ", $equiposRaw);
    $uuid = bin2hex(random_bytes(16)); // UUID más seguro

    $sqlRes = "INSERT INTO reservas (uuid, id_cliente, fecha, turno, equipos, estado) VALUES (:uuid, :id_c, :fec, :tur, :eq, 'confirmada')";
    $stmtRes = $pdo->prepare($sqlRes);
    $stmtRes->execute([
        ':uuid' => $uuid, ':id_c' => $id_cliente, ':fec' => $fecha,
        ':tur' => $turno, ':eq' => $equiposString
    ]);

    $pdo->commit();

    $datosParaCorreoCliente = [
        'nombre' => $nombre,
        'gmail' => $gmail,
        'dni' => $dni
    ];

    $datosParaCorreoReserva = [
        'fecha' => $fecha,
        'turno' => $turno,
        'uuid' => $uuid
    ];

    enviarConfirmacionReserva($datosParaCorreoCliente, $datosParaCorreoReserva);

        echo "<script>alert('Reserva confirmada con éxito. Por favor revise su correo.'); window.location.href='index.php';</script>";


} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}

// --- FUNCIÓN DE SUBIDA MEJORADA ---
function subirArchivo($archivo, $identificador, $lado) {
    if ($archivo['error'] !== UPLOAD_ERR_OK) return null;
    
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombreLimpio = "dni_" . $identificador . "_" . $lado . "." . $extension;
    $folder = $_SERVER['DOCUMENT_ROOT'] . '/uploads/dni/';
    
    if (!file_exists($folder)) mkdir($folder, 0777, true);
    
    $destino = $folder . $nombreLimpio;
    if (move_uploaded_file($archivo['tmp_name'], $destino)) {
        return '/uploads/dni/' . $nombreLimpio;
    }
    return null;
}
