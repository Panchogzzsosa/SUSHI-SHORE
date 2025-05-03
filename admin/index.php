<?php
session_start();
require_once '../config/database.php';

// Eliminar reserva si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $idEliminar = intval($_POST['eliminar_id']);
    $stmt = $pdo->prepare("DELETE FROM reservas WHERE id = ?");
    $stmt->execute([$idEliminar]);
    // Redirigir para evitar reenvío de formulario
    header('Location: index.php');
    exit;
}

// Obtener todas las reservas
$stmt = $pdo->query("SELECT * FROM reservas ORDER BY fecha DESC, hora DESC");
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Reservas - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="../assets/img/ICON.jpg" type="image/x-icon">
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4 text-center">Reservas Registradas</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-dark align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Personas</th>
                        <th>Mesa/Asientos</th>
                        <th>Estado</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo $reserva['id']; ?></td>
                            <td><?php echo htmlspecialchars($reserva['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['email']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['telefono']); ?></td>
                            <td><?php echo $reserva['fecha']; ?></td>
                            <td><?php echo substr($reserva['hora'], 0, 5); ?></td>
                            <td><?php echo $reserva['personas']; ?></td>
                            <td>
                                <?php
                                if (!empty($reserva['mesa'])) {
                                    echo htmlspecialchars($reserva['mesa']);
                                } elseif (!empty($reserva['asientos_barra'])) {
                                    // Mostrar los asientos de la barra de forma legible
                                    $asientos = array_map(function($n) { return ((int)$n)+1; }, explode(',', $reserva['asientos_barra']));
                                    echo 'Barra (asientos: ' . implode(', ', $asientos) . ')';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td><?php echo ucfirst($reserva['estado']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($reserva['notas'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm btn-eliminar-reserva" data-id="<?php echo $reserva['id']; ?>">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            ¿Seguro que deseas eliminar esta reserva?
          </div>
          <div class="modal-footer border-0">
            <form id="formEliminarReserva" method="post">
              <input type="hidden" name="eliminar_id" id="eliminar_id_modal">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Mostrar modal y pasar el id de la reserva a eliminar
    const modalEliminar = new bootstrap.Modal(document.getElementById('modalEliminar'));
    document.querySelectorAll('.btn-eliminar-reserva').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('eliminar_id_modal').value = this.dataset.id;
        modalEliminar.show();
      });
    });
    </script>
</body>
</html> 