<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Semana DSI - Túpac Amaru Cusco')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS (Animaciones) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- FontAwesome (Iconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts (Outfit es moderna y tecnológica) -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">

    <style>
        /* PALETA DE COLORES BASADA EN LA IMAGEN */
        :root {
            --color-dark: #1A1423;      /* Fondo oscuro principal */
            --color-purple: #5D3A7A;    /* Violeta */
            --color-wine: #701C21;      /* Rojo Vino */
            --color-terracotta: #C2594A;/* Rojo Terracota */
            --color-sand: #E4B88D;      /* Durazno/Arena */
            --color-white: #FDFBF7;     /* Blanco cálido */
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--color-white);
            color: #333;
        }

        /* Navbar Personalizado */
        .navbar-custom {
            background-color: rgba(26, 20, 35, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }
        
        .navbar-custom .nav-link {
            color: var(--color-sand);
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: var(--color-terracotta);
        }

        /* Botón Principal */
        .btn-custom {
            background-color: var(--color-terracotta);
            color: white;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--color-wine);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(194, 89, 74, 0.4);
        }

        .btn-outline-custom {
            border: 2px solid var(--color-sand);
            color: var(--color-sand);
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background-color: var(--color-sand);
            color: var(--color-dark);
        }

        /* Footer */
        footer {
            background-color: var(--color-dark);
            color: var(--color-sand);
            border-top: 3px solid var(--color-purple);
        }
        footer a { color: var(--color-sand); transition: 0.3s; text-decoration: none; }
        footer a:hover { color: var(--color-terracotta); }
    </style>
    @stack('styles')
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top py-3">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="{{ route('home') }}">
                <div class="bg-purple rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; background-color: var(--color-purple);">
                    <i class="fa-solid fa-code text-white"></i>
                </div>
                <div>
                    <span class="d-block lh-1 fs-5">Semana DSI</span>
                    <small class="text-sand lh-1" style="font-size: 0.7rem; color: var(--color-sand);">Túpac Amaru</small>
                </div>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars text-white fs-4"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('schedule') }}">Cronograma</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Galería</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('certificates.search') }}">Certificados</a></li>
                    <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                        <a class="btn btn-custom" href="{{ route('registration.create') }}">Inscríbete</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mb-4">
                    <h5 class="text-white mb-3 fw-bold"><i class="fa-solid fa-laptop-code me-2" style="color: var(--color-terracotta);"></i>Semana DSI 2026</h5>
                    <p class="small opacity-75">Organizado por la carrera de Desarrollo de Sistemas de Información del Instituto Túpac Amaru de Cusco. Un espacio para la innovación y el futuro tecnológico.</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-white mb-3 fw-bold">Enlaces</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('schedule') }}">Cronograma</a></li>
                        <li class="mb-2"><a href="{{ route('registration.create') }}">Inscripciones</a></li>
                        <li class="mb-2"><a href="{{ route('certificates.search') }}">Certificados</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3 fw-bold">Contacto y Redes</h5>
                    <p class="small mb-2"><i class="fa-solid fa-location-dot me-2"></i> Cusco, Perú</p>
                    <div class="d-flex gap-3 fs-5 mt-3">
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary opacity-25">
            <div class="text-center small opacity-50">&copy; {{ date('Y') }} Carrera de Desarrollo de Sistemas de Información - Instituto Túpac Amaru. Todos los derechos reservados.</div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        // Efecto Navbar al hacer scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(26, 20, 35, 0.98)';
                navbar.classList.add('shadow-lg');
            } else {
                navbar.style.backgroundColor = 'rgba(26, 20, 35, 0.95)';
                navbar.classList.remove('shadow-lg');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>