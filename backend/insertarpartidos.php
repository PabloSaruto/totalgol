<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Establecer cabeceras CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Incluir archivo de conexión a la base de datos
include_once 'conexion.php';

// Obtener los datos enviados por el cliente
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos no son nulos
if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Datos no válidos']);
    exit();
}

// Obtener los valores del formulario
$partidoId = $data['partidoId'];   // Obtener ID del partido desde el frontend
$golesLocal = $data['golesLocal']; // Goles del equipo local
$golesVisitante = $data['golesVisitante']; // Goles del equipo visitante

// Validar que los goles no estén vacíos
if (empty($golesLocal) || empty($golesVisitante)) {
    echo json_encode(['status' => 'error', 'message' => 'Los goles no pueden estar vacíos']);
    exit();
}

// Crear la consulta SQL para actualizar los goles del partido
$consulta = "UPDATE partidos SET GolesLocal = '$golesLocal', GolesVisitante = '$golesVisitante' WHERE ID_partido = '$partidoId'";

// Ejecutar la consulta
if (mysqli_query($conn, $consulta)) {
    // Respuesta exitosa
    echo json_encode(['status' => 'success', 'message' => 'Resultado actualizado correctamente']);
} else {
    // Error en la ejecución de la consulta
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el resultado']);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>