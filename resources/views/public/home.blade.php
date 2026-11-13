{{-- ==========================================================================
|   SEMANA INFORMÁTICA — DEV CON 2026
|   Instituto de Educación Superior Túpac Amaru · Cusco
|   Carrera: Desarrollo de Sistemas de Información (DSI)
|   Vista:   resources/views/public/home.blade.php
|   Requiere en layouts/public.blade.php:
|   @stack('styles') en <head> y @yield('content') en <body>
========================================================================== --}}

@extends('layouts.public')

@section('content')

    <!-- ======================================================================
         HERO SECTION — Primera pantalla
    ====================================================================== -->
    <header id="inicio" class="hero-section d-flex align-items-center position-relative overflow-hidden">
        <!-- Orbes decorativos de nebulosa -->
        <div class="orb orb-1" aria-hidden="true"></div>
        <div class="orb orb-2" aria-hidden="true"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center gy-5">

                <!-- Columna izquierda: logo, título, countdown y CTAs -->
                <div class="col-lg-7 text-center">

                    <!-- LOGO flotante -->
                    <div class="logo-wrap mx-auto d-inline-block" data-aos="zoom-in" data-aos-duration="900">
                        <img src="{{ asset('images/logo_fest.png') }}" alt="Logo DEV CON 2026 - Semana Informática DSI" class="hero-logo img-fluid">
                    </div>

                    <div data-aos="fade-down" data-aos-delay="100">
                        <span class="hero-badge">
                            <i class="fa-solid fa-bolt me-2"></i>
                            Semana Informática · Instituto Túpac Amaru — Cusco
                        </span>
                    </div>

                    <!-- Título gigante -->
                    <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
                        <span class="title-line">DEV CON</span>
                        <span class="text-neon">2026</span>
                    </h1>

                    <!-- Subtítulo -->
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="300">
                        El futuro de la tecnología se construye hoy. Acompaña a la carrera de
                        <span class="text-highlight">Desarrollo de Sistemas de Información</span> en la semana
                        más épica del Instituto Túpac Amaru: cinco días de código, inteligencia artificial
                        e innovación desde el corazón de <span class="text-highlight">Cusco</span>.
                    </p>

                    <!-- CUENTA REGRESIVA (glassmorphism) -->
                    <div class="countdown-wrap" data-aos="fade-up" data-aos-delay="400" aria-label="Cuenta regresiva al DEV CON 2026">
                        <div class="countdown-box">
                            <span id="cd-days" class="countdown-value">00</span>
                            <span class="countdown-label">Días</span>
                        </div>
                        <span class="countdown-sep" aria-hidden="true">:</span>
                        <div class="countdown-box">
                            <span id="cd-hours" class="countdown-value">00</span>
                            <span class="countdown-label">Horas</span>
                        </div>
                        <span class="countdown-sep" aria-hidden="true">:</span>
                        <div class="countdown-box">
                            <span id="cd-minutes" class="countdown-value">00</span>
                            <span class="countdown-label">Minutos</span>
                        </div>
                        <span class="countdown-sep" aria-hidden="true">:</span>
                        <div class="countdown-box">
                            <span id="cd-seconds" class="countdown-value">00</span>
                            <span class="countdown-label">Segundos</span>
                        </div>
                    </div>
                    <p class="countdown-caption" id="cd-caption" data-aos="fade-up" data-aos-delay="450">
                        para el gran inicio · lunes 30 de noviembre, 8:00 a. m.
                    </p>

                    <!-- Botones -->
                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4" data-aos="fade-up" data-aos-delay="550">
                        <a href="#inscripcion" class="btn btn-neon btn-lg">
                            <i class="fa-solid fa-rocket me-2"></i> Inscríbete Ahora
                        </a>
                        <a href="#tarjetas" class="btn btn-ghost btn-lg">
                            <i class="fa-solid fa-circle-info me-2"></i> Explorar Evento
                        </a>
                    </div>
                </div>

                <!-- Columna derecha: IMAGEN 3D del androide con flotación -->
                <div class="col-lg-5" data-aos="fade-left" data-aos-delay="300">
                    <div class="hero-robot mx-auto">
                        <div class="robot-glow" aria-hidden="true"></div>
                        <div class="robot-ring" aria-hidden="true"></div>
                        <img src="{{ asset('images/inicio_lado.jpg') }}"
                             alt="Androide de inteligencia artificial con ojos brillantes"
                             class="robot-img" loading="eager">
                        <span class="robot-chip robot-chip-main">
                            <i class="fa-solid fa-microchip me-2"></i> IA · Robótica
                        </span>
                        <span class="robot-chip robot-chip-sub">
                            <i class="fa-solid fa-code me-2"></i> DSI · Cusco
                        </span>
                    </div>
                </div>
            </div>

            <!-- Indicador de scroll -->
            <div class="scroll-hint" aria-hidden="true">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
    </header>

    <!-- ======================================================================
         SECCIÓN TARJETAS — Ejes temáticos con efecto 3D (Vanilla Tilt)
    ====================================================================== -->
    <section id="tarjetas" class="section-cards py-5">
        <div class="container py-lg-4">

            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-kicker">Explora el evento</span>
                <h2 class="section-title">
                    Ejes que marcarán <span class="text-neon">el futuro</span>
                </h2>
                <p class="section-desc mx-auto">
                    Cada jornada del DEV CON está dedicada a una de las áreas que están
                    redefiniendo el mundo digital. Elige tu camino… o domínalos todos.
                </p>
            </div>

            <div class="row g-4">

                <!-- Tarjeta 1: Inteligencia Artificial y Robótica -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <article class="tilt-card h-100">
                        <div class="card-top-accent" aria-hidden="true"></div>
                        <div class="card-icon"><i class="fa-solid fa-robot"></i></div>
                        <h3>Inteligencia Artificial y Robótica</h3>
                        <p>
                            Sumérgete en el mundo de los modelos generativos, el machine learning y la
                            automatización. Descubre cómo los agentes inteligentes y los androides
                            están transformando todas las industrias del planeta.
                        </p>
                        <div class="chip-group">
                            <span>Machine Learning</span>
                            <span>Chatbots</span>
                            <span>Robótica</span>
                        </div>
                    </article>
                </div>

                <!-- Tarjeta 2: Desarrollo Web Full-Stack -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <article class="tilt-card h-100">
                        <div class="card-top-accent" aria-hidden="true"></div>
                        <div class="card-icon"><i class="fa-solid fa-laptop-code"></i></div>
                        <h3>Desarrollo Web Full-Stack</h3>
                        <p>
                            Domina el ciclo completo de construcción de aplicaciones modernas:
                            desde backends con APIs robustas hasta interfaces reactivas que
                            enamoran a los usuarios. Del código al despliegue.
                        </p>
                        <div class="chip-group">
                            <span>Laravel</span>
                            <span>React</span>
                            <span>APIs REST</span>
                        </div>
                    </article>
                </div>

                <!-- Tarjeta 3: Ciberseguridad -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <article class="tilt-card h-100">
                        <div class="card-top-accent" aria-hidden="true"></div>
                        <div class="card-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <h3>Ciberseguridad</h3>
                        <p>
                            Aprende a pensar como un hacker ético: explora técnicas de pentesting,
                            protección de redes y análisis forense para convertirte en el guardián
                            que defiende el mundo digital de las amenazas.
                        </p>
                        <div class="chip-group">
                            <span>Hacking Ético</span>
                            <span>Pentesting</span>
                            <span>Forense</span>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         SECCIÓN SOBRE EL EVENTO / DSI — Dos columnas
    ====================================================================== -->
    <section id="dsi" class="section-about py-5">
        <div class="container py-lg-4">
            <div class="row align-items-center gy-5">

                <!-- Columna izquierda: imagen de Cusco -->
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-img-frame position-relative">
                        <img src="https://images.unsplash.com/photo-1526392060635-9d6019884377?q=80&w=1000&auto=format&fit=crop"
                             alt="Cusco, Perú - ciudad imperial, sede del DEV CON 2026"
                             class="about-img" loading="lazy">
                        <span class="about-img-chip">
                            <i class="fa-solid fa-location-dot me-2"></i> Cusco · Perú
                        </span>
                    </div>
                </div>

                <!-- Columna derecha: texto sobre el Instituto y la carrera DSI -->
                <div class="col-lg-6 about-text" data-aos="fade-left" data-aos-delay="150">
                    <span class="section-kicker">Sobre el evento</span>
                    <h2 class="section-title">
                        Hecho en Cusco por la <span class="text-neon">carrera DSI</span>
                    </h2>
                    <p>
                        La <strong>Semana Informática — DEV CON 2026</strong> es el evento tecnológico
                        más importante del año, organizado por la carrera de
                        <strong>Desarrollo de Sistemas de Información (DSI)</strong> del Instituto de
                        Educación Superior <strong>Túpac Amaru</strong> de Cusco. Durante una semana,
                        el instituto se transforma en el punto de encuentro entre estudiantes, docentes
                        y la industria tecnológica.
                    </p>
                    <p>
                        Del <strong>lunes 30 de noviembre al viernes 04 de diciembre de 2026</strong>,
                        vive ponencias magistrales, talleres prácticos, competencias de programación y
                        una hackatón épica, todo en un ambiente 100% futurista pensado para inspirar a
                        la próxima generación de desarrolladores cusqueños.
                    </p>

                    <!-- Características con iconos FontAwesome -->
                    <div class="row g-3 mt-2">
                        <div class="col-sm-6">
                            <div class="feature-item h-100">
                                <i class="fa-solid fa-calendar-days"></i>
                                <div>
                                    <h4>5 días de tecnología</h4>
                                    <p>Una semana completa dedicada a la innovación y al conocimiento digital.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-item h-100">
                                <i class="fa-solid fa-microphone"></i>
                                <div>
                                    <h4>Ponencias y talleres</h4>
                                    <p>Invitados de la industria y sesiones 100% prácticas en laboratorio.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-item h-100">
                                <i class="fa-solid fa-trophy"></i>
                                <div>
                                    <h4>Hackatón y premios</h4>
                                    <p>Compite en equipos, resuelve retos reales y gana premios increíbles.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-item h-100">
                                <i class="fa-solid fa-user-group"></i>
                                <div>
                                    <h4>Networking DSI</h4>
                                    <p>Conecta con estudiantes, egresados y empresas aliadas del sector.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nota dirigida a los semestres -->
                    <div class="semester-note">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>
                            Dirigido a los semestres <strong>2.º, 4.º y 6.º</strong> de la carrera de
                            Desarrollo de Sistemas de Información. ¡Tu inscripción incluye acceso a
                            todas las actividades!
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         CTA FINAL — Llamado a la inscripción
    ====================================================================== -->
    <section id="inscripcion" class="section-cta py-5">
        <div class="container py-lg-3">
            <div class="cta-panel text-center" data-aos="zoom-in">
                <span class="section-kicker">Inscripciones abiertas</span>
                <h2 class="section-title">
                    ¿Listo para <span class="text-neon">construir el futuro</span>?
                </h2>
                <p class="section-desc mx-auto mb-4">
                    Asegura tu lugar en la Semana Informática más grande del Instituto Túpac Amaru.
                    El próximo gran proyecto tecnológico podría empezar aquí.
                </p>
                <div class="cta-meta">
                    <span><i class="fa-solid fa-calendar-days"></i> 30 Nov — 04 Dic 2026</span>
                    <span><i class="fa-solid fa-location-dot"></i> Instituto Túpac Amaru · Cusco</span>
                    <span><i class="fa-solid fa-clock"></i> Inicio: 8:00 a. m.</span>
                </div>
                <a href="#" class="btn btn-neon btn-lg">
                    <i class="fa-solid fa-rocket me-2"></i> Inscríbete Ahora
                </a>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         LIBRERÍAS CDN (Bootstrap 5, AOS, Vanilla Tilt) + JavaScript propio
    ====================================================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.8.1/dist/vanilla-tilt.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* ==========================================================
               1. CUENTA REGRESIVA en tiempo real
               Fecha objetivo: 30 de noviembre de 2026, 08:00:00
            ========================================================== */
            var targetDate = new Date('2026-11-30T08:00:00').getTime();

            var cdDays    = document.getElementById('cd-days');
            var cdHours   = document.getElementById('cd-hours');
            var cdMinutes = document.getElementById('cd-minutes');
            var cdSeconds = document.getElementById('cd-seconds');
            var cdCaption = document.getElementById('cd-caption');
            var countdownTimer = null;

            function pad(value) {
                return String(value).padStart(2, '0');
            }

            function updateCountdown() {
                var now  = new Date().getTime();
                var diff = targetDate - now;

                // Si el evento ya comenzó, se muestran ceros y un mensaje especial
                if (diff <= 0) {
                    cdDays.textContent    = '00';
                    cdHours.textContent   = '00';
                    cdMinutes.textContent = '00';
                    cdSeconds.textContent = '00';
                    if (cdCaption) {
                        cdCaption.textContent = '¡El DEV CON 2026 ya comenzó! Nos vemos en el Instituto Túpac Amaru.';
                    }
                    if (countdownTimer) { clearInterval(countdownTimer); }
                    return;
                }

                var days    = Math.floor(diff / (1000 * 60 * 60 * 24));
                var hours   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((diff % (1000 * 60)) / 1000);

                cdDays.textContent    = pad(days);
                cdHours.textContent   = pad(hours);
                cdMinutes.textContent = pad(minutes);
                cdSeconds.textContent = pad(seconds);
            }

            updateCountdown();
            countdownTimer = setInterval(updateCountdown, 1000);

            /* ==========================================================
               2. AOS — Animaciones al hacer scroll
            ========================================================== */
            if (window.AOS) {
                AOS.init({
                    duration: 900,
                    easing: 'ease-out-cubic',
                    once: true,
                    offset: 60
                });
            }

            /* ==========================================================
               3. VANILLA TILT — Efecto 3D en las tarjetas
            ========================================================== */
            if (window.VanillaTilt) {
                VanillaTilt.init(document.querySelectorAll('.tilt-card'), {
                    max: 12,
                    speed: 500,
                    glare: true,
                    'max-glare': 0.25,
                    scale: 1.03,
                    gyroscope: true
                });
            }
        });
    </script>

