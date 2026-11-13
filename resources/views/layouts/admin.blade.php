<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- =============================================== --}}
    {{--  EXPO DEV 2026 · Layout de Administración (CMS) --}}
    {{--  Semana Informática DSI · Túpac Amaru · Cusco   --}}
    {{-- =============================================== --}}

    <title>@yield('title', 'Panel de Control') · EXPO DEV 2026 Admin</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- FontAwesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    {{-- Google Fonts: Outfit (títulos) + Inter (texto) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ==================================================
           EXPO DEV 2026 Admin · Sistema de estilos base
           ================================================== */
        :root{
            --adm-black: #050208;
            --adm-purple-900: #6b21a8;
            --adm-purple-600: #9333ea;
            --adm-violet: #c084fc;
            --adm-text: #e9e2f5;
            --adm-muted: #9b8fb3;
            --adm-border: rgba(147, 51, 234, .22);
            --adm-glow: 0 0 22px rgba(147, 51, 234, .35);
            --sidebar-w: 240px;
            --topbar-h: 70px;
        }

        *{ box-sizing: border-box; }

        html{ scroll-behavior: smooth; }

        body{
            font-family: 'Inter', sans-serif;
            color: var(--adm-text);
            background-color: var(--adm-black);
            /* Gradiente radial morado sutil en las esquinas */
            background-image:
                radial-gradient(ellipse 60% 50% at 0% 0%,      rgba(147, 51, 234, .15), transparent 65%),
                radial-gradient(ellipse 60% 50% at 100% 100%,  rgba(107, 33, 168, .14), transparent 65%),
                radial-gradient(ellipse 45% 40% at 100% 0%,    rgba(192, 132, 252, .07), transparent 60%);
            background-attachment: fixed;
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6{ font-family: 'Outfit', sans-serif; }

        a{ text-decoration: none; }

        ::selection{ background: rgba(147, 51, 234, .5); color: #fff; }

        ::-webkit-scrollbar{ width: 9px; height: 9px; }
        ::-webkit-scrollbar-track{ background: #0a0613; }
        ::-webkit-scrollbar-thumb{
            background: linear-gradient(180deg, var(--adm-purple-600), var(--adm-purple-900));
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover{ background: var(--adm-purple-600); }

        /* ---------- Orbes decorativos de fondo ---------- */
        .bg-orb{
            position: fixed; border-radius: 50%;
            filter: blur(90px); pointer-events: none; z-index: 0;
        }
        .orb-1{
            width: 420px; height: 420px; top: -150px; left: 32%;
            background: rgba(147, 51, 234, .15);
            animation: orbDrift 16s ease-in-out infinite;
        }
        .orb-2{
            width: 360px; height: 360px; bottom: -130px; right: -90px;
            background: rgba(107, 33, 168, .18);
            animation: orbDrift 20s ease-in-out infinite reverse;
        }
        @keyframes orbDrift{
            0%, 100%{ transform: translate(0, 0) scale(1); }
            50%     { transform: translate(-40px, 30px) scale(1.08); }
        }

        /* ---------- Panel de cristal (glassmorphism) ---------- */
        .glass-panel{
            background: rgba(18, 11, 30, .55);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--adm-border);
            border-radius: 18px;
        }

        /* ==================================================
           SIDEBAR (240px fijo a la izquierda)
           ================================================== */
        .admin-sidebar{
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #0b0614 0%, var(--adm-black) 55%);
            border-right: 1px solid var(--adm-border);
            display: flex; flex-direction: column;
            z-index: 1040;
            transition: transform .35s ease;
        }
        /* Línea de neón en el borde derecho */
        .admin-sidebar::after{
            content: ''; position: absolute; right: -1px; top: 12%; bottom: 12%;
            width: 1px; pointer-events: none;
            background: linear-gradient(180deg, transparent, rgba(147, 51, 234, .75), transparent);
        }

        /* Marca / logo */
        .sidebar-brand{
            display: flex; align-items: center; gap: .75rem;
            padding: 1.05rem 1rem;
            border-bottom: 1px solid rgba(147, 51, 234, .16);
        }
        .brand-logo{
            width: 44px; height: 44px; object-fit: contain; flex: 0 0 44px;
            filter: drop-shadow(0 0 10px rgba(147, 51, 234, .65));
            animation: logoFloat 4.5s ease-in-out infinite;
        }
        @keyframes logoFloat{
            0%, 100%{ transform: translateY(0); }
            50%     { transform: translateY(-4px); }
        }
        .brand-name{
            display: block; font-family: 'Outfit', sans-serif;
            font-weight: 800; font-size: 1rem; letter-spacing: 1px;
            color: #fff; line-height: 1.15;
        }
        .brand-name span{ color: var(--adm-violet); text-shadow: 0 0 10px rgba(192, 132, 252, .65); }
        .brand-sub{
            display: block; font-size: .6rem; letter-spacing: 2.6px;
            text-transform: uppercase; color: var(--adm-muted); margin-top: 2px;
        }

        /* Navegación */
        .sidebar-nav{ flex: 1 1 auto; overflow-y: auto; padding: 1rem .8rem; }
        .sidebar-nav::-webkit-scrollbar{ width: 4px; }
        .nav-section{
            font-size: .62rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 2.4px; color: rgba(155, 143, 179, .6);
            padding: .9rem .65rem .45rem; margin: 0;
        }
        .nav-item-admin{
            display: flex; align-items: center; gap: .75rem;
            padding: .68rem .8rem; margin-bottom: .28rem;
            border-radius: 12px; border: 1px solid transparent;
            color: var(--adm-muted); font-size: .89rem; font-weight: 500;
            position: relative;
            transition: all .3s ease;
        }
        .nav-item-admin i{
            width: 20px; text-align: center; font-size: .95rem;
            transition: all .3s ease;
        }
        .nav-item-admin:hover{
            color: #fff; background: rgba(147, 51, 234, .12);
            transform: translateX(4px);
        }
        .nav-item-admin:hover i{ color: var(--adm-violet); text-shadow: 0 0 8px rgba(192, 132, 252, .8); }

        /* Elemento activo: fondo morado sutil + barra de neón */
        .nav-item-admin.active{
            color: #fff;
            background: linear-gradient(90deg, rgba(147, 51, 234, .32), rgba(147, 51, 234, .06));
            border-color: rgba(147, 51, 234, .35);
            box-shadow: inset 0 0 18px rgba(147, 51, 234, .15);
        }
        .nav-item-admin.active i{ color: var(--adm-violet); text-shadow: 0 0 10px rgba(192, 132, 252, .9); }
        .nav-item-admin.active::before{
            content: ''; position: absolute; left: -.8rem; top: 20%; bottom: 20%;
            width: 3px; border-radius: 99px;
            background: var(--adm-violet);
            box-shadow: 0 0 12px var(--adm-violet);
        }

        /* Tarjeta del evento al pie del sidebar */
        .sidebar-event{
            margin: .9rem; padding: .9rem 1rem;
            border-radius: 14px; text-align: center;
            background: linear-gradient(135deg, rgba(107, 33, 168, .35), rgba(147, 51, 234, .12));
            border: 1px solid rgba(147, 51, 234, .3);
            box-shadow: inset 0 0 20px rgba(147, 51, 234, .12);
        }
        .sidebar-event .ev-title{
            display: block; font-family: 'Outfit', sans-serif;
            font-weight: 700; font-size: .85rem; color: #fff; letter-spacing: .5px;
        }
        .sidebar-event .ev-title i{ color: var(--adm-violet); text-shadow: 0 0 8px rgba(192, 132, 252, .8); }
        .sidebar-event .ev-date{
            display: block; margin-top: .25rem;
            font-size: .7rem; letter-spacing: .5px; color: var(--adm-violet);
        }

        /* Overlay para el sidebar móvil */
        .sidebar-overlay{
            position: fixed; inset: 0; z-index: 1035;
            background: rgba(3, 1, 8, .7);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            opacity: 0; visibility: hidden;
            transition: all .35s ease;
        }

        /* ==================================================
           WRAPPER + TOPBAR
           ================================================== */
        .admin-wrapper{
            position: relative; z-index: 1;
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
            transition: margin-left .35s ease;
        }

        .admin-topbar{
            position: sticky; top: 0; z-index: 1030;
            height: var(--topbar-h);
            display: flex; align-items: center; gap: 1rem;
            padding: 0 1.4rem;
            background: rgba(10, 5, 18, .72);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--adm-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .35);
        }
        .btn-sidebar-toggle{
            display: none;
            width: 42px; height: 42px; flex: 0 0 42px;
            border-radius: 11px;
            background: rgba(147, 51, 234, .14);
            border: 1px solid var(--adm-border);
            color: var(--adm-violet); font-size: 1.05rem;
            transition: all .3s ease;
        }
        .btn-sidebar-toggle:hover{ background: rgba(147, 51, 234, .3); box-shadow: var(--adm-glow); color: #fff; }

        .topbar-title{
            font-family: 'Outfit', sans-serif; font-weight: 600;
            font-size: 1rem; color: #fff; margin: 0;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .topbar-right{ margin-left: auto; display: flex; align-items: center; gap: 1rem; }

        /* Chip de usuario */
        .admin-chip{ display: flex; align-items: center; gap: .7rem; padding: .3rem .5rem; border-radius: 13px; }
        .admin-avatar{
            width: 42px; height: 42px; flex: 0 0 42px; border-radius: 50%;
            display: grid; place-items: center;
            font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; color: #fff;
            background: linear-gradient(135deg, var(--adm-purple-600), var(--adm-purple-900));
            border: 1px solid rgba(192, 132, 252, .5);
            box-shadow: 0 0 14px rgba(147, 51, 234, .45);
        }
        .a-name{ display: block; font-size: .85rem; font-weight: 600; color: #fff; line-height: 1.25; }
        .a-role{
            display: block; font-size: .62rem; letter-spacing: 1.6px;
            text-transform: uppercase; color: var(--adm-violet);
        }

        /* Botón cerrar sesión */
        .btn-logout{
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .55rem 1rem; border-radius: 11px;
            background: rgba(244, 63, 94, .1);
            border: 1px solid rgba(244, 63, 94, .35);
            color: #fda4af; font-size: .84rem; font-weight: 600;
            transition: all .3s ease;
        }
        .btn-logout:hover{
            background: rgba(244, 63, 94, .24); color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 0 16px rgba(244, 63, 94, .4);
        }

        /* ==================================================
           CONTENIDO PRINCIPAL
           ================================================== */
        .admin-main{ flex: 1; padding: 1.7rem 1.6rem 2.2rem; }

        .page-enter{ animation: fadeUp .45s ease both; }
        @keyframes fadeUp{
            from{ opacity: 0; transform: translateY(14px); }
            to  { opacity: 1; transform: translateY(0); }
        }

        /* Alertas flash (cristal oscuro) */
        .flash-alert{
            border-radius: 14px;
            background: rgba(18, 11, 30, .72)!important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid;
        }
        .flash-alert.alert-success{ border-color: rgba(34, 197, 94, .4)!important; color: #86efac!important; }
        .flash-alert.alert-danger{ border-color: rgba(244, 63, 94, .4)!important; color: #fecdd3!important; }
        .flash-alert i{ margin-right: .5rem; }

        /* Pie del panel */
        .admin-footer{
            margin-top: auto;
            padding: 1.1rem 1.6rem;
            border-top: 1px solid rgba(147, 51, 234, .14);
            display: flex; justify-content: space-between; gap: .6rem; flex-wrap: wrap;
            font-size: .74rem; color: var(--adm-muted);
        }

        /* ==================================================
           RESPONSIVE
           ================================================== */
        @media (max-width: 991.98px){
            .admin-sidebar{ transform: translateX(-105%); }
            body.sidebar-open .admin-sidebar{
                transform: translateX(0);
                box-shadow: 24px 0 60px rgba(0, 0, 0, .55);
            }
            body.sidebar-open{ overflow: hidden; }
            body.sidebar-open .sidebar-overlay{ opacity: 1; visibility: visible; }
            .admin-wrapper{ margin-left: 0; }
            .btn-sidebar-toggle{ display: grid; place-items: center; }
        }

        @media (min-width: 992px){
            .sidebar-overlay{ display: none; }
        }

        @media (max-width: 575.98px){
            .admin-meta{ display: none; }
            .admin-main{ padding: 1.2rem 1rem 1.8rem; }
            .topbar-title{ font-size: .92rem; }
            .admin-topbar{ padding: 0 1rem; gap: .7rem; }
        }

        @media (max-width: 420px){
            .btn-logout span{ display: none; }
            .btn-logout{ padding: .55rem .7rem; }
        }

        /* Accesibilidad: menos movimiento si el usuario lo prefiere */
        @media (prefers-reduced-motion: reduce){
            *, *::before, *::after{
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Orbes decorativos de fondo --}}
    <div class="bg-orb orb-1" aria-hidden="true"></div>
    <div class="bg-orb orb-2" aria-hidden="true"></div>

    {{-- Overlay del sidebar móvil --}}
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="admin-sidebar" id="adminSidebar">

        <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/logo_fest.png') }}" alt="Logo EXPO DEV 2026" class="brand-logo">
            <span class="brand-text">
                <span class="brand-name">EXPO <span>DEV</span></span>
                <span class="brand-sub">Panel Admin</span>
            </span>
        </a>

        <nav class="sidebar-nav">
            <p class="nav-section">General</p>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item-admin {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i><span>Dashboard</span>
            </a>

            <p class="nav-section">Gestión</p>

            <a href="{{ route('admin.students.index') }}"
               class="nav-item-admin {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-graduate"></i><span>Estudiantes</span>
            </a>

            <a href="{{ route('admin.activities.index') }}"
               class="nav-item-admin {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days"></i><span>Cronograma</span>
            </a>

            <a href="{{ route('admin.speakers.index') }}"
               class="nav-item-admin {{ request()->routeIs('admin.speakers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-microphone-lines"></i><span>Ponentes</span>
            </a>

            <a href="{{ route('admin.certificates.index') }}"
               class="nav-item-admin {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                <i class="fa-solid fa-award"></i><span>Certificados</span>
            </a>

            <a href="{{ route('admin.gallery.index') }}"
               class="nav-item-admin {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                <i class="fa-solid fa-images"></i><span>Galería</span>
            </a>

            <a href="{{ route('admin.payments.index') }}"
               class="nav-item-admin {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-wave"></i><span>Pagos</span>
            </a>

            <p class="nav-section">Sistema</p>

            <a href="{{ route('admin.settings.index') }}"
               class="nav-item-admin {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i><span>Configuración</span>
            </a>

            <a href="{{ route('home') }}" class="nav-item-admin" target="_blank" rel="noopener">
                <i class="fa-solid fa-globe"></i><span>Ver sitio público</span>
            </a>
        </nav>

        <div class="sidebar-event">
            <span class="ev-title"><i class="fa-solid fa-bolt"></i> EXPO DEV 2026</span>
            <span class="ev-date">30 Nov — 04 Dic · Cusco</span>
        </div>
    </aside>

    {{-- ===================== CONTENIDO ===================== --}}
    <div class="admin-wrapper">

        {{-- ---------- Topbar ---------- --}}
        <header class="admin-topbar">
            <button class="btn-sidebar-toggle" id="sidebarToggle" type="button" aria-label="Abrir menú">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="topbar-title">@yield('title', 'Panel de Control')</div>

            <div class="topbar-right">
                <div class="admin-chip">
                    <span class="admin-avatar">{{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}</span>
                    <span class="admin-meta">
                        <span class="a-name">{{ auth('admin')->user()->name ?? 'Administrador' }}</span>
                        <span class="a-role">{{ auth('admin')->user()->role ?? 'Administrador' }}</span>
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- ---------- Área principal ---------- --}}
        <main class="admin-main">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show flash-alert" role="alert">
                    <i class="fa-solid fa-circle-check"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show flash-alert" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>{{ session('error') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            <div class="page-enter">
                @yield('content')
            </div>
        </main>

        <footer class="admin-footer">
            <span>EXPO DEV 2026 · Semana Informática DSI</span>
            <span>Instituto Túpac Amaru — Cusco</span>
        </footer>
    </div>

    {{-- Bootstrap JS (alertas, dropdowns, etc.) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ---------- Sidebar móvil ----------
        const admBody      = document.body;
        const admToggle    = document.getElementById('sidebarToggle');
        const admOverlay   = document.getElementById('sidebarOverlay');
        const admToggleIcon = admToggle ? admToggle.querySelector('i') : null;

        admToggle && admToggle.addEventListener('click', () => {
            const open = admBody.classList.toggle('sidebar-open');
            if (admToggleIcon){
                admToggleIcon.classList.toggle('fa-bars', !open);
                admToggleIcon.classList.toggle('fa-xmark', open);
            }
        });

        admOverlay && admOverlay.addEventListener('click', () => {
            admBody.classList.remove('sidebar-open');
            if (admToggleIcon){
                admToggleIcon.classList.add('fa-bars');
                admToggleIcon.classList.remove('fa-xmark');
            }
        });

        // Cerrar el sidebar al navegar (móvil)
        document.querySelectorAll('.nav-item-admin').forEach(item => {
            item.addEventListener('click', () => admBody.classList.remove('sidebar-open'));
        });

        // ---------- Auto-cerrar alertas flash tras 5s ----------
        setTimeout(() => {
            document.querySelectorAll('.flash-alert').forEach(alertEl => {
                const alert = bootstrap.Alert.getOrCreateInstance(alertEl);
                alert && alert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>