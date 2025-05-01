<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sushi Shore - Restaurante & Rooftop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- OpenTable Widget -->
    <script type="text/javascript" src="//www.opentable.com/widget/reservation/loader?rid=YOUR_RESTAURANT_ID&type=standard&theme=standard&color=1&iframe=true&domain=com&lang=es-MX&newtab=false&ot_source=Restaurant%20website"></script>
</head>
<body>
    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1>SUSHI SHORE</h1>
                    <p>RESTAURANTE & ROOFTOP</p>
                    <!-- OpenTable Widget Button -->
                    <div class="ot-button-holder mt-4">
                        <script type="text/javascript">
                            var OT_Button = OT_Button || {};
                            OT_Button.id = "ot-reservation-btn";
                            OT_Button.uuid = "YOUR_RESTAURANT_ID"; // Reemplaza con tu ID de OpenTable
                            OT_Button.domain = "com";
                            OT_Button.lang = "es-MX";
                            OT_Button.theme = "dark"; // Tema oscuro para que coincida con el diseño
                            OT_Button.width = 200;
                            OT_Button.height = 50;
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Concept Section -->
    <section class="concept-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="concept-card">
                        <img src="assets/img/sushi-restaurant.jpg" alt="Restaurante Sushi Shore">
                        <div class="card-body">
                            <h3 class="card-title">RESTAURANTE</h3>
                            <p class="card-text">Disfruta de la auténtica experiencia culinaria japonesa en nuestro restaurante de sushi. Nuestros chefs expertos preparan cada plato con ingredientes frescos y de alta calidad, ofreciendo una variedad de rolls, nigiri y sashimi que deleitarán tus sentidos.</p>
                            <a href="#menu" class="btn btn-primary">VER MENÚ</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="concept-card">
                        <img src="assets/img/rooftop.jpg" alt="Rooftop Sushi Shore">
                        <div class="card-body">
                            <h3 class="card-title">ROOFTOP</h3>
                            <p class="card-text">Asciende a nuestro exclusivo rooftop y sumérgete en una atmósfera vibrante con DJs en vivo y música house. Disfruta de bebidas artesanales mientras contemplas vistas impresionantes de la ciudad. La experiencia perfecta para una noche inolvidable.</p>
                            <a href="#rooftop" class="btn btn-primary">EXPLORAR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section con Swiper -->
    <section id="menu" class="menu-section">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">NUESTRO MENÚ</h2>
            
            <!-- Swiper para Categorías -->
            <div class="swiper menuCategorySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" data-aos="fade-up" data-aos-delay="100">
                        <div class="category-card">
                            <img src="assets/img/rolls.jpg" alt="Rolls Especiales">
                            <h3>ROLLS ESPECIALES</h3>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-delay="200">
                        <div class="category-card">
                            <img src="assets/img/nigiri.jpg" alt="Nigiri">
                            <h3>NIGIRI</h3>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-delay="300">
                        <div class="category-card">
                            <img src="assets/img/sashimi.jpg" alt="Sashimi">
                            <h3>SASHIMI</h3>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-delay="400">
                        <div class="category-card">
                            <img src="assets/img/bebidas.jpg" alt="Bebidas">
                            <h3>BEBIDAS</h3>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

            <!-- Galería de Platos Destacados -->
            <div class="featured-dishes mt-5">
                <h3 class="text-center mb-4" data-aos="fade-up">PLATOS DESTACADOS</h3>
                <div class="swiper featuredDishesSwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" data-aos="zoom-in">
                            <div class="dish-card">
                                <img src="assets/img/dish1.jpg" alt="Plato 1">
                                <div class="dish-info">
                                    <h4>Philadelphia roll</h4>
                                    <p>Queso crema, salmón, pepino y aguacate</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide" data-aos="zoom-in">
                            <div class="dish-card">
                                <img src="assets/img/dish2.jpg" alt="Plato 2">
                                <div class="dish-info">
                                    <h4>Rainbow Roll</h4>
                                    <p>Variedad de pescados sobre California Roll</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide" data-aos="zoom-in">
                            <div class="dish-card">
                                <img src="assets/img/dish3.jpg" alt="Plato 3">
                                <div class="dish-info">
                                    <h4>Sashimi Deluxe</h4>
                                    <p>Selección premium de pescados frescos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experiencias Section -->
    <section class="experiences-section py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">EXPERIENCIAS ÚNICAS</h2>
            <div class="row">
                <div class="col-md-4" data-aos="fade-right">
                    <div class="experience-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>CENA OMAKASE</h3>
                        <p>Déjate sorprender por nuestro chef con un menú exclusivo de degustación</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up">
                    <div class="experience-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-cocktail"></i>
                        </div>
                        <h3>MIXOLOGÍA</h3>
                        <p>Descubre nuestros cócteles de autor con un toque japonés</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-left">
                    <div class="experience-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-music"></i>
                        </div>
                        <h3>DJ SESSIONS</h3>
                        <p>Disfruta de la mejor música en nuestro rooftop</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rooftop Section -->
    <section id="rooftop" class="rooftop-section">
        <div class="container">
            <div class="row align-items-center">
                
        
            </div>
        </div>
    </section>

    <!-- Reservation Section -->
    <section class="reservation-section-minimal py-5">
        <div class="container text-center">
            <h2 class="mb-3" style="color:#fff; letter-spacing:2px;">RESERVA TU EXPERIENCIA</h2>
            <p class="mb-4" style="color:rgba(255,255,255,0.7); font-size:1.1rem;">Reserva tu mesa y vive una noche inolvidable en Sushi Shore.</p>
            <a href="reservas.php" class="btn btn-primary btn-lg" style="border-radius:30px; padding:0.8rem 2.5rem; font-size:1.1rem;">RESERVAR AHORA</a>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="ubicacion" class="location-section py-5">
        <div class="container">
            <h2 class="text-center mb-4" style="color:#fff; letter-spacing:2px;">UBICACIÓN</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="map-responsive mb-4">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3601.263211950843!2d-100.18535492465325!3d25.496266819522393!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8662c8a7edae41c3%3A0x8b47f315f3e8c1b2!2sFelicitos%20Guajardo%2C%20Zitoon%20Yerbaniz%2C%20El%20Yerbaniz%2C%2067302%20Santiago%2C%20N.L.!5e0!3m2!1ses-419!2smx!4v1746126135994!5m2!1ses-419!2smx" width="100%" height="350" style="border:0; border-radius:12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="text-center" style="color:#fff;">
                        <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i> Felicitos Guajardo, Zitoon Yerbaniz, El Yerbaniz, 67302 Santiago, N.L.</p>
                        <p class="mb-1"><i class="fas fa-phone me-2"></i> +123 456 7890</p>
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i> info@sushishore.com</p>
                        <p class="mb-0"><i class="fas fa-clock me-2"></i> Restaurante: Lunes a Domingo, 12:00 - 23:00 | Rooftop: Jueves a Sábado, 20:00 - 02:00</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-minimal">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-4">
                    <h5 class="mb-0">SUSHI SHORE</h5>
                </div>
                <div class="col-md-4 text-center">
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <p class="mb-0">&copy; 2025 Sushi Shore</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 