@endsection

@push('styles')

    <!-- Bootstrap 5 (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- FontAwesome 6 (CDN) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts: Outfit (títulos) + Inter (texto) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS - Animate On Scroll (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        /* ==========================================================
           VARIABLES Y BASE
        ========================================================== */
        :root {
            --df-black:      #050208;
            --df-black-soft: #0a0a0a;
            --df-purple-900: #6b21a8;
            --df-purple-600: #9333ea;
            --df-violet:     #c084fc;
            --df-white:      #ffffff;
        }

        html { scroll-behavior: smooth; }

        body {
            background-color: var(--df-black) !important;
            color: #e9e4f5;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .hero-title, .section-title {
            font-family: 'Outfit', sans-serif;
        }

        section[id], header[id] { scroll-margin-top: 90px; }

        ::selection { background: var(--df-purple-600); color: var(--df-white); }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: var(--df-black); }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(var(--df-purple-600), var(--df-purple-900));
            border-radius: 8px;
        }

        /* ==========================================================
         * FONDO CÓSMICO: nebulosa morada + estrellas parpadeantes
        ========================================================== */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                radial-gradient(60rem 40rem at 85% -10%, rgba(147, 51, 234, .22), transparent 60%),
                radial-gradient(50rem 35rem at -15% 25%, rgba(107, 33, 168, .20), transparent 60%),
                radial-gradient(45rem 30rem at 50% 110%, rgba(192, 132, 252, .12), transparent 60%),
                linear-gradient(180deg, #050208 0%, #0a0512 50%, #050208 100%);
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background-image:
                radial-gradient(1px 1px at 20px 40px,   rgba(255, 255, 255, .90), transparent),
                radial-gradient(1px 1px at 90px 120px,  rgba(255, 255, 255, .55), transparent),
                radial-gradient(1.5px 1.5px at 160px 70px, rgba(216, 180, 254, .90), transparent),
                radial-gradient(1px 1px at 230px 180px, rgba(255, 255, 255, .50), transparent),
                radial-gradient(1px 1px at 300px 60px,  rgba(255, 255, 255, .75), transparent),
                radial-gradient(1.5px 1.5px at 370px 140px, rgba(192, 132, 252, .80), transparent),
                radial-gradient(1px 1px at 40px 220px,  rgba(255, 255, 255, .60), transparent),
                radial-gradient(1px 1px at 260px 260px, rgba(255, 255, 255, .45), transparent);
            background-size: 420px 300px;
            opacity: .55;
            animation: twinkle 6s ease-in-out infinite alternate;
        }

        @keyframes twinkle {
            from { opacity: .35; }
            to   { opacity: .75; }
        }

        /* Navbar con efecto cristal */
        .navbar {
            background: rgba(5, 2, 8, .60) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(147, 51, 234, .25);
            box-shadow: 0 8px 30px rgba(0, 0, 0, .35);
        }
        .navbar .nav-link {
            color: #d9d2ea !important;
            font-weight: 500;
            transition: all .3s ease;
        }
        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--df-violet) !important;
            text-shadow: 0 0 12px rgba(192, 132, 252, .80);
        }

        /* ==========================================================
           HERO SECTION
        ========================================================== */
        .hero-section {
            min-height: 100vh;
            padding: 5.5rem 0 3.5rem;
        }

        /* Orbes de nebulosa flotando detrás del contenido */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            z-index: 1;
            pointer-events: none;
        }
        .orb-1 {
            width: 480px; height: 480px;
            top: -120px; right: -80px;
            background: radial-gradient(circle, rgba(147, 51, 234, .35), transparent 70%);
            animation: orbDrift 12s ease-in-out infinite alternate;
        }
        .orb-2 {
            width: 380px; height: 380px;
            bottom: -80px; left: -120px;
            background: radial-gradient(circle, rgba(107, 33, 168, .30), transparent 70%);
            animation: orbDrift 14s ease-in-out infinite alternate-reverse;
        }
        @keyframes orbDrift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(40px, -30px) scale(1.12); }
        }

        /* LOGO flotante con hover */
        .logo-wrap {
            animation: floatY 5s ease-in-out infinite;
        }
        .hero-logo {
            width: 98px;
            filter: drop-shadow(0 0 18px rgba(147, 51, 234, .55));
            transition: all .3s ease;
        }
        .logo-wrap:hover .hero-logo {
            transform: scale(1.08) rotate(3deg);
            filter: drop-shadow(0 0 32px rgba(192, 132, 252, .95));
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            padding: .5rem 1.2rem;
            border-radius: 50rem;
            border: 1px solid rgba(192, 132, 252, .40);
            background: rgba(147, 51, 234, .12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: var(--df-violet);
            font-size: .85rem;
            font-weight: 500;
            letter-spacing: .5px;
            box-shadow: 0 0 18px rgba(147, 51, 234, .25);
            margin-bottom: 1.2rem;
        }

        /* Título gigante neón */
        .hero-title {
            font-size: clamp(3rem, 8vw, 6.2rem);
            font-weight: 800;
            line-height: .95;
            letter-spacing: -2px;
            margin-bottom: 1.1rem;
        }
        .hero-title .title-line {
            display: block;
            background: linear-gradient(180deg, #ffffff 30%, #d8b4fe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 25px rgba(147, 51, 234, .35));
        }
        .hero-title .text-neon { display: block; }

        /* Texto morado neón reutilizable */
        .text-neon {
            color: var(--df-violet);
            text-shadow:
                0 0 8px rgba(192, 132, 252, .90),
                0 0 24px rgba(147, 51, 234, .80),
                0 0 56px rgba(147, 51, 234, .55);
            animation: neonPulse 2.6s ease-in-out infinite;
        }
        @keyframes neonPulse {
            0%, 100% {
                text-shadow:
                    0 0 8px rgba(192, 132, 252, .90),
                    0 0 24px rgba(147, 51, 234, .80),
                    0 0 56px rgba(147, 51, 234, .55);
            }
            50% {
                text-shadow:
                    0 0 12px rgba(192, 132, 252, 1),
                    0 0 38px rgba(147, 51, 234, 1),
                    0 0 80px rgba(147, 51, 234, .75);
            }
        }

        .hero-subtitle {
            max-width: 640px;
            margin: 0 auto 1.9rem;
            color: #b8b0cf;
            font-size: clamp(1rem, 2vw, 1.18rem);
            line-height: 1.75;
        }
        .text-highlight {
            color: var(--df-violet);
            font-weight: 600;
        }

        /* CUENTA REGRESIVA — cajas de cristal (glassmorphism) */
        .countdown-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
            margin-bottom: .9rem;
        }
        .countdown-box {
            min-width: 92px;
            padding: .95rem .9rem .8rem;
            border-radius: 18px;
            text-align: center;
            background: linear-gradient(160deg, rgba(147, 51, 234, .16), rgba(10, 10, 10, .50));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(192, 132, 252, .45);
            box-shadow:
                0 0 22px rgba(147, 51, 234, .28),
                inset 0 0 18px rgba(147, 51, 234, .10);
            transition: all .3s ease;
        }
        .countdown-box:hover {
            transform: translateY(-4px);
            box-shadow:
                0 0 34px rgba(147, 51, 234, .50),
                inset 0 0 22px rgba(147, 51, 234, .15);
        }
        .countdown-value {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2.1rem, 5vw, 3rem);
            font-weight: 700;
            color: var(--df-white);
            line-height: 1;
            text-shadow: 0 0 14px rgba(192, 132, 252, .80);
            font-variant-numeric: tabular-nums;
        }
        .countdown-label {
            display: block;
            margin-top: .5rem;
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--df-violet);
        }
        .countdown-sep {
            font-size: 2rem;
            font-family: 'Outfit', sans-serif;
            color: rgba(192, 132, 252, .70);
            text-shadow: 0 0 12px rgba(147, 51, 234, .80);
            animation: blinkSep 1s steps(1) infinite;
            transform: translateY(-.6rem);
        }
        @keyframes blinkSep { 50% { opacity: .15; } }

        .countdown-caption {
            color: #8f86a8;
            font-size: .85rem;
            letter-spacing: 1px;
        }

        /* Botones neón */
        .btn-neon {
            background: linear-gradient(135deg, var(--df-purple-600), var(--df-purple-900));
            color: var(--df-white);
            border: 1px solid rgba(192, 132, 252, .50);
            border-radius: 50rem;
            padding: .9rem 2.2rem;
            font-weight: 600;
            letter-spacing: .5px;
            box-shadow: 0 0 18px rgba(147, 51, 234, .45);
            transition: all 0.3s ease;
        }
        .btn-neon:hover {
            color: var(--df-white);
            transform: translateY(-3px) scale(1.04);
            background: linear-gradient(135deg, #a855f7, #7e22ce);
            box-shadow:
                0 0 32px rgba(147, 51, 234, .75),
                0 0 60px rgba(147, 51, 234, .35);
        }
        .btn-ghost {
            background: transparent;
            color: #e9d5ff;
            border: 1px solid rgba(192, 132, 252, .50);
            border-radius: 50rem;
            padding: .9rem 2.2rem;
            font-weight: 600;
            letter-spacing: .5px;
            transition: all 0.3s ease;
        }
        .btn-ghost:hover {
            color: var(--df-white);
            background: rgba(147, 51, 234, .18);
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 0 26px rgba(147, 51, 234, .40);
        }

        /* IMAGEN 3D del androide — flotación con @keyframes */
        .hero-robot {
            position: relative;
            max-width: 430px;
        }
        .robot-glow {
            position: absolute;
            inset: 8%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(147, 51, 234, .45), rgba(107, 33, 168, .15) 55%, transparent 72%);
            filter: blur(45px);
            animation: glowPulse 4.5s ease-in-out infinite;
        }
        .robot-img {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 32px;
            border: 1px solid rgba(192, 132, 252, .45);
            box-shadow:
                0 0 45px rgba(147, 51, 234, .40),
                inset 0 0 30px rgba(0, 0, 0, .40);
            animation: floatRobot 6s ease-in-out infinite;
            transition: all .3s ease;
        }
        .hero-robot:hover .robot-img {
            border-color: rgba(192, 132, 252, .85);
            box-shadow:
                0 0 70px rgba(147, 51, 234, .65),
                inset 0 0 30px rgba(0, 0, 0, .40);
        }
        .robot-ring {
            position: absolute;
            inset: -14px;
            border: 1px dashed rgba(192, 132, 252, .35);
            border-radius: 40px;
            animation: spinSlow 26s linear infinite;
            pointer-events: none;
        }
        @keyframes floatRobot {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-18px); }
        }
        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-12px); }
        }
        @keyframes glowPulse {
            0%, 100% { opacity: .70; transform: scale(1); }
            50%      { opacity: 1;   transform: scale(1.08); }
        }
        @keyframes spinSlow {
            to { transform: rotate(360deg); }
        }

        /* Chips flotantes sobre la imagen 3D */
        .robot-chip {
            position: absolute;
            display: inline-flex;
            align-items: center;
            padding: .5rem 1rem;
            border-radius: 50rem;
            background: rgba(10, 10, 10, .65);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(192, 132, 252, .50);
            color: var(--df-violet);
            font-size: .8rem;
            font-weight: 600;
            box-shadow: 0 0 16px rgba(147, 51, 234, .35);
            animation: floatY 5s ease-in-out infinite;
            z-index: 3;
        }
        .robot-chip-main { top: 22px; left: -14px; }
        .robot-chip-sub  { bottom: 26px; right: -12px; animation-delay: 1.2s; }

        .scroll-hint {
            text-align: center;
            margin-top: 2.2rem;
            color: rgba(192, 132, 252, .70);
            font-size: 1.15rem;
            animation: bounceDown 2s infinite;
        }
        @keyframes bounceDown {
            0%, 100% { transform: translateY(0); opacity: .7; }
            50%      { transform: translateY(8px); opacity: 1; }
        }

        /* ==========================================================
           SECCIONES GENERALES
        ========================================================== */
        .section-cards, .section-about, .section-cta {
            border-top: 1px solid rgba(147, 51, 234, .14);
        }

        .section-kicker {
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 4px;
            font-size: .75rem;
            font-weight: 600;
            color: var(--df-violet);
            padding: .4rem 1.1rem;
            border: 1px solid rgba(192, 132, 252, .30);
            border-radius: 50rem;
            background: rgba(147, 51, 234, .08);
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: clamp(2rem, 4.5vw, 3.1rem);
            font-weight: 700;
            color: var(--df-white);
            margin-bottom: 1rem;
        }

        .section-desc {
            color: #a99fc4;
            max-width: 620px;
            margin-bottom: 0;
        }

        /* ==========================================================
           TARJETAS 3D (Vanilla Tilt)
        ========================================================== */
        .tilt-card {
            position: relative;
            overflow: hidden;
            padding: 2.4rem 1.8rem 2.1rem;
            border-radius: 22px;
            background: linear-gradient(165deg, rgba(147, 51, 234, .10), rgba(10, 10, 10, .72) 45%);
            border: 1px solid rgba(147, 51, 234, .28);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transform-style: preserve-3d;
            transition: border-color .3s ease, box-shadow .3s ease;
        }
        .tilt-card:hover {
            border-color: rgba(192, 132, 252, .70);
            box-shadow:
                0 18px 45px rgba(0, 0, 0, .55),
                0 0 35px rgba(147, 51, 234, .35);
        }
        /* El brillo generado por Vanilla Tilt respeta las esquinas redondeadas */
        .js-tilt-glare { border-radius: 22px; }
        .js-tilt-glare-inner { border-radius: 22px; }

        .card-top-accent {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--df-violet), var(--df-purple-900), transparent);
            opacity: .85;
        }

        .card-icon {
            width: 72px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--df-white);
            border-radius: 20px;
            margin-bottom: 1.4rem;
            background: linear-gradient(135deg, var(--df-purple-600), var(--df-purple-900));
            box-shadow: 0 0 24px rgba(147, 51, 234, .55);
            transform: translateZ(30px);
            transition: all .3s ease;
        }
        .tilt-card:hover .card-icon {
            transform: translateZ(42px) scale(1.08) rotate(-6deg);
            box-shadow: 0 0 38px rgba(192, 132, 252, .70);
        }

        .tilt-card h3 {
            font-size: 1.32rem;
            font-weight: 700;
            color: var(--df-white);
            margin-bottom: .8rem;
            transform: translateZ(24px);
        }
        .tilt-card p {
            color: #b3a9cc;
            font-size: .95rem;
            line-height: 1.75;
            margin-bottom: 0;
            transform: translateZ(18px);
        }

        .chip-group {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-top: 1.3rem;
            transform: translateZ(20px);
        }
        .chip-group span {
            font-size: .72rem;
            font-weight: 500;
            color: var(--df-violet);
            padding: .32rem .78rem;
            border-radius: 50rem;
            border: 1px solid rgba(192, 132, 252, .35);
            background: rgba(147, 51, 234, .12);
            letter-spacing: .4px;
        }

        /* ==========================================================
           SOBRE EL EVENTO / DSI
        ========================================================== */
        .about-img-frame {
            border-radius: 26px;
            padding: 10px;
            background: linear-gradient(135deg, rgba(147, 51, 234, .65), rgba(192, 132, 252, .15) 40%, rgba(107, 33, 168, .55));
            box-shadow: 0 0 40px rgba(147, 51, 234, .30);
        }
        .about-img {
            width: 100%;
            aspect-ratio: 4 / 3.2;
            object-fit: cover;
            border-radius: 18px;
            display: block;
        }
        .about-img-chip {
            position: absolute;
            bottom: 26px;
            left: 26px;
            padding: .55rem 1.1rem;
            border-radius: 50rem;
            background: rgba(5, 2, 8, .72);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(192, 132, 252, .45);
            color: var(--df-white);
            font-size: .85rem;
            font-weight: 600;
        }

        .about-text p { color: #b3a9cc; line-height: 1.8; }
        .about-text strong { color: var(--df-white); }

        .feature-item {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            padding: 1.1rem;
            border-radius: 16px;
            background: rgba(147, 51, 234, .07);
            border: 1px solid rgba(147, 51, 234, .22);
            transition: all .3s ease;
        }
        .feature-item:hover {
            transform: translateY(-4px);
            border-color: rgba(192, 132, 252, .55);
            background: rgba(147, 51, 234, .13);
            box-shadow: 0 0 22px rgba(147, 51, 234, .25);
        }
        .feature-item > i {
            color: var(--df-violet);
            font-size: 1.3rem;
            margin-top: .2rem;
            filter: drop-shadow(0 0 8px rgba(147, 51, 234, .80));
        }
        .feature-item h4 {
            font-size: 1rem;
            color: var(--df-white);
            font-weight: 600;
            margin-bottom: .3rem;
        }
        .feature-item p {
            font-size: .85rem;
            color: #a99fc4;
            margin: 0;
            line-height: 1.6;
        }

        .semester-note {
            margin-top: 1.6rem;
            padding: 1.1rem 1.4rem;
            border-radius: 16px;
            display: flex;
            gap: .9rem;
            align-items: center;
            border: 1px solid rgba(192, 132, 252, .40);
            background: linear-gradient(135deg, rgba(147, 51, 234, .16), rgba(5, 2, 8, .60));
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: #e9e4f5;
            font-size: .95rem;
            line-height: 1.6;
            box-shadow: 0 0 20px rgba(147, 51, 234, .20);
        }
        .semester-note i {
            font-size: 1.5rem;
            color: var(--df-violet);
            flex-shrink: 0;
            filter: drop-shadow(0 0 10px rgba(147, 51, 234, .90));
        }

        /* ==========================================================
           CTA FINAL
        ========================================================== */
        .cta-panel {
            position: relative;
            padding: clamp(2.5rem, 6vw, 4.5rem);
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(192, 132, 252, .35);
            background: linear-gradient(160deg, rgba(107, 33, 168, .35), rgba(5, 2, 8, .85) 55%);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow:
                0 0 60px rgba(147, 51, 234, .25),
                inset 0 0 40px rgba(147, 51, 234, .08);
        }
        .cta-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(30rem 18rem at 50% -20%, rgba(192, 132, 252, .18), transparent 60%);
            pointer-events: none;
        }
        .cta-meta {
            position: relative;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin: 1.6rem 0 2rem;
        }
        .cta-meta span {
            padding: .55rem 1.25rem;
            border-radius: 50rem;
            border: 1px solid rgba(192, 132, 252, .40);
            background: rgba(10, 10, 10, .55);
            color: #e9e4f5;
            font-size: .9rem;
        }
        .cta-meta i { color: var(--df-violet); margin-right: .5rem; }

        /* ==========================================================
           RESPONSIVE
        ========================================================== */
        @media (max-width: 767.98px) {
            .hero-section { padding-top: 5.5rem; }
            .hero-title { letter-spacing: -1px; }
            .hero-robot { max-width: 320px; margin-top: 1rem; }
            .robot-chip { font-size: .7rem; padding: .4rem .8rem; }
            .robot-chip-main { left: 6px; }
            .robot-chip-sub  { right: 6px; }
            .robot-ring { inset: -10px; }
        }

        @media (max-width: 575.98px) {
            .countdown-sep { display: none; }
            .countdown-box { min-width: 74px; padding: .8rem .6rem .7rem; }
            .hero-logo { width: 96px; }
        }

        /* Accesibilidad: respetar preferencia de menos movimiento */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>

@endpush