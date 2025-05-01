<?php
require_once 'config/database.php';
require_once 'config/opentable.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['fecha']) || !isset($data['hora']) || !isset($data['personas'])) {
        echo json_encode(['error' => 'Faltan datos requeridos']);
        exit;
    }
    
    $fecha = $data['fecha'];
    $hora = $data['hora'];
    $personas = (int)$data['personas'];
    
    // Verificar disponibilidad usando la API de OpenTable
    $disponibilidad = checkAvailability($fecha, $hora, $personas);
    
    if (isset($disponibilidad['error'])) {
        echo json_encode(['error' => 'Error al verificar disponibilidad']);
        exit;
    }
    
    // Si hay disponibilidad, devolver los horarios disponibles
    if (isset($disponibilidad['available_times']) && !empty($disponibilidad['available_times'])) {
        echo json_encode([
            'disponible' => true,
            'horarios' => $disponibilidad['available_times']
        ]);
    } else {
        echo json_encode([
            'disponible' => false,
            'mensaje' => 'No hay disponibilidad para la fecha y hora seleccionadas'
        ]);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?> 