@extends('layouts.public')

@section('title', 'Semana DSI 2026 - Instituto Túpac Amaru')

@push('styles')
<style>
    /* HERO SECTION */
    .hero-section {
        background: linear-gradient(135deg, var(--color-dark) 0%, var(--color-purple) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        padding-top: 100px;
        padding-bottom: 50px;
    }

    /* Patrón de fondo (Puntos) */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 30px 30px;
        opacity: 0.5;
    }

    /* Forma flotante */
    .floating-shape {
        position: absolute;
        background: var(--color-wine);
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.5;
        z-index: 0;
    }
    .shape-1 { width: 400px; height: 400px; top: -100px; right: -50px; }
    .shape-2 { width: 300px; height: 300px; bottom: -50px; left: -50px; background: var(--color-terracotta); }

    .hero-title {
        font-size: 4.5rem;
        font-weight: 900;
        line-height: 1.1;
        color: var(--color-white);
    }
    
    .hero-title span {
        color: var(--color-terracotta);
    }

    .hero-subtitle {
        color: var(--color-sand);
        font-size: 1.2rem;
        max-width: 600px;
    }

    /* COUNTDOWN (Cuenta Regresiva) */
    .countdown-box {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 20px;
        display: inline-flex;
        gap: 20px;
        text-align: center;
    }

    .countdown-item h3 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--color-sand);
        margin-bottom: 0;
        line-height: 1;
    }

    .countdown-item p {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255,255,255,0.6);
        margin-bottom: 0;
    }

    /* TARJETAS DE CARACTERÍSTICAS */
    .feature-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.4s ease;
        overflow: hidden;
        border-bottom: 4px solid transparent;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(93, 58, 122, 0.15);
        border-bottom: 4px solid var(--color-terracotta);
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 20px;
    }

    .bg-custom-purple { background-color: rgba(93, 58, 122, 0.1); color: var(--color-purple); }
    .bg-custom-wine { background-color: rgba(112, 28, 33, 0.1); color: var(--color-wine); }
    .bg-custom-terracotta { background-color: rgba(194, 89, 74, 0.1); color: var(--color-terracotta); }

    /* SECCIÓN INSTITUTO */
    .institute-section {
        background-color: var(--color-dark);
        color: var(--color-white);
        position: relative;
        overflow: hidden;
    }
    
    .institute-img {
        border-radius: 20px;
        box-shadow: -20px 20px 0px var(--color-purple);
    }

    /* Adaptación Móvil */
    @media (max-width: 768px) {
        .hero-title { font-size: 3rem; }
        .countdown-box { padding: 15px; gap: 10px; }
        .countdown-item h3 { font-size: 1.8rem; }
        .institute-img { box-shadow: -10px 10px 0px var(--color-purple); margin-bottom: 30px;}
    }
</style>
@endpush

