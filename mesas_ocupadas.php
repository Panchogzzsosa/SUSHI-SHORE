<?php
require_once 'config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $fecha = $data['fecha'] ?? null;
    $hora = $data['hora'] ?? null;
    if (!$fecha || !$hora) {
        echo json_encode(['error' => 'Faltan datos de fecha u hora']);
        exit;
    }

    // Buscar reservas confirmadas para esa fecha y hora
    $stmt = $pdo->prepare("SELECT mesa, asientos_barra FROM reservas WHERE fecha = ? AND hora = ? AND estado = 'confirmada'");
    $stmt->execute([$fecha, $hora]);
    $ocupadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $mesasOcupadas = [];
    $asientosBarraOcupados = [];
    foreach ($ocupadas as $res) {
        if (!empty($res['mesa'])) {
            $mesasOcupadas[] = $res['mesa'];
        }
        if (!empty($res['asientos_barra'])) {
            $asientos = explode(',', $res['asientos_barra']);
            foreach ($asientos as $a) {
                if (is_numeric($a)) {
                    $asientosBarraOcupados[] = (int)$a;
                }
            }
        }
    }
    echo json_encode([
        'mesas' => $mesasOcupadas,
        'barra' => $asientosBarraOcupados
    ]);
    exit;
} else {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
} 