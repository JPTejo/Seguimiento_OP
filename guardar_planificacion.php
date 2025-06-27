<?php
require_once 'conexion.php';
session_start();


// Obtener datos básicos
$usuario_id = $_POST['usuario_id'];
$cliente = $_POST['cliente'];
$maquina_principal = $_POST['maquina'] ?? null;
$estado = $_POST['accion'] ?? null;
$fecha_creacion = date('Y-m-d H:i:s');

// Insertar en la tabla registros
$sql = "INSERT INTO registros (usuario_id, cliente, maquina_id, estado, fecha_creacion)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $usuario_id, $cliente, $maquina_principal, $estado, $fecha_creacion);
$stmt->execute();
$registro_id = $stmt->insert_id;

// Insertar materia prima (puede ser múltiple o simple)
if (!empty($_POST['materias_primas'])) {
    foreach ($_POST['materias_primas'] as $materia) {
        $sql = "INSERT INTO materias_primas (registro_id, materia_prima)
                VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $registro_id, $materia);
        $stmt->execute();
    }
}

// Insertar máquinas adicionales si existen
$otras_maquinas = ['maquina_2', 'maquina_3', 'maquina_4'];
foreach ($otras_maquinas as $maq) {
    if (!empty($_POST[$maq])) {
        $maquina_extra = $_POST[$maq];
        $sql = "INSERT INTO maquinas (registro_id, maquina)
                VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $registro_id, $maquina_extra);
        $stmt->execute();
    }
}

// Insertar imágenes (archivos)
if (!empty($_FILES['imagenes']['name'][0])) {
    $targetDir = "uploads/";
    foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
        $fileName = basename($_FILES['imagenes']['name'][$index]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        if (move_uploaded_file($tmpName, $targetFilePath)) {
            $sql = "INSERT INTO imagenes (registro_id, ruta)
                    VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $registro_id, $targetFilePath);
            $stmt->execute();
        }
    }
}

// Insertar comentarios
if (!empty($_POST['comentarios'])) {
    $comentario = $_POST['comentarios'];
    $sql = "INSERT INTO comentarios (registro_id, texto)
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $registro_id, $comentario);
    $stmt->execute();
}

// Redirigir o mostrar mensaje
echo "Datos guardados correctamente.";
// Opcional: header("Location: alguna_pagina.php");
?>
