<?php
header('Content-Type: application/json');

// Incluir archivo de conexión a la base de datos
include_once 'conexion.php';

// Consulta SQL para obtener todos los partidos sin filtrar por jornada
$sql = "
  SELECT 
    p.ID_partido,
    p.IDEquipoLocal,
    p.IDEquipoVisitante,
    p.Fecha,
    p.Jornada,
    p.GolesLocal,
    p.GolesVisitante,
    el.NombreEquipo AS NombreEquipoLocal,
    el.NombreEntrenador AS EntrenadorLocal,
    el.ColorVestimenta AS ColorVestimentaLocal,
    ev.NombreEquipo AS NombreEquipoVisitante,
    ev.NombreEntrenador AS EntrenadorVisitante,
    ev.ColorVestimenta AS ColorVestimentaVisitante,
    GROUP_CONCAT(CONCAT(j.NombreJugador, ' (', e.NombreEquipo, ')') SEPARATOR ', ') AS NombreJugadores,
    GROUP_CONCAT(pg.Goles SEPARATOR ', ') AS GolesJugadores
FROM partidos p
JOIN equipos el ON p.IDEquipoLocal = el.ID_equipo
JOIN equipos ev ON p.IDEquipoVisitante = ev.ID_equipo
LEFT JOIN partidosgoles pg ON p.ID_partido = pg.ID_partido
LEFT JOIN jugadores j ON pg.ID_jugador = j.ID_jugador
LEFT JOIN equipos e ON j.ID_equipo = e.ID_equipo  -- Unir con la tabla de equipos para obtener el equipo del jugador
GROUP BY p.ID_partido
ORDER BY p.Jornada, p.Fecha;
"; // Ordenamos por jornada y fecha

// Preparar la consulta
$stmt = $conn->prepare($sql);

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
