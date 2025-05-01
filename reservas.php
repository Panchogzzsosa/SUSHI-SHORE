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
        let svg = `<svg width="${svgWidth}" height="${svgHeight}" viewBox="0 0 ${svgWidth} ${svgHeight}">`;
        // Posiciones de las mesas (más separadas)
        const mesaPos = [
            {x:180, y:120}, {x:420, y:120}, // Mesas 1-2 (2p)
            {x:180, y:320}, {x:420, y:320}, {x:660, y:320}, // Mesas 3-5 (4p)
            {x:180, y:520}, {x:420, y:520}, // Mesas 6-7 (6p)
            {x:660, y:520} // Mesa 8 (8p)
        ];
        mesas.forEach((mesa, i) => {
            let mesaEstado = 'mesa-disponible-nueva';
            if (ocupacion[mesa.id]) mesaEstado = 'mesa-ocupada-nueva';
            if (personas > mesa.capacidad) mesaEstado = 'mesa-ocupada-nueva';
            if (personas > 0 && personas <= mesa.capacidad && !ocupacion[mesa.id]) mesaEstado = 'mesa-disponible-nueva';
            if (seleccion.mesa === mesa.id) mesaEstado = 'mesa-seleccionada-nueva';
            // Mesa como círculo
            let mesaRadio = 32 + mesa.capacidad * 2;
            svg += `<g class="mesa-svg-nueva ${mesaEstado}" data-id="${mesa.id}" tabindex="0">`;
            svg += `<circle cx="${mesaPos[i].x}" cy="${mesaPos[i].y}" r="${mesaRadio}" />`;
            // Sillas alrededor
            let anguloBase = -90;
            for(let s=0; s<mesa.capacidad; s++) {
                let angulo = anguloBase + (360/mesa.capacidad)*s;
                let rad = angulo * Math.PI / 180;
                let sillaX = mesaPos[i].x + Math.cos(rad)*(mesaRadio+22);
                let sillaY = mesaPos[i].y + Math.sin(rad)*(mesaRadio+22);
                let sillaEstado = mesaEstado;
                svg += `<circle cx="${sillaX}" cy="${sillaY}" r="12" class="${
                    mesaEstado==='mesa-ocupada-nueva' ? 'asiento-ocupado-nuevo' :
                    mesaEstado==='mesa-seleccionada-nueva' ? 'asiento-seleccionado-nuevo' :
                    'asiento-disponible-nuevo'}" />`;
            }
            // Solo el nombre de la mesa
            svg += `<text x="${mesaPos[i].x}" y="${mesaPos[i].y+6}" text-anchor="middle" class="mesa-label-nueva">${mesa.nombre}</text>`;
            svg += `</g>`;
        });
        // Barra (asientos individuales)
        svg += `<g><rect x="900" y="60" width="160" height="480" rx="24" fill="#222" stroke="#fff" stroke-width="2" />`;
        for(let i=0; i<barra.capacidad; i++) {
            let estado = 'asiento-disponible-nuevo';
            if (ocupacion.barra && ocupacion.barra.includes(i)) estado = 'asiento-ocupado-nuevo';
            if (seleccion.asientosBarra.includes(i)) estado = 'asiento-seleccionado-nuevo';
            svg += `<circle class="asiento-barra-nuevo ${estado}" data-idx="${i}" cx="980" cy="110" r="18" transform="translate(0,${i*42})" />`;
            svg += `<text x="980" y="115" transform="translate(0,${i*42})" text-anchor="middle" class="mesa-capacidad-nueva" font-size="0.8rem">${i+1}</text>`;
        }
        svg += `<text x="980" y="570" text-anchor="middle" class="mesa-label-nueva">Barra</text>`;
        svg += `<text x="980" y="590" text-anchor="middle" class="mesa-capacidad-nueva">10 asientos</text>`;
        svg += `</g>`;
        svg += `</svg>`;
        document.getElementById('svgMesasContainerNuevo').innerHTML = svg;
    }

    // --- Simulación de ocupación (esto debe venir del backend) ---
    function simularOcupacion() {
        // Aquí deberías hacer una petición AJAX al backend con fecha/hora
        // Simulación: mesa3 y mesa6 ocupadas, barra asientos 2,3,4 ocupados
        ocupacion = {
            mesa3: true,
            mesa6: true,
            barra: [2,3,4]
        };
    }

    // --- Lógica de selección ---
    document.addEventListener('change', function(e) {
        if(['fecha','hora','personas'].includes(e.target.id)) {
            seleccion = { mesa: null, asientosBarra: [] };
            simularOcupacion(); // Reemplazar por consulta real
            renderSVGPlanoNuevo();
            document.getElementById('mesa_seleccionada').value = '';
            document.getElementById('asientos_barra').value = '';
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
        }
    });

    // Inicializar
    simularOcupacion();
    renderSVGPlanoNuevo();

    flatpickr("#fecha", {
        minDate: "today",
        dateFormat: "Y-m-d",
        locale: "es"
    });
    </script>
</body>
</html> 