<?php
session_start();
require_once '../config/database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener filtros
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d', strtotime('+7 days'));
$tipo_servicio = isset($_GET['tipo_servicio']) ? $_GET['tipo_servicio'] : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';

// Construir la consulta
$query = "SELECT * FROM reservas WHERE fecha BETWEEN ? AND ?";
$params = [$fecha_inicio, $fecha_fin];

if ($tipo_servicio) {
    $query .= " AND tipo_servicio = ?";
    $params[] = $tipo_servicio;
}

if ($estado) {
    $query .= " AND estado = ?";
    $params[] = $estado;
}

$query .= " ORDER BY fecha, hora";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$reservas = $stmt->fetchAll();

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reserva_id = filter_input(INPUT_POST, 'reserva_id', FILTER_SANITIZE_NUMBER_INT);
    $accion = filter_input(INPUT_POST, 'accion', FILTER_SANITIZE_STRING);
    
    if ($reserva_id && $accion) {
        switch ($accion) {
            case 'confirmar':
                $stmt = $pdo->prepare("UPDATE reservas SET estado = 'confirmada' WHERE id = ?");
                break;
            case 'cancelar':
                $stmt = $pdo->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id = ?");
                break;
            case 'eliminar':
                $stmt = $pdo->prepare("DELETE FROM reservas WHERE id = ?");
                break;
        }
        
        if (isset($stmt)) {
            $stmt->execute([$reserva_id]);
            header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Reservas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Panel de Administración</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Reservas</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link">Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h2>Gestión de Reservas</h2>
                
                <!-- Filtros -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="tipo_servicio" class="form-label">Tipo de Servicio</label>
                        <select class="form-control" id="tipo_servicio" name="tipo_servicio">
                            <option value="">Todos</option>
                            <option value="restaurante" <?php echo $tipo_servicio === 'restaurante' ? 'selected' : ''; ?>>Restaurante</option>
                            <option value="consultorio" <?php echo $tipo_servicio === 'consultorio' ? 'selected' : ''; ?>>Consultorio</option>
                            <option value="peluqueria" <?php echo $tipo_servicio === 'peluqueria' ? 'selected' : ''; ?>>Peluquería</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="">Todos</option>
                            <option value="pendiente" <?php echo $estado === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="confirmada" <?php echo $estado === 'confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                            <option value="cancelada" <?php echo $estado === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </form>

                <!-- Tabla de reservas -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><?php echo $reserva['id']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($reserva['nombre']); ?><br>
                                        <small><?php echo htmlspecialchars($reserva['email']); ?></small>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($reserva['fecha'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($reserva['hora'])); ?></td>
                                    <td><?php echo ucfirst($reserva['tipo_servicio']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $reserva['estado'] === 'confirmada' ? 'success' : 
                                                ($reserva['estado'] === 'cancelada' ? 'danger' : 'warning'); 
                                        ?>">
                                            <?php echo ucfirst($reserva['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($reserva['estado'] === 'pendiente'): ?>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                                    <input type="hidden" name="accion" value="confirmar">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Confirmar">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <?php if ($reserva['estado'] !== 'cancelada'): ?>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                                    <input type="hidden" name="accion" value="cancelar">
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Cancelar">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <form method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta reserva?');">
                                                <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                                <input type="hidden" name="accion" value="eliminar">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 