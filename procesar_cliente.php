<?php
require __DIR__ . '/config/db_conection.php';

// VERIFICA EL MÉTODO
if($_SERVER['REQUEST_METHOD']!=='POST'){
    exit('Acceso no permitido');
}

// CAPTURAR DATOS
$nombre     = trim($_POST['nombre'] ?? '');
$apellido   = trim($_POST['apellido'] ?? '');
$dni        = trim($_POST['dni'] ?? '');
$gmail      = trim($_POST['gmail'] ?? '');
$celular    = trim($_POST['celular'] ?? '');
$domicilio  = trim($_POST['domicilio'] ?? '');
$residencia  = trim($_POST['residencia'] ?? '');
$nacionalidad  = trim($_POST['nacionalidad'] ?? '');


// VALIDACIONES MÍNIMAS

if(empty($nombre) || empty($apellido) || empty($dni) || empty($gmail)){
    exit('Faltan datos obligatorios');
}

// VERIFICAR SI EL CLIENTE EXISTE
$sql = "SELECT id_cliente FROM clientes WHERE dni = :dni LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([':dni' => $dni]);

$cliente = $stmt->fetch();

if ($cliente) {
    $id_cliente = $cliente['id_cliente'];
}else {
    $sql = "INSERT INTO clientes (nombre, apellido, dni, gmail, celular, domicilio, residencia, nacionalidad) VALUES (:nombre, :apellido, :dni, :gmail, :celular, :domicilio, :residencia, :nacionalidad)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre'   => $nombre,
        ':apellido' => $apellido,
        ':dni'      => $dni,
        ':gmail'    => $gmail,
        ':celular'  => $celular,
        ':domicilio'  => $domicilio,
        ':residencia'  => $residencia,
        ':nacionalidad'  => $nacionalidad
    ]);
    $id_cliente = $pdo->lastInsertId();
}

// FUNCIÓN PARA SUBIR ARCHIVOS DE DNI

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/dni/';

function subirArchivo($archivo, $id_cliente, $tipo) {
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // LIMITAR TAMAÑO
    if ($archivo['size'] > 10 * 1024 * 1024){
        return null;
    }

    // OBTENER EL TIPO REAL DEL ARCHIVO
    $mime = mime_content_type($archivo['tmp_name']);

    $tiposPermitidos = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];
    
    if (!in_array($mime, $tiposPermitidos)){
        return null;
    }

    // EXTENSIÓN SEGURA
    switch ($mime){
        case 'image/jpeg':
            $extension = 'jpg';
            break;

        case 'image/png':
            $extension = 'png';
            break;

        case 'image/webp':
            $extension = 'webp';
            break;

        default:
            return null;
    }

// CREA CARPETA SI NO EXISTE
    $rutaCarpeta = $_SERVER['DOCUMENT_ROOT'] . '/uploads/dni/';

    if(!file_exists($rutaCarpeta)){
        mkdir($rutaCarpeta, 0777, true);
    }

// NOMBRE DEL ARCHIVO
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombreArchivo = "cliente_{$id_cliente}_{$tipo}." . $extension;
    $rutaDestino = $_SERVER['DOCUMENT_ROOT'] . "/uploads/dni/" . $nombreArchivo;

    if(!move_uploaded_file($archivo['tmp_name'], $rutaDestino)){
        return null;
    };

    return "/uploads/dni/" . $nombreArchivo;
}

// SUBIR ARCHIVOS
$dniFrente = subirArchivo($_FILES['dni_frente'], $id_cliente, 'frente');
$dniDorso  = subirArchivo($_FILES['dni_dorso'], $id_cliente, 'dorso');

// VALIDAR QUE AMBOS ARCHIVOS SE HAYAN SUBIDO
if ($dniFrente ===null || $dniDorso ===null) {
    exit("Error: los archivos del DNI deben ser imágenes JPG, PNG O WEBP y pesar menos de 5MB.");
}

// GUARDAR RUTAS EN LA BASE DE DATOS

    $sql = "INSERT INTO documentacion_cliente (id_cliente, dni_frente, dni_dorso) VALUES (:id_cliente, :frente, :dorso) ON DUPLICATE KEY UPDATE dni_frente = VALUES(dni_frente), dni_dorso  = VALUES(dni_dorso)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_cliente' => $id_cliente,
        ':frente'     => $dniFrente,
        ':dorso'      => $dniDorso
    ]);

echo '<script>alert("Cliente guardado correctamente");history.go(-1);</script>';
?>