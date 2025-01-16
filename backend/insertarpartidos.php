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
if (!is_numeric($golesLocal) || !is_numeric($golesVisitante)) {
    echo json_encode(['status' => 'error', 'message' => 'Los goles deben ser valores numéricos']);
    exit();
}

// Crear la consulta SQL para actualizar los goles del partido
$consulta = "UPDATE partidos SET GolesLocal = '$golesLocal', GolesVisitante = '$golesVisitante' WHERE ID_partido = '$partidoId'";

// Ejecutar la consulta
if (mysqli_query($conn, $consulta)) {
    // Hacer un SELECT para obtener los IDs de los equipos local y visitante
    $consultaEquipos = "SELECT IDEquipoLocal, IDEquipoVisitante FROM partidos WHERE ID_partido = '$partidoId'";
    $resultadoEquipos = mysqli_query($conn, $consultaEquipos);

    if ($resultadoEquipos && mysqli_num_rows($resultadoEquipos) > 0) {
        $fila = mysqli_fetch_assoc($resultadoEquipos);
        $idEquipoLocal = $fila['IDEquipoLocal'];
        $idEquipoVisitante = $fila['IDEquipoVisitante'];

        // Obtener los puntos del equipo local
        $consultaPuntosLocal = "SELECT Puntos FROM equipos WHERE ID_equipo = '$idEquipoLocal'";
        $resultadoPuntosLocal = mysqli_query($conn, $consultaPuntosLocal);
        
        if ($resultadoPuntosLocal && mysqli_num_rows($resultadoPuntosLocal) > 0) {
            $filaLocal = mysqli_fetch_assoc($resultadoPuntosLocal);
            $puntosLocal = (int)$filaLocal['Puntos'];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron puntos para el equipo local']);
            exit();
        }
        
        // Obtener los puntos del equipo visitante
        $consultaPuntosVisitante = "SELECT Puntos FROM equipos WHERE ID_equipo = '$idEquipoVisitante'";
        $resultadoPuntosVisitante = mysqli_query($conn, $consultaPuntosVisitante);
        
        if ($resultadoPuntosVisitante && mysqli_num_rows($resultadoPuntosVisitante) > 0) {
            $filaVisitante = mysqli_fetch_assoc($resultadoPuntosVisitante);
            $puntosVisitante = (int)$filaVisitante['Puntos'];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron puntos para el equipo visitante']);
            exit();
        }

        // Actualizar puntos según el resultado
        if ($golesLocal > $golesVisitante) {
            $puntosLocal += 3; // Sumar 3 puntos al equipo local
        } else if ($golesLocal < $golesVisitante) {
            $puntosVisitante += 3; // Sumar 3 puntos al equipo visitante
        } else {
            $puntosLocal += 1; // Sumar 1 punto a ambos equipos
            $puntosVisitante += 1;
        }

        // Actualizar los puntos en la base de datos
        $actualizarLocal = "UPDATE equipos SET Puntos = '$puntosLocal' WHERE ID_equipo = '$idEquipoLocal'";
        $actualizarVisitante = "UPDATE equipos SET Puntos = '$puntosVisitante' WHERE ID_equipo = '$idEquipoVisitante'";

        if (mysqli_query($conn, $actualizarLocal) && mysqli_query($conn, $actualizarVisitante)) {
            echo json_encode(['status' => 'success', 'message' => 'Resultados y puntos actualizados correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar los puntos de los equipos']);
        }
    } else {
        // Error al obtener los equipos
        echo json_encode(['status' => 'error', 'message' => 'No se encontraron los equipos para el partido especificado']);
    }
} else {
    // Error en la ejecución de la consulta
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el resultado']);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
