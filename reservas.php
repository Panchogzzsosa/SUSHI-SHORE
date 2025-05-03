<?php
require_once 'config/database.php';
$tipo_servicio = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$titulos = [
    'restaurante' => 'Reserva de Mesa',
    'consultorio' => 'Agenda de Cita Médica',
    'peluqueria' => 'Reserva de Cita en Peluquería'
];
$titulo = isset($titulos[$tipo_servicio]) ? $titulos[$tipo_servicio] : 'Sistema de Reservas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Mesa - Sushi Shore</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="shortcut icon" href="assets/img/ICON.jpg" type="image/x-icon">
    <style>
        body {
            background: var(--dark-color, #111111);
        }
        .reserva-minimal {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reserva-form {
            background: transparent;
            padding: 0;
            border-radius: 0;
            box-shadow: none;
            color: #fff;
        }
        .reserva-form h2 {
            color: #fff;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 2rem;
        }
        .reserva-form label {
            color: #bbb;
            font-size: 0.95rem;
            margin-bottom: 0.3rem;
        }
        .reserva-form .form-control, .reserva-form .form-select, .reserva-form textarea {
            background: #181818;
            border: none;
            border-radius: 8px;
            color: #fff;
            margin-bottom: 1.2rem;
        }
        .reserva-form .form-control:focus, .reserva-form .form-select:focus, .reserva-form textarea:focus {
            background: #222;
            color: #fff;
            box-shadow: 0 0 0 2px var(--secondary-color, #c41e3a);
        }
        .reserva-form button {
            width: 100%;
            border-radius: 30px;
            padding: 0.8rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            background: var(--secondary-color, #c41e3a);
            border: none;
            color: #fff;
            transition: background 0.2s;
            margin-top: 4rem;
        }
        .reserva-form button:hover {
            background: #a81a32;
        }
        .calendar-minimal {
            background: transparent;
            border-radius: 0;
            box-shadow: none;
            margin-top: 2rem;
        }
        .svg-mesas-nuevo {
            background: transparent;
            border-radius: 0;
            box-shadow: none;
            margin-top: 2rem;
            margin-left: -120px;
        }
        .mesa-svg-nueva {
            cursor: pointer;
            transition: filter 0.2s, stroke 0.2s;
        }
        .mesa-disponible-nueva {
            fill: #2ecc40;
        }
        .mesa-ocupada-nueva {
            fill: #c41e3a;
            cursor: not-allowed;
            filter: grayscale(0.7) brightness(0.7);
        }
        .mesa-seleccionada-nueva {
            fill: #2986f5;
            stroke: #fff;
            stroke-width: 4px;
        }
        .asiento-barra-nuevo {
            cursor: pointer;
            transition: filter 0.2s, stroke 0.2s;
        }
        .asiento-disponible-nuevo {
            fill: #2ecc40;
        }
        .asiento-ocupado-nuevo {
            fill: #c41e3a;
            cursor: not-allowed;
            filter: grayscale(0.7) brightness(0.7);
        }
        .asiento-seleccionado-nuevo {
            fill: #2986f5;
            stroke: #fff;
            stroke-width: 2px;
        }
        .mesa-label-nueva {
            font-size: 1.1rem;
            fill: #fff;
            font-family: 'Montserrat', sans-serif;
            pointer-events: none;
            font-weight: 600;
        }
        .mesa-capacidad-nueva {
            font-size: 0.9rem;
            fill: #fff;
            font-family: 'Montserrat', sans-serif;
            pointer-events: none;
        }
        @media (max-width: 900px) {
            .svg-mesas-nuevo svg {
                max-width: 100%;
            }
        }
        /* Animación minimalista para el modal de reserva */
        .modal.fade .modal-dialog {
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.3s cubic-bezier(.4,0,.2,1);
        }
        .modal.fade.show .modal-dialog {
            transform: scale(1);
            opacity: 1;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container reserva-minimal py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-8 col-md-10">
                <form id="formReserva" class="reserva-form needs-validation" novalidate>
                    <br>
                    <br>
                    <br>
                    <h2 class="text-center mb-4">Reservar Mesa</h2>
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                    <div class="invalid-feedback">Por favor ingrese su nombre</div>

                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Por favor ingrese un email válido</div>

                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                    <div class="invalid-feedback">Por favor ingrese su teléfono</div>

                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                    <div class="invalid-feedback">Por favor seleccione una fecha</div>

                    <label for="hora" class="form-label">Hora</label>
                    <select class="form-select" id="hora" name="hora" required>
                        <option value="">Seleccione...</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                    </select>
                    <div class="invalid-feedback">Por favor seleccione una hora</div>

                    <label for="personas" class="form-label">Número de personas</label>
                    <select class="form-select" id="personas" name="personas" required>
                        <option value="">Seleccione...</option>
                        <?php for($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?> persona<?php echo $i > 1 ? 's' : ''; ?></option>
                        <?php endfor; ?>
                    </select>
                    <div class="invalid-feedback">Por favor seleccione el número de personas</div>

                    <label for="notas" class="form-label">Notas adicionales</label>
                    <textarea class="form-control" id="notas" name="notas" rows="2"></textarea>

                    <!-- SVG Plano de Mesas NUEVO -->
                    <div class="svg-mesas-nuevo" id="svgMesasContainerNuevo">
                        <!-- SVG generado por JS -->
                    </div>
                    <input type="hidden" name="mesa_seleccionada" id="mesa_seleccionada">
                    <input type="hidden" name="asientos_barra" id="asientos_barra">

                    <div class="mb-5"></div>
                    <button type="submit" class="btn btn-primary mt-2" id="btnReservar" disabled>Reservar Mesa</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
    // --- Configuración de mesas y barra ---
    const mesas = [
        { id: 'mesa1', nombre: 'Mesa 1', capacidad: 2 },
        { id: 'mesa2', nombre: 'Mesa 2', capacidad: 2 },
        { id: 'mesa3', nombre: 'Mesa 3', capacidad: 4 },
        { id: 'mesa4', nombre: 'Mesa 4', capacidad: 4 },
        { id: 'mesa5', nombre: 'Mesa 5', capacidad: 4 },
        { id: 'mesa6', nombre: 'Mesa 6', capacidad: 6 },
        { id: 'mesa7', nombre: 'Mesa 7', capacidad: 6 },
        { id: 'mesa8', nombre: 'Mesa 8', capacidad: 8 }
    ];
    const barra = { id: 'barra', nombre: 'Barra', capacidad: 10 };
    let ocupacion = {
        // Ejemplo: 'mesa1': true, 'barra': [0,1,2] (índices ocupados)
    };
    let seleccion = {
        mesa: null,
        asientosBarra: []
    };

    // --- Renderizar SVG NUEVO con mesas y sillas vistas desde arriba ---
    function renderSVGPlanoNuevo() {
        const personas = parseInt(document.getElementById('personas').value) || 0;
        const svgWidth = 1100;
        const svgHeight = 600;
        let svg = `<svg width="${svgWidth}" height="${svgHeight}" viewBox="0 0 ${svgWidth} ${svgHeight}" style="background:transparent;">`;
        // Posiciones de las mesas (más separadas)
        const mesaPos = [
            {x:180, y:120}, {x:420, y:120}, // Mesas 1-2 (2p)
            {x:180, y:320}, {x:420, y:320}, {x:660, y:320}, // Mesas 3-5 (4p)
            {x:180, y:520}, {x:420, y:520}, // Mesas 6-7 (6p)
            {x:660, y:520} // Mesa 8 (8p)
        ];
        mesas.forEach((mesa, i) => {
            let mesaEstado = 'disponible';
            if (ocupacion[mesa.id]) mesaEstado = 'ocupada';
            if (personas > mesa.capacidad) mesaEstado = 'ocupada';
            if (personas > 0 && personas <= mesa.capacidad && !ocupacion[mesa.id]) mesaEstado = 'disponible';
            if (seleccion.mesa === mesa.id) mesaEstado = 'seleccionada';
            // Colores minimalistas
            let color = mesaEstado==='disponible' ? '#2ecc40' : mesaEstado==='ocupada' ? '#c41e3a' : '#2986f5';
            // Mesa como cuadrado
            let mesaLado = 48 + mesa.capacidad * 6;
            let x = mesaPos[i].x - mesaLado/2;
            let y = mesaPos[i].y - mesaLado/2;
            svg += `<g class="mesa-svg-nueva" data-id="${mesa.id}" tabindex="0" style="cursor:pointer;">`;
            svg += `<rect x="${x}" y="${y}" width="${mesaLado}" height="${mesaLado}" rx="10" fill="${color}" />`;
            // Sillas como rectángulos minimalistas
            let sillas = mesa.capacidad;
            let anguloBase = -90;
            for(let s=0; s<sillas; s++) {
                let angulo = anguloBase + (360/sillas)*s;
                let rad = angulo * Math.PI / 180;
                let sillaX = mesaPos[i].x + Math.cos(rad)*(mesaLado/2+18) - 8;
                let sillaY = mesaPos[i].y + Math.sin(rad)*(mesaLado/2+18) - 8;
                let rot = angulo+90;
                svg += `<rect x="${sillaX}" y="${sillaY}" width="16" height="16" rx="4" fill="#fff" stroke="#bbb" stroke-width="1.5" transform="rotate(${rot},${sillaX+8},${sillaY+8})" />`;
            }
            // Número de mesa minimalista
            svg += `<text x="${mesaPos[i].x}" y="${mesaPos[i].y+7}" text-anchor="middle" font-size="1.3rem" fill="#222" font-family="'Montserrat',sans-serif" font-weight="600">${i+1}</text>`;
            svg += `</g>`;
        });
        // Barra minimalista: línea blanca y puntos
        svg += `<g>`;
        svg += `<rect x="900" y="60" width="160" height="480" rx="24" fill="none" stroke="#fff" stroke-width="2" />`;
        for(let i=0; i<barra.capacidad; i++) {
            let estado = 'disponible';
            if (ocupacion.barra && ocupacion.barra.includes(i)) estado = 'ocupada';
            if (seleccion.asientosBarra.includes(i)) estado = 'seleccionada';
            let color = estado==='disponible' ? '#2ecc40' : estado==='ocupada' ? '#c41e3a' : '#2986f5';
            svg += `<circle class="asiento-barra-nuevo" data-idx="${i}" cx="980" cy="110" r="13" transform="translate(0,${i*42})" fill="${color}" style="cursor:pointer;" />`;
            svg += `<text x="980" y="115" transform="translate(0,${i*42})" text-anchor="middle" font-size="0.8rem" fill="#fff" font-family="'Montserrat',sans-serif">${i+1}</text>`;
        }
        svg += `<text x="980" y="570" text-anchor="middle" font-size="1rem" fill="#fff" font-family="'Montserrat',sans-serif">Barra</text>`;
        svg += `<text x="980" y="590" text-anchor="middle" font-size="0.8rem" fill="#fff" font-family="'Montserrat',sans-serif">10 asientos</text>`;
        svg += `</g>`;
        svg += `</svg>`;
        document.getElementById('svgMesasContainerNuevo').innerHTML = svg;
    }

    // --- Ocupación real desde backend ---
    async function cargarOcupacionReal() {
        const fecha = document.getElementById('fecha').value;
        const hora = document.getElementById('hora').value;
        if (!fecha || !hora) {
            ocupacion = {};
            renderSVGPlanoNuevo();
            return;
        }
        try {
            const response = await fetch('mesas_ocupadas.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ fecha, hora })
            });
            const data = await response.json();
            ocupacion = {
                ...Object.fromEntries((data.mesas||[]).map(m=>[m,true])),
                barra: data.barra || []
            };
            renderSVGPlanoNuevo();
        } catch (e) {
            ocupacion = {};
            renderSVGPlanoNuevo();
        }
    }

    // --- Lógica de selección ---
    document.addEventListener('change', function(e) {
        if(['fecha','hora','personas'].includes(e.target.id)) {
            seleccion = { mesa: null, asientosBarra: [] };
            cargarOcupacionReal();
            document.getElementById('mesa_seleccionada').value = '';
            document.getElementById('asientos_barra').value = '';
            actualizarBotonReserva();
        }
    });

    document.getElementById('svgMesasContainerNuevo').addEventListener('click', function(e) {
        const personas = parseInt(document.getElementById('personas').value) || 0;
        if(e.target.closest('.mesa-svg-nueva')) {
            const mesaId = e.target.closest('.mesa-svg-nueva').getAttribute('data-id');
            // Solo permitir seleccionar si está disponible
            if(!ocupacion[mesaId] && personas > 0 && personas <= mesas.find(m=>m.id===mesaId).capacidad) {
                seleccion = { mesa: mesaId, asientosBarra: [] };
                document.getElementById('mesa_seleccionada').value = mesaId;
                document.getElementById('asientos_barra').value = '';
                renderSVGPlanoNuevo();
                actualizarBotonReserva();
            }
        }
        if(e.target.classList.contains('asiento-barra-nuevo')) {
            const idx = parseInt(e.target.getAttribute('data-idx'));
            if(ocupacion.barra && ocupacion.barra.includes(idx)) return;
            if(seleccion.mesa) seleccion.mesa = null;
            // Selección múltiple hasta el número de personas
            if(seleccion.asientosBarra.includes(idx)) {
                seleccion.asientosBarra = seleccion.asientosBarra.filter(i=>i!==idx);
            } else {
                if(seleccion.asientosBarra.length < personas) {
                    seleccion.asientosBarra.push(idx);
                }
            }
            document.getElementById('mesa_seleccionada').value = '';
            document.getElementById('asientos_barra').value = seleccion.asientosBarra.join(',');
            renderSVGPlanoNuevo();
            actualizarBotonReserva();
        }
    });

    // Inicializar
    cargarOcupacionReal();
    renderSVGPlanoNuevo();

    flatpickr("#fecha", {
        minDate: "today",
        dateFormat: "Y-m-d",
        locale: "es"
    });

    // --- Habilitar el botón de reserva solo si hay selección válida ---
    function actualizarBotonReserva() {
        const nombre = document.getElementById('nombre').value.trim();
        const email = document.getElementById('email').value.trim();
        const telefono = document.getElementById('telefono').value.trim();
        const fecha = document.getElementById('fecha').value.trim();
        const hora = document.getElementById('hora').value.trim();
        const personas = parseInt(document.getElementById('personas').value) || 0;
        const mesaSeleccionada = document.getElementById('mesa_seleccionada').value;
        const asientosBarra = document.getElementById('asientos_barra').value;
        const btn = document.getElementById('btnReservar');
        // Validar todos los campos obligatorios
        const camposLlenos = nombre && email && telefono && fecha && hora && personas > 0;
        const seleccionValida = (mesaSeleccionada && personas > 0) || (asientosBarra && asientosBarra.split(',').length === personas);
        if (camposLlenos && seleccionValida) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }

    // Llama a esta función cada vez que se actualiza la selección
    document.addEventListener('change', actualizarBotonReserva);
    document.getElementById('svgMesasContainerNuevo').addEventListener('click', actualizarBotonReserva);

    // Mostrar modal al reservar y enviar datos a la base de datos
    let reservaEnviada = false;
    document.getElementById('formReserva').addEventListener('submit', async function(e) {
        e.preventDefault();
        if (reservaEnviada) return;
        reservaEnviada = true;
        
        const btn = document.getElementById('btnReservar');
        btn.disabled = true; // Deshabilita para evitar doble envío
        // Obtener datos del formulario
        const nombre = document.getElementById('nombre').value;
        const email = document.getElementById('email').value;
        const telefono = document.getElementById('telefono').value;
        const fecha = document.getElementById('fecha').value;
        const hora = document.getElementById('hora').value;
        const personas = document.getElementById('personas').value;
        const notas = document.getElementById('notas').value;
        const mesa = document.getElementById('mesa_seleccionada').value;
        const asientosBarra = document.getElementById('asientos_barra').value;
        // Enviar datos por AJAX
        try {
            const response = await fetch('procesar_reserva.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    nombre, email, telefono, fecha, hora, personas, notas,
                    mesa: mesa,
                    asientos_barra: asientosBarra
                })
            });
            const data = await response.json();
            if (data.success) {
                // Mostrar info en el modal
                document.getElementById('modalNombre').innerText = nombre;
                document.getElementById('modalEmail').innerText = email;
                document.getElementById('modalTelefono').innerText = telefono;
                document.getElementById('modalFecha').innerText = fecha;
                document.getElementById('modalHora').innerText = hora;
                document.getElementById('modalPersonas').innerText = personas;
                document.getElementById('modalNotas').innerText = notas;
                if (mesa) {
                    document.getElementById('modalMesa').innerText = mesa;
                } else if (asientosBarra) {
                    document.getElementById('modalMesa').innerText = 'Barra (asientos: ' + asientosBarra.split(',').map(n => parseInt(n)+1).join(', ') + ')';
                } else {
                    document.getElementById('modalMesa').innerText = '-';
                }
                // Mostrar el modal
                const modal = new bootstrap.Modal(document.getElementById('modalReserva'));
                modal.show();
                // Limpiar el formulario y selección
                this.reset();
                document.getElementById('mesa_seleccionada').value = '';
                document.getElementById('asientos_barra').value = '';
                seleccion = { mesa: null, asientosBarra: [] };
                renderSVGPlanoNuevo();
                actualizarBotonReserva();
            } else {
                alert(data.error || 'Error al procesar la reserva.');
                btn.disabled = false; // Solo vuelve a habilitar si hay error
                reservaEnviada = false;
            }
        } catch (err) {
            alert('Error de conexión. Intenta de nuevo.');
            btn.disabled = false;
            reservaEnviada = false;
        }
    });
    </script>
    <!-- Modal de confirmación de reserva -->
    <div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="modalReservaLabel">¡Reserva realizada con éxito!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="modalNombre"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p><strong>Teléfono:</strong> <span id="modalTelefono"></span></p>
                    <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                    <p><strong>Hora:</strong> <span id="modalHora"></span></p>
                    <p><strong>Personas:</strong> <span id="modalPersonas"></span></p>
                    <p><strong>Mesa/Asientos:</strong> <span id="modalMesa"></span></p>
                    <p class="mb-0"><strong>Notas:</strong> <span id="modalNotas"></span></p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 