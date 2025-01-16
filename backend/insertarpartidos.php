<?php
require_once 'conexion.php'; // Asegúrate de que la conexión esté correctamente configurada

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos JSON enviados
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['status' => 'error', 'message' => 'No se enviaron datos válidos.']);
        exit();
    }

    $partidoId = isset($data['partidoId']) ? intval($data['partidoId']) : null;
    $golesLocal = isset($data['golesLocal']) ? intval($data['golesLocal']) : null;
    $golesVisitante = isset($data['golesVisitante']) ? intval($data['golesVisitante']) : null;

    if (is_null($partidoId) || is_null($golesLocal) || is_null($golesVisitante)) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios.']);
        exit();
    }

    // Iniciar una transacción para garantizar integridad
    $conn->begin_transaction();
    try {
        // Actualizar el marcador en la tabla principal de partidos
        $queryPartido = "UPDATE partidos SET GolesLocal = ?, GolesVisitante = ? WHERE ID_partido = ?";
        if ($stmtPartido = $conn->prepare($queryPartido)) {
            $stmtPartido->bind_param("iii", $golesLocal, $golesVisitante, $partidoId);
            $stmtPartido->execute();
            $stmtPartido->close();
        } else {
            throw new Exception('Error al preparar la consulta para actualizar el marcador del partido.');
        }

        // Confirmar la transacción
        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Resultados guardados correctamente.']);
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit();
}
?>
