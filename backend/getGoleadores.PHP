<?php
// Incluye la conexión a la base de datos
include_once 'conexion.php';

// Definir la cabecera para indicar que el contenido es JSON
header('Content-Type: application/json');

// Consulta para obtener el nombre del jugador, el equipo y los goles
$query = "
    SELECT 
        j.NombreJugador,
        e.NombreEquipo,
        SUM(pg.Goles) AS TotalGoles
    FROM 
        jugadores j
    INNER JOIN 
        equipos e ON j.ID_equipo = e.ID_equipo
    LEFT JOIN 
        partidosgoles pg ON j.ID_jugador = pg.ID_jugador
    GROUP BY 
        j.ID_jugador, e.NombreEquipo
    HAVING 
        TotalGoles > 0  -- Excluye a los jugadores con cero goles
    ORDER BY 
        TotalGoles DESC
    LIMIT 20   -- Limitar a los primeros 20 goleadores
";  // Realizamos un JOIN entre las tablas jugadores, equipos y partidosgoles

// Ejecuta la consulta
$result = $conn->query($query);

// Inicializa un array para almacenar los datos
$jugadoresGoles = [];

// Verifica si hay resultados
if ($result->num_rows > 0) {
    // Si la consulta devuelve resultados, los almacena en el array
    while ($row = $result->fetch_assoc()) {
        $jugadoresGoles[] = $row;
    }
    
    // Devuelve los datos en formato JSON
    echo json_encode($jugadoresGoles);
} else {
    // Si no hay resultados, retorna un mensaje de error
    echo json_encode(["mensaje" => "No hay goleadores disponibles"]);
}

// Cierra la conexión a la base de datos
$conn->close();
?>
