<?php
// Incluye la conexión a la base de datos
include_once 'conexion.php';

// Definir la cabecera para indicar que el contenido es JSON
header('Content-Type: application/json');

// Consulta para obtener los equipos y sus puntos
$query = "
   SELECT e.ID_equipo, e.NombreEquipo, e.NombreEntrenador, e.ColorVestimenta, puntos      
FROM equipos e
LEFT JOIN partidos p ON p.IDEquipoLocal = e.ID_equipo OR p.IDEquipoVisitante = e.ID_equipo
GROUP BY e.ID_equipo
ORDER BY puntos DESC

";

// Depuración: Mostrar la consulta

// Ejecuta la consulta
$result = $conn->query($query);

// Inicializa un array para almacenar los datos
$equipos = [];

if ($result->num_rows > 0) {
    // Si la consulta devuelve resultados, los almacena en el array
    while ($row = $result->fetch_assoc()) {
        $equipos[] = $row;
    }
    
    // Devuelve los datos en formato JSON
    echo json_encode($equipos);
} else {
    // Si no hay resultados, retorna un mensaje de error
    echo json_encode(["mensaje" => "No hay equipos disponibles"]);
}

// Cierra la conexión a la base de datos
$conn->close();
?>
