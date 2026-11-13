<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Semana Informática EXPO DEV 2026 — Carrera de Desarrollo de Sistemas de Información, Instituto Túpac Amaru, Cusco.">
    <title>@yield('title', 'EXPO DEV 2026 · Semana DSI — Túpac Amaru Cusco')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS (Animaciones) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- FontAwesome (Iconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

     <!-- ... tus scripts existentes ... -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Google Fonts: Outfit (títulos) + Inter (texto) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">



    <style>
        /* ==========================================================
           TEMA NEÓN FUTURISTA — EXPO DEV 2026
           Se mantienen los MISMOS nombres de variables para que el
           resto de vistas que use var(--color-*) se adapte sola:
           · --color-terracotta pasa a ser el morado eléctrico (acción)
           · --color-sand pasa a ser el violeta neón (enlaces/acentos)
           · --color-wine pasa a ser el morado profundo (hovers)
        ========================================================== */
        :root {
            /* Nombres originales, ahora en paleta neón */
            --color-dark: #0a0a0a;
            /* Negro profundo (fondo principal) */
            --color-purple: #6b21a8;
            /* Morado oscuro (marca / bordes) */
            --color-wine: #581c87;
            /* Morado profundo (hovers) */
            --color-terracotta: #9333ea;
            /* Morado eléctrico (color de acción) */
            --color-sand: #c084fc;
            /* Violeta neón (enlaces / acentos) */
            --color-white: #ffffff;
            /* Blanco puro */

            /* Variables adicionales del tema */
            --color-deep: #050208;
            /* Negro violáceo (degradados) */
            --color-violet: #c084fc;
            /* Alias violeta neón */
            --color-electric: #9333ea;
            /* Alias morado eléctrico */
            --color-text: #e9e4f5;
            /* Texto base sobre oscuro */
            --color-text-soft: #b8b0cf;
            /* Texto secundario */
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', 'Outfit', sans-serif;
            background-color: var(--color-dark);
            color: var(--color-text);
            overflow-x: hidden;
            animation: bodyFadeIn .5s ease both;
        }

        @keyframes bodyFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .navbar-brand {
            font-family: 'Outfit', sans-serif;
        }

        /* ---------- FONDO CÓSMICO GLOBAL: nebulosa + estrellas ---------- */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                radial-gradient(55rem 38rem at 85% -10%, rgba(147, 51, 234, .16), transparent 62%),
                radial-gradient(45rem 30rem at -12% 30%, rgba(107, 33, 168, .14), transparent 62%),
                radial-gradient(40rem 26rem at 50% 115%, rgba(192, 132, 252, .08), transparent 60%),
                linear-gradient(180deg, #050208 0%, #0a0a0a 50%, #050208 100%);
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background-image:
                radial-gradient(1px 1px at 20px 40px, rgba(255, 255, 255, .80), transparent),
                radial-gradient(1px 1px at 90px 120px, rgba(255, 255, 255, .45), transparent),
                radial-gradient(1.5px 1.5px at 160px 70px, rgba(216, 180, 254, .80), transparent),
                radial-gradient(1px 1px at 230px 180px, rgba(255, 255, 255, .40), transparent),
                radial-gradient(1px 1px at 300px 60px, rgba(255, 255, 255, .60), transparent),
                radial-gradient(1.5px 1.5px at 370px 140px, rgba(192, 132, 252, .70), transparent),
                radial-gradient(1px 1px at 40px 220px, rgba(255, 255, 255, .50), transparent),
                radial-gradient(1px 1px at 260px 260px, rgba(255, 255, 255, .35), transparent);
            background-size: 420px 300px;
            opacity: .45;
            animation: twinkle 6s ease-in-out infinite alternate;
        }

        @keyframes twinkle {
            from {
                opacity: .30;
            }

            to {
                opacity: .65;
            }
        }

        ::selection {
            background: var(--color-electric);
            color: #fff;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--color-deep);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(var(--color-electric), var(--color-purple));
            border-radius: 8px;
        }

        /* ---------- Utilidades del tema ---------- */
        .text-sand {
            color: var(--color-sand) !important;
        }

        .text-neon {
            color: var(--color-sand);
            text-shadow: 0 0 10px rgba(192, 132, 252, .65);
        }

        /* ---------- Barra de progreso de scroll ---------- */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, var(--color-purple), var(--color-electric), var(--color-sand));
            box-shadow: 0 0 10px rgba(147, 51, 234, .8);
            z-index: 2000;
            transition: width .1s linear;
        }

        /* ---------- NAVBAR de cristal neón ---------- */
        .navbar-custom {
            background-color: rgba(10, 10, 10, .55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(147, 51, 234, .25);
            transition: all .3s ease;
        }

        .navbar-custom.scrolled {
            background-color: rgba(5, 2, 8, .92);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .45), 0 4px 20px rgba(147, 51, 234, .18);
        }

        /* Ícono de marca con degradado y pulso neón */
        .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--color-electric), var(--color-purple));
            box-shadow: 0 0 16px rgba(147, 51, 234, .55);
            animation: brandPulse 3.5s ease-in-out infinite;
            transition: all .3s ease;
        }

        .navbar-brand:hover .brand-icon {
            transform: scale(1.08) rotate(-6deg);
            box-shadow: 0 0 26px rgba(192, 132, 252, .85);
        }

        @keyframes brandPulse {

            0%,
            100% {
                box-shadow: 0 0 12px rgba(147, 51, 234, .45);
            }

            50% {
                box-shadow: 0 0 24px rgba(147, 51, 234, .80);
            }
        }

        .navbar-custom .nav-link {
            color: #d9d2ea;
            font-weight: 500;
            margin: 0 10px;
            position: relative;
            transition: all .3s ease;
        }

        /* Subrayado neón animado */
        .navbar-custom .nav-link::after {
            content: "";
            position: absolute;
            left: 10px;
            right: 10px;
            bottom: 0;
            height: 2px;
            border-radius: 2px;
            background: linear-gradient(90deg, var(--color-electric), var(--color-sand));
            box-shadow: 0 0 8px rgba(147, 51, 234, .8);
            transform: scaleX(0);
            transition: transform .3s ease;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: var(--color-sand);
            text-shadow: 0 0 12px rgba(192, 132, 252, .60);
        }

        .navbar-custom .nav-link:hover::after,
        .navbar-custom .nav-link.active::after {
            transform: scaleX(1);
        }

        .navbar-toggler {
            border: 0;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Menú desplegable en móvil: panel de cristal flotante */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(10, 10, 10, .92);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
                border: 1px solid rgba(147, 51, 234, .25);
                border-radius: 16px;
                padding: 1rem 1.25rem;
                margin-top: .75rem;
                box-shadow: 0 18px 40px rgba(0, 0, 0, .5);
            }
        }

        /* ---------- BOTONES neón (mismas clases, nuevo look) ---------- */
        .btn-custom {
            --bs-btn-padding-y: .62rem;
            --bs-btn-padding-x: 1.6rem;
            padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
            background: linear-gradient(135deg, var(--color-electric), var(--color-purple));
            color: #fff;
            font-weight: 600;
            border-radius: 50px;
            border: 1px solid rgba(192, 132, 252, .40);
            box-shadow: 0 0 16px rgba(147, 51, 234, .45);
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #a855f7, #7e22ce);
            color: #fff;
            transform: translateY(-3px) scale(1.03);
            box-shadow:
                0 0 30px rgba(147, 51, 234, .70),
                0 10px 24px rgba(0, 0, 0, .40);
        }

        .btn-outline-custom {
            --bs-btn-padding-y: .58rem;
            --bs-btn-padding-x: 1.55rem;
            padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
            border: 2px solid rgba(192, 132, 252, .55);
            color: var(--color-sand);
            font-weight: 600;
            border-radius: 50px;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: rgba(147, 51, 234, .18);
            border-color: var(--color-sand);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 0 22px rgba(147, 51, 234, .45);
        }

        /* ---------- FOOTER neón ---------- */
        footer {
            position: relative;
            background: linear-gradient(180deg, #0d0616, var(--color-deep));
            color: var(--color-text-soft);
            border-top: 1px solid rgba(147, 51, 234, .30);
        }

        /* Línea neón superior con glow */
        footer::before {
            content: "";
            position: absolute;
            top: -1px;
            left: 8%;
            right: 8%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--color-electric), var(--color-sand), var(--color-electric), transparent);
            box-shadow: 0 0 18px rgba(147, 51, 234, .70);
        }

        footer h5 {
            color: #fff;
            letter-spacing: .5px;
        }

        footer a {
            color: var(--color-text-soft);
            transition: all .3s ease;
            text-decoration: none;
        }

        footer a:hover {
            color: var(--color-sand);
            text-shadow: 0 0 10px rgba(192, 132, 252, .70);
        }

        /* Enlaces rápidos con flecha animada */
        .footer-quicklinks li a {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }

        .footer-quicklinks li a::before {
            content: "\f105";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: .7rem;
            color: var(--color-electric);
            transition: transform .3s ease;
        }

        .footer-quicklinks li a:hover::before {
            transform: translateX(4px);
        }

        /* Redes sociales en burbujas de cristal */
        .footer-social a {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid rgba(192, 132, 252, .35);
            background: rgba(147, 51, 234, .08);
            color: var(--color-sand);
            transition: all .3s ease;
        }

        .footer-social a:hover {
            background: linear-gradient(135deg, var(--color-electric), var(--color-purple));
            color: #fff;
            transform: translateY(-4px) scale(1.06);
            box-shadow: 0 0 20px rgba(147, 51, 234, .65);
            text-shadow: none;
        }

        /* Divisor con degradado neón */
        .footer-hr {
            border: 0;
            height: 1px;
            opacity: 1;
            background: linear-gradient(90deg, transparent, rgba(147, 51, 234, .5), transparent);
        }

        /* ---------- Botón "volver arriba" ---------- */
        .back-to-top {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 1500;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 1px solid rgba(192, 132, 252, .50);
            background: linear-gradient(135deg, var(--color-electric), var(--color-purple));
            color: #fff;
            font-size: 1rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(14px);
            box-shadow: 0 0 18px rgba(147, 51, 234, .50);
            transition: all .3s ease;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-to-top:hover {
            transform: translateY(-4px) scale(1.06);
            box-shadow: 0 0 28px rgba(147, 51, 234, .85);
        }

        /* Accesibilidad: respetar preferencia de menos movimiento */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Barra de progreso de scroll -->
    <div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top py-3">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="{{ route('home') }}">
                <!-- Contenedor del logo. Le quitamos el borde y fondo para que se vea limpio tu PNG -->
                <span class="me-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <img src="{{ asset('images/logo_fest.png') }}" alt="Logo EXPO DEV"
                        style="width: 100%; height: 100%; object-fit: contain;">
                </span>
                <span>
                    <span class="d-block lh-1 fs-5">Expo Dev <span class="text-neon">DSI</span></span>
                    <small class="text-sand lh-1" style="font-size: 0.7rem;">Túpac Amaru</small>
                </span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Menú de navegación">
                <i class="fa-solid fa-bars text-white fs-4"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('schedule') }}">Cronograma</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Galería</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('certificates.search') }}">Certificados</a>
                    </li>
                    <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                        <a class="btn btn-custom" href="{{ route('registration.create') }}">Inscríbete</a>
                    </li>
                    <!-- Botón para abrir el modal de login -->
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal" style="color: var(--color-sand); font-weight: 600;">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Iniciar sesión
                        </a>
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
                    <h5 class="mb-3 fw-bold d-flex align-items-center">
                        <!-- Contenedor del logo en el footer, un poco más pequeño -->
                        <span class="me-2 d-inline-flex align-items-center justify-content-center"
                            style="width: 36px; height: 36px;">
                            <img src="{{ asset('images/logo_fest.png') }}" alt="Logo EXPO DEV"
                                style="width: 100%; height: 100%; object-fit: contain;">
                        </span>
                        Semana <span class="text-neon">DSI</span> 2026
                    </h5>
                    <p class="small opacity-75">Organizado por la carrera de Desarrollo de Sistemas de Información del
                        Instituto Túpac Amaru de Cusco. Un espacio para la innovación y el futuro tecnológico.</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-white mb-3 fw-bold">Enlaces</h5>
                    <ul class="list-unstyled small footer-quicklinks">
                        <li class="mb-2"><a href="{{ route('schedule') }}">Cronograma</a></li>
                        <li class="mb-2"><a href="{{ route('registration.create') }}">Inscripciones</a></li>
                        <li class="mb-2"><a href="{{ route('certificates.search') }}">Certificados</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3 fw-bold">Contacto y Redes</h5>
                    <p class="small mb-2"><i class="fa-solid fa-location-dot me-2"
                            style="color: var(--color-sand);"></i> Cusco, Perú</p>
                    <div class="d-flex gap-3 mt-3 footer-social">
                        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="footer-hr my-4">
            <div class="text-center small opacity-50">&copy; {{ date('Y') }} Carrera de Desarrollo de Sistemas de
                Información — Instituto Túpac Amaru. Todos los derechos reservados.</div>
        </div>
    </footer>

    <!-- Botón volver arriba -->
    <button id="btnBackToTop" class="back-to-top" type="button" aria-label="Volver arriba">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out-cubic',
            offset: 60
        });

        // Navbar al hacer scroll + barra de progreso + botón volver arriba
        window.addEventListener('scroll', function() {
            var navbar = document.querySelector('.navbar-custom');
            var progress = document.getElementById('scrollProgress');
            var btnTop = document.getElementById('btnBackToTop');
            var maxScroll = document.documentElement.scrollHeight - window.innerHeight;

            if (progress && maxScroll > 0) {
                progress.style.width = ((window.scrollY / maxScroll) * 100) + '%';
            }

            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            if (btnTop) {
                btnTop.classList.toggle('show', window.scrollY > 400);
            }
        });

        // Volver arriba con scroll suave
        document.getElementById('btnBackToTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
    @stack('scripts')
    <!-- INCLUIR EL MODAL AQUÍ -->
    @include('layouts.partials.login-modal')
    
</body>

</html>
