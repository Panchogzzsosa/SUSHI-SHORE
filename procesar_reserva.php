<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validar datos requeridos
    $required_fields = ['nombre', 'email', 'telefono', 'fecha', 'hora', 'personas'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            echo json_encode(['error' => 'Todos los campos son requeridos']);
            exit;
        }
    }

    // Validar que se haya seleccionado una mesa o asientos de barra
    if (empty($data['mesa']) && empty($data['asientos_barra'])) {
        echo json_encode(['error' => 'Debes seleccionar una mesa o asientos en la barra']);
        exit;
    }

    // Guardar la reserva en nuestra base de datos
    try {
        $stmt = $pdo->prepare("
            INSERT INTO reservas (
                nombre, email, telefono, fecha, hora, personas, 
                notas, estado, mesa, asientos_barra
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['telefono'],
            $data['fecha'],
            $data['hora'],
            $data['personas'],
            $data['notas'] ?? '',
            'confirmada',
            $data['mesa'] ?? null,
            $data['asientos_barra'] ?? null
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Reserva creada exitosamente'
        ]);
        
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al procesar la reserva: ' . $e->getMessage()]);
    }
    
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?> 