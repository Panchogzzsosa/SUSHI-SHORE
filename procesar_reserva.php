<?php
require_once 'config/database.php';
require_once 'config/opentable.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validar datos requeridos
    $required_fields = ['nombre', 'email', 'telefono', 'fecha', 'hora', 'personas', 'notas'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            echo json_encode(['error' => 'Todos los campos son requeridos']);
            exit;
        }
    }
    
    // Preparar datos para la reserva en OpenTable
    $reservationData = [
        'restaurant_id' => OPENTABLE_RESTAURANT_ID,
        'date' => $data['fecha'],
        'time' => $data['hora'],
        'party_size' => (int)$data['personas'],
        'customer' => [
            'first_name' => $data['nombre'],
            'email' => $data['email'],
            'phone' => $data['telefono']
        ],
        'notes' => $data['notas']
    ];
    
    // Crear reserva en OpenTable
    $result = createReservation($reservationData);
    
    if (isset($result['error'])) {
        echo json_encode(['error' => 'Error al crear la reserva']);
        exit;
    }
    
    // Guardar la reserva en nuestra base de datos
    try {
        $stmt = $pdo->prepare("
            INSERT INTO reservas (
                nombre, email, telefono, fecha, hora, personas, 
                notas, opentable_id, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['telefono'],
            $data['fecha'],
            $data['hora'],
            $data['personas'],
            $data['notas'],
            $result['reservation_id'],
            'confirmada'
        ]);
        
        // Enviar email de confirmación
        $to = $data['email'];
        $subject = "Confirmación de Reserva - Sushi Delight";
        $message = "
            Hola {$data['nombre']},
            
            Tu reserva ha sido confirmada para el día {$data['fecha']} a las {$data['hora']}.
            
            Detalles de la reserva:
            - Fecha: {$data['fecha']}
            - Hora: {$data['hora']}
            - Número de personas: {$data['personas']}
            - Notas: {$data['notas']}
            
            Si necesitas modificar o cancelar tu reserva, por favor contáctanos.
            
            ¡Gracias por elegir Sushi Delight!
        ";
        
        $headers = "From: reservas@sushidelight.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        mail($to, $subject, $message, $headers);
        
        echo json_encode([
            'success' => true,
            'message' => 'Reserva creada exitosamente',
            'reservation_id' => $result['reservation_id']
        ]);
        
    } catch (PDOException $e) {
        // Si hay error al guardar en la base de datos, cancelar la reserva en OpenTable
        cancelReservation($result['reservation_id']);
        
        echo json_encode(['error' => 'Error al procesar la reserva']);
    }
    
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?> 