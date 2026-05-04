<?php
require __DIR__ . '/config/db_conection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token   = $_POST['token'];
    $nombre  = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $gmail   = trim($_POST['gmail']);
    $celular = trim($_POST['celular']);
    $equipos = trim($_POST['equipos']);

    try {
        $pdo->beginTransaction();

        // 1. Obtener el ID del cliente vinculado a ese token
        $stmtId = $pdo->prepare("SELECT id_cliente FROM reservas WHERE uuid = :token");
        $stmtId->execute([':token' => $token]);
        $id_cliente = $stmtId->fetchColumn();

        // 2. Actualizar datos del cliente
        $updCliente = $pdo->prepare("UPDATE clientes SET nombre = :nom, apellido = :ape, gmail = :mail, celular = :cel WHERE id_cliente = :id");
        $updCliente->execute([':nom' => $nombre,':ape' => $apellido, ':mail' => $gmail, ':cel' => $celular, ':id' => $id_cliente]);

        // 3. Actualizar equipos en la reserva
        $updReserva = $pdo->prepare("UPDATE reservas SET equipos = :eq WHERE uuid = :token");
        $updReserva->execute([':eq' => $equipos, ':token' => $token]);

        $pdo->commit();
        echo "<script>alert('Datos actualizados correctamente'); window.location.href='gestion_reserva.php?token=$token';</script>";

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}