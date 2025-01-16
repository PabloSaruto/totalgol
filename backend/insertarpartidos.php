
<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include('conexion.php');  // Asegúrate de que este archivo tenga la conexión a la base de datos

// Obtener los datos recibidos en JSON
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos requeridos estén presentes
if (isset($data['partidoId'], $data['golesLocal'], $data['golesVisitante'])) {
    // Extraemos los datos
    $partidoId = $data['partidoId'];
    $golesLocal = $data['golesLocal'];
    $golesVisitante = $data['golesVisitante'];

    echo "<script>console.log('Paso 1: Datos recibidos - partidoId: $partidoId, golesLocal: $golesLocal, golesVisitante: $golesVisitante');</script>";

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Inserción de los goles del partido en la tabla partidos
        // Definir la consulta UPDATE para actualizar los goles del partido

            $query = "UPDATE equipos  SET Puntos = 66666666 WHERE ID_equipo = 1";
                $stmt = $conn->prepare($query);

                // Ejecutamos la consulta
                $stmt->execute();




            // $query = "UPDATE partidos SET GolesLocal = ?, GolesVisitante = ? WHERE ID_partido = ?";
            // $stmt = $conn->prepare($query);

            // // Vinculamos los parámetros: GolesLocal, GolesVisitante, y ID_partido
            // $stmt->bind_param('iii', $golesLocal, $golesVisitante, $partidoId);

            // // Ejecutamos la consulta
            // $stmt->execute();


            
            

        

       
     


        // Responder con éxito
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // En caso de error, hacemos rollback
        $conn->rollback();
        echo "<script>console.log('Error: Transacción revertida - Mensaje: {$e->getMessage()}');</script>";
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al insertar los datos del partido: ' . $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    } finally {
        // Cerramos la conexión
        $conn->close();
    }
} else {
    echo "<script>console.log('Error: Datos requeridos no proporcionados');</script>";
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
?>
