<?php
include_once 'conexion.php';
header('Content-Type: application/json');

// Obtener el ID del equipo desde la URL
$idEquipo = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idEquipo > 0) {
    // Consulta para obtener el equipo, sus puntos y jugadores
    $query = "
        SELECT 
            e.NombreEquipo,
            e.NombreEntrenador,
            e.Puntos,
            j.ID_jugador,
            j.NombreJugador
        FROM equipos e
        LEFT JOIN jugadores j ON e.ID_equipo = j.ID_equipo
        WHERE e.ID_equipo = $idEquipo
    ";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(["error" => "Error en la consulta: " . $conn->error]);
        exit();
    }

    // Inicializamos la estructura de datos
    $data = [
        "NombreEquipo" => "",
        "NombreEntrenador" => "",
        "Puntos" => 0,  // Agregamos el campo de puntos
        "jugadores" => []
    ];

    while ($row = $result->fetch_assoc()) {
        $data["NombreEquipo"] = $row["NombreEquipo"];
        $data["NombreEntrenador"] = $row["NombreEntrenador"];
        $data["Puntos"] = $row["Puntos"];  // Capturamos los puntos
        
        if (!is_null($row["ID_jugador"])) {  // Verificar si hay jugadores
            $data["jugadores"][] = [
                "ID_jugador" => $row["ID_jugador"],
                "NombreJugador" => $row["NombreJugador"]
            ];
        }
    }

    echo json_encode($data);
} else {
    echo json_encode(["mensaje" => "ID de equipo no válido"]);
}

$conn->close();
?>
