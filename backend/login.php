<?php
session_start();
require_once 'conexion.php';  // Asegúrate de que la conexión a la base de datos esté correctamente configurada

// Permitir CORS
$allowedOrigins = ['http://localhost:8082'];  // Asegura que estos son los puertos correctos de tu frontend
if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
}

header('Access-Control-Allow-Methods: POST, GET, OPTIONS');  // Permitir métodos POST y GET
header('Access-Control-Allow-Headers: Content-Type');

// Si es una solicitud OPTIONS (preflight request de CORS), respondemos con 200
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario a través de POST
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $contraseña = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';

    // Preparar la consulta SQL para obtener el árbitro
    $query = "SELECT * FROM arbitros WHERE Usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró el árbitro
    if ($arbitro = $resultado->fetch_assoc()) {
        // Comparar la contraseña proporcionada directamente con la almacenada en la base de datos
        if ($contraseña == $arbitro['Contraseña']) {
            // La contraseña es correcta, almacenamos la sesión
            $_SESSION['ID_arbitro'] = $arbitro['ID_arbitro'];
            $_SESSION['usuario'] = $arbitro['Usuario'];
            $_SESSION['nombre'] = $arbitro['Nombre'];
            $_SESSION['telefono'] = $arbitro['Telefono'];

            // Responder con un mensaje de éxito
            echo json_encode([
                'status' => 'success',
                'usuario' => $arbitro['Usuario'],
                'nombre' => $arbitro['Nombre'],
                'telefono' => $arbitro['Telefono'],
                'ID_arbitro' => $arbitro['ID_arbitro'] // Enviar el ID_arbitro
            ]);
            exit();
        } else {
            // Si la contraseña no es correcta
            echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
        }
    } else {
        // Si no se encontró el usuario
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
    }

    $stmt->close();
}
?>