@section('content')

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        
        <div class="container position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-8 text-center text-lg-start" data-aos="fade-right">
                    <span class="badge mb-3 px-3 py-2 rounded-pill fw-bold" style="background-color: var(--color-sand); color: var(--color-dark);">
                        <i class="fa-solid fa-graduation-cap me-1"></i> Instituto Túpac Amaru - Cusco
                    </span>
                    <h1 class="hero-title mb-4">
                        Semana de <span>Desarrollo de Sistemas</span>
                    </h1>
                    <p class="hero-subtitle mb-5 mx-auto mx-lg-0">
                        El evento tecnológico más esperado por la carrera de DSI. Únete a las conferencias, talleres y retos de programación del <strong>30 de Noviembre al 04 de Diciembre</strong>.
                    </p>
                    
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start flex-wrap mb-5">
                        <a href="{{ route('registration.create') }}" class="btn btn-custom btn-lg">Inscríbete Ahora <i class="fa-solid fa-arrow-right ms-2"></i></a>
                        <a href="{{ route('schedule') }}" class="btn btn-outline-custom btn-lg">Ver Cronograma</a>
                    </div>

                    <!-- CUENTA REGRESIVA -->
                    <div class="countdown-box" data-aos="zoom-in" data-aos-delay="300">
                        <div class="countdown-item">
                            <h3 id="days">00</h3>
                            <p>Días</p>
                        </div>
                        <div class="countdown-item">
                            <h3 id="hours">00</h3>
                            <p>Horas</p>
                        </div>
                        <div class="countdown-item">
                            <h3 id="minutes">00</h3>
                            <p>Minutos</p>
                        </div>
                        <div class="countdown-item">
                            <h3 id="seconds">00</h3>
                            <p>Segundos</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 d-none d-lg-block text-center" data-aos="fade-left">
                    <!-- Icono flotante gigante -->
                    <div class="position-relative">
                        <i class="fa-solid fa-network-wired" style="font-size: 15rem; color: rgba(228, 184, 141, 0.1);"></i>
                        <i class="fa-solid fa-database position-absolute" style="font-size: 5rem; color: var(--color-terracotta); top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN: SOBRE EL EVENTO (Tarjetas) -->
    <section class="py-5 bg-light" style="margin-top: -60px; position: relative; z-index: 10;">
        <div class="container">
            <div class="row g-4">
                <!-- Tarjeta 1 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card p-4 h-100">
                        <div class="icon-wrapper bg-custom-purple">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <h4 class="fw-bold">Conferencias Magistrales</h4>
                        <p class="text-muted">Aprende de los mejores profesionales en desarrollo de software, redes y gestión de TI de la región.</p>
                    </div>
                </div>
                <!-- Tarjeta 2 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card p-4 h-100">
                        <div class="icon-wrapper bg-custom-wine">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <h4 class="fw-bold">Hackathon DSI</h4>
                        <p class="text-muted">Demuestra tus habilidades en nuestro concurso de programación. Premios increíbles para los mejores equipos.</p>
                    </div>
                </div>
                <!-- Tarjeta 3 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card p-4 h-100">
                        <div class="icon-wrapper bg-custom-terracotta">
                            <i class="fa-solid fa-certificate"></i>
                        </div>
                        <h4 class="fw-bold">Certificación Digital</h4>
                        <p class="text-muted">Recibe un certificado oficial avalado por el Instituto Túpac Amaru por tu participación (sujeto a pago).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN: EL INSTITUTO -->
    <section class="py-5 institute-section">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <!-- Puedes reemplazar esta imagen por una del Instituto Túpac Amaru -->
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Instituto Túpac Amaru Cusco" class="img-fluid institute-img">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <h6 class="text-uppercase fw-bold mb-2" style="color: var(--color-terracotta); letter-spacing: 2px;">Orgullo Cusqueño</h6>
                    <h2 class="fw-bold mb-4 display-5 text-white">Instituto Túpac Amaru</h2>
                    <p class="fs-5 mb-4" style="color: var(--color-sand);">
                        Ubicado en la histórica ciudad del Cusco, nuestro instituto forma a los líderes tecnológicos del mañana. 
                    </p>
                    <p class="mb-4 opacity-75">
                        La carrera de <strong>Desarrollo de Sistemas de Información (DSI)</strong> organiza este evento para los estudiantes de 2do, 4to y 6to semestre, buscando cerrar la brecha entre la academia y la industria real.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fa-solid fa-check-circle me-3 fs-5" style="color: var(--color-terracotta);"></i>
                            <span>Networking con empresas locales</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fa-solid fa-check-circle me-3 fs-5" style="color: var(--color-terracotta);"></i>
                            <span>Talleres 100% prácticos</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fa-solid fa-check-circle me-3 fs-5" style="color: var(--color-terracotta);"></i>
                            <span>Exposición de proyectos estudiantiles</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    // Lógica de la Cuenta Regresiva
    // Cambia esta fecha a la fecha exacta de inicio del evento (30 de Noviembre de 2026 a las 08:00 AM)
    const countDownDate = new Date("Nov 30, 2026 08:00:00").getTime();

    const timer = setInterval(function() {
        const now = new Date().getTime();
        const distance = countDownDate - now;

        // Cálculos de tiempo
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Mostrar en el HTML
        document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
        document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
        document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
        document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;

        // Si el evento ya pasó
        if (distance < 0) {
            clearInterval(timer);
            document.querySelector(".countdown-box").innerHTML = "<h3 class='text-white m-0'>¡El evento ha comenzado!</h3>";
        }
    }, 1000);
</script>
@endpush