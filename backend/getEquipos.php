<?php
// Incluye la conexión a la base de datos
include_once 'conexion.php';

// Definir la cabecera para indicar que el contenido es JSON
header('Content-Type: application/json');

// Consulta para obtener los equipos
$query = "SELECT ID_equipo, NombreEquipo, NombreEntrenador, ColorVestimenta FROM equipos";  // Asegúrate de que la tabla "equipos" exista en tu base de datos

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
