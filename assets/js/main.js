document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el calendario
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            selectable: true,
            select: function(info) {
                document.getElementById('fecha').value = info.startStr;
                verificarDisponibilidad();
            }
        });
        calendar.render();
    }

    // Verificar disponibilidad cuando cambia la fecha o hora
    const fechaInput = document.getElementById('fecha');
    const horaInput = document.getElementById('hora');
    const personasInput = document.getElementById('personas');

    if (fechaInput && horaInput && personasInput) {
        [fechaInput, horaInput, personasInput].forEach(input => {
            input.addEventListener('change', verificarDisponibilidad);
        });
    }
});

function verificarDisponibilidad() {
    const fecha = document.getElementById('fecha').value;
    const hora = document.getElementById('hora').value;
    const personas = document.getElementById('personas').value;

    if (!fecha || !hora || !personas) return;

    fetch('verificar_disponibilidad.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            fecha: fecha,
            hora: hora,
            personas: personas
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            mostrarError(data.error);
            return;
        }

        if (data.disponible) {
            document.getElementById('btnReservar').disabled = false;
            mostrarMensaje('Horario disponible', 'success');
        } else {
            document.getElementById('btnReservar').disabled = true;
            mostrarMensaje(data.mensaje, 'warning');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarError('Error al verificar disponibilidad');
    });
}

function mostrarMensaje(mensaje, tipo) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const formContainer = document.querySelector('.form-container');
    formContainer.insertBefore(alertDiv, formContainer.firstChild);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function mostrarError(mensaje) {
    mostrarMensaje(mensaje, 'danger');
}

// Inicializar AOS (Animate On Scroll)
AOS.init({
    duration: 800,
    offset: 100,
    once: true
});

// Inicializar Swiper para las categorías del menú
const menuCategorySwiper = new Swiper('.menuCategorySwiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
        },
        768: {
            slidesPerView: 3,
        },
        1024: {
            slidesPerView: 4,
        },
    }
});

// Inicializar Swiper para los platos destacados
const featuredDishesSwiper = new Swiper('.featuredDishesSwiper', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 3,
        },
    }
});

// Animaciones al hacer scroll
document.addEventListener('scroll', () => {
    const scrolled = window.scrollY;
    
    // Parallax effect para imágenes de fondo
    document.querySelectorAll('.parallax-bg').forEach(element => {
        const speed = element.dataset.speed || 0.5;
        element.style.transform = `translateY(${scrolled * speed}px)`;
    });
});

// Animación para las cards de experiencia al hacer hover
document.querySelectorAll('.experience-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.querySelector('.icon-wrapper').style.transform = 'scale(1.1) rotate(10deg)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.querySelector('.icon-wrapper').style.transform = 'scale(1) rotate(0deg)';
    });
});

// Animación para el menú de navegación al hacer scroll
const header = document.querySelector('header');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll <= 0) {
        header.classList.remove('scroll-up');
        return;
    }
    
    if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
        // Scroll hacia abajo
        header.classList.remove('scroll-up');
        header.classList.add('scroll-down');
    } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
        // Scroll hacia arriba
        header.classList.remove('scroll-down');
        header.classList.add('scroll-up');
    }
    
    lastScroll = currentScroll;
}); 