<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">SUSHI SHORE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">INICIO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#menu">MENÚ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#rooftop">ROOFTOP</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#nosotros">NOSOTROS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reservas.php">RESERVAR</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contacto">CONTACTO</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    // Cambiar el color del navbar al hacer scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script> 