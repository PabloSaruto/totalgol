<?php
header('Content-Type: application/json');

// Obtener el ID del árbitro desde el parámetro
$id_arbitro = $_GET['ID_arbitro'];

// Incluir archivo de conexión a la base de datos
include_once 'conexion.php';

// Verificar si el ID del árbitro es válido
if (!isset($id_arbitro) || empty($id_arbitro)) {
    echo json_encode(['status' => 'error', 'message' => 'ID del árbitro no válido']);
    exit();
}

// Consulta SQL para obtener los partidos y los detalles de los equipos
$sql = "
    SELECT 
        p.ID_partido,
        p.IDEquipoLocal,
        p.IDEquipoVisitante,
        p.Fecha,
        p.Jornada,
        el.NombreEquipo AS NombreEquipoLocal,
        el.NombreEntrenador AS EntrenadorLocal,
        el.ColorVestimenta AS ColorVestimentaLocal,
        ev.NombreEquipo AS NombreEquipoVisitante,
        ev.NombreEntrenador AS EntrenadorVisitante,
        ev.ColorVestimenta AS ColorVestimentaVisitante
    FROM partidos p
    JOIN equipos el ON p.IDEquipoLocal = el.ID_equipo
    JOIN equipos ev ON p.IDEquipoVisitante = ev.ID_equipo
    WHERE p.Arbitro_asociado = ?
    ORDER BY Jornada"; // Filtrar por IDArbitro

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_arbitro); // "i" indica que el parámetro es un entero

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Verificar si hay resultados
if ($result->num_rows > 0) {
    $partidos = [];
    while ($row = $result->fetch_assoc()) {
        $partidos[] = $row;
    }
    echo json_encode(['status' => 'success', 'partidos' => $partidos]);
} else {
    echo json_encode(['status' => 'no_partidos']);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
