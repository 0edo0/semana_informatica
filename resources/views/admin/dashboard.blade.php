@extends('layouts.admin')

@section('title', 'Panel de Control')

@section('content')

    {{-- ================================================= --}}
    {{--  EXPO DEV 2026 · Dashboard del CMS                --}}
    {{-- ================================================= --}}

    {{-- ===== Encabezado de bienvenida ===== --}}
    <div class="dash-header">
        <div class="dash-header-text">
            <span class="dash-badge">
                <i class="fa-solid fa-bolt"></i> EXPO DEV 2026 · 30 Nov — 04 Dic
            </span>
            <h1 class="dash-title">Panel de Control</h1>
            <p class="dash-sub">
                Bienvenido de nuevo, <strong>{{ auth('admin')->user()->name ?? 'Administrador' }}</strong>.
                Así va la Semana Informática de la carrera DSI hoy.
            </p>
        </div>

        <div class="dash-date glass-panel">
            <i class="fa-regular fa-calendar"></i>
            <span class="dd-text">
                <span class="dd-label">Hoy es</span>
                <span class="dd-value">{{ now()->format('d/m/Y') }}</span>
            </span>
        </div>
    </div>

    {{-- ===== Tarjetas de estadísticas ===== --}}
    <div class="stats-grid">
        <!-- Total Estudiantes -->
        <article class="stat-card glass-panel">
            <div class="stat-icon si-a"><i class="fa-solid fa-users"></i></div>
            <div class="stat-body">
                <span class="stat-value">{{ $totalStudents }}</span>
                <span class="stat-label">Total Estudiantes</span>
                <span class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> Base de datos</span>
            </div>
        </article>

        <!-- Inscripciones Hoy -->
        <article class="stat-card glass-panel">
            <div class="stat-icon si-b"><i class="fa-solid fa-user-plus"></i></div>
            <div class="stat-body">
                <span class="stat-value">{{ $registrationsToday }}</span>
                <span class="stat-label">Inscripciones Hoy</span>
                <span class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> En vivo</span>
            </div>
        </article>

        <!-- Certificados Emitidos -->
        <article class="stat-card glass-panel">
            <div class="stat-icon si-c"><i class="fa-solid fa-certificate"></i></div>
            <div class="stat-body">
                <span class="stat-value">{{ $totalCertificates }}</span>
                <span class="stat-label">Certificados Habilitados</span>
                <span class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> Listos para descarga</span>
            </div>
        </article>

        <!-- Ingresos por Pagos -->
        <article class="stat-card glass-panel">
            <div class="stat-icon si-d"><i class="fa-solid fa-money-bill-wave"></i></div>
            <div class="stat-body">
                <!-- number_format para darle formato de moneda (ej. 4960 -> 4,960.00) -->
                <span class="stat-value">S/ {{ number_format($totalRevenue, 2) }}</span>
                <span class="stat-label">Ingresos Confirmados</span>
                <span class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> Pagos completados</span>
            </div>
        </article>
    </div>

    {{-- ===== Accesos rápidos ===== --}}
    <div class="section-head">
        <h2><i class="fa-solid fa-bolt"></i> Accesos Rápidos</h2>
    </div>

    <div class="quick-grid">
        <!-- Si tienes la ruta create, cambia 'index' por 'create' aquí -->
        <a href="{{ route('admin.students.index') }}" class="quick-card glass-panel">
            <span class="q-icon qi-a"><i class="fa-solid fa-user-plus"></i></span>
            <span class="q-text">
                <span class="q-title">Registrar Estudiante</span>
                <span class="q-desc">Añade un nuevo participante a la semana</span>
            </span>
            <i class="fa-solid fa-arrow-right q-arrow"></i>
        </a>

        <a href="{{ route('admin.gallery.index') }}" class="quick-card glass-panel">
            <span class="q-icon qi-b"><i class="fa-solid fa-camera-retro"></i></span>
            <span class="q-text">
                <span class="q-title">Subir Foto a Galería</span>
                <span class="q-desc">Publica los mejores momentos del evento</span>
            </span>
            <i class="fa-solid fa-arrow-right q-arrow"></i>
        </a>

        <a href="{{ route('admin.certificates.index') }}" class="quick-card glass-panel">
            <span class="q-icon qi-c"><i class="fa-solid fa-clipboard-check"></i></span>
            <span class="q-text">
                <span class="q-title">Habilitar Certificados</span>
                <span class="q-desc">Gestiona y libera las constancias</span>
            </span>
            <i class="fa-solid fa-arrow-right q-arrow"></i>
        </a>
    </div>

    {{-- ===== Últimos registros ===== --}}
    <div class="table-card glass-panel">
        <div class="table-head">
            <div>
                <h2><i class="fa-solid fa-clock-rotate-left"></i> Últimos estudiantes registrados</h2>
                <p>Los registros más recientes del formulario de inscripción</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="btn-view-all">
                Ver todos <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle students-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Semestre</th>
                        <th>Fecha</th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Bucle para los datos reales --}}
                    @forelse($latestStudents as $student)
                        <tr>
                            <td>
                                <span class="student-cell">
                                    {{-- Generamos las iniciales del nombre automáticamente --}}
                                    <span class="s-avatar">
                                        {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                    </span>
                                    <span class="s-name">{{ $student->first_name }} {{ $student->last_name }}</span>
                                </span>
                            </td>
                            <td><span class="s-dni">{{ $student->dni }}</span></td>
                            <td>
                                {{-- Convertimos el número de semestre a romano para mantener el diseño original --}}
                                <span class="s-sem">
                                    @if($student->semester == 2) II
                                    @elseif($student->semester == 4) IV
                                    @elseif($student->semester == 6) VI
                                    @else {{ $student->semester }}
                                    @endif
                                </span>
                            </td>
                            {{-- Formateamos la fecha para que se vea como en el diseño original: 13/11/2026 · 08:12 --}}
                            <td>{{ $student->created_at ? $student->created_at->format('d/m/Y · H:i') : '—' }}</td>
                            <td class="text-end">
                                <a href="#" class="btn-ver"><i class="fa-regular fa-eye"></i> Ver</a>
                            </td>
                        </tr>
                    @empty
                        {{-- Qué mostrar si la base de datos está vacía --}}
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-inbox fs-3 mb-2 d-block" style="color: var(--adm-violet);"></i>
                                Aún no hay estudiantes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('styles')
<style>
    /* ==================================================
       Dashboard EXPO DEV 2026
       ================================================== */

    /* ---------- Encabezado de bienvenida ---------- */
    .dash-header{
        display: flex; align-items: flex-end; justify-content: space-between;
        gap: 1.2rem; flex-wrap: wrap; margin-bottom: 1.7rem;
    }
    .dash-badge{
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .34rem .8rem; border-radius: 99px;
        font-size: .7rem; font-weight: 600;
        letter-spacing: 1.2px; text-transform: uppercase;
        color: var(--adm-violet);
        background: rgba(147, 51, 234, .13);
        border: 1px solid rgba(147, 51, 234, .4);
        box-shadow: 0 0 14px rgba(147, 51, 234, .2);
    }
    .dash-badge i{ text-shadow: 0 0 8px rgba(192, 132, 252, .8); }
    .dash-title{
        font-size: clamp(1.8rem, 3.4vw, 2.5rem);
        font-weight: 800; color: #fff; letter-spacing: .5px;
        margin: .6rem 0 .4rem;
        text-shadow: 0 0 28px rgba(147, 51, 234, .45);
    }
    .dash-sub{ color: var(--adm-muted); font-size: .95rem; margin: 0; max-width: 62ch; }
    .dash-sub strong{ color: var(--adm-violet); font-weight: 600; }

    .dash-date{
        display: flex; align-items: center; gap: .85rem;
        padding: .85rem 1.15rem;
        animation: fadeUp .5s ease both;
    }
    .dash-date > i{
        font-size: 1.5rem; color: var(--adm-violet);
        filter: drop-shadow(0 0 8px rgba(192, 132, 252, .6));
    }
    .dd-label{
        display: block; font-size: .64rem; text-transform: uppercase;
        letter-spacing: 1.8px; color: var(--adm-muted);
    }
    .dd-value{ font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.05rem; color: #fff; }

    /* ---------- Títulos de sección ---------- */
    .section-head h2{
        display: flex; align-items: center; gap: .6rem;
        font-size: 1.15rem; font-weight: 700; color: #fff;
        margin: 1.9rem 0 1rem;
    }
    .section-head h2 i{
        color: var(--adm-violet);
        filter: drop-shadow(0 0 7px rgba(192, 132, 252, .7));
    }

    /* ---------- Tarjetas de estadísticas ---------- */
    .stats-grid{
        display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem;
    }
    .stat-card{
        display: flex; align-items: center; gap: 1rem;
        padding: 1.25rem 1.3rem;
        position: relative; overflow: hidden;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        animation: fadeUp .55s ease both;
    }
    .stat-card:nth-child(1){ animation-delay: .05s; }
    .stat-card:nth-child(2){ animation-delay: .12s; }
    .stat-card:nth-child(3){ animation-delay: .19s; }
    .stat-card:nth-child(4){ animation-delay: .26s; }
    .stat-card::after{
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at 88% 10%, rgba(147, 51, 234, .16), transparent 55%);
    }
    .stat-card:hover{
        transform: translateY(-5px);
        border-color: rgba(192, 132, 252, .55);
        box-shadow: 0 14px 36px rgba(0, 0, 0, .45), 0 0 26px rgba(147, 51, 234, .3);
    }
    .stat-icon{
        width: 58px; height: 58px; flex: 0 0 58px;
        border-radius: 15px; display: grid; place-items: center;
        font-size: 1.4rem;
        transition: transform .3s ease;
    }
    .stat-card:hover .stat-icon{ transform: scale(1.1) rotate(-5deg); }
    .si-a{
        color: #c084fc; background: rgba(147, 51, 234, .16);
        border: 1px solid rgba(147, 51, 234, .45);
        box-shadow: inset 0 0 16px rgba(147, 51, 234, .28), 0 0 12px rgba(147, 51, 234, .18);
    }
    .si-b{
        color: #e879f9; background: rgba(192, 38, 211, .13);
        border: 1px solid rgba(217, 70, 239, .4);
        box-shadow: inset 0 0 16px rgba(217, 70, 239, .22), 0 0 12px rgba(217, 70, 239, .15);
    }
    .si-c{
        color: #a78bfa; background: rgba(124, 58, 237, .15);
        border: 1px solid rgba(124, 58, 237, .45);
        box-shadow: inset 0 0 16px rgba(124, 58, 237, .25), 0 0 12px rgba(124, 58, 237, .16);
    }
    .si-d{
        color: #d8b4fe; background: rgba(107, 33, 168, .2);
        border: 1px solid rgba(192, 132, 252, .45);
        box-shadow: inset 0 0 16px rgba(107, 33, 168, .3), 0 0 12px rgba(147, 51, 234, .2);
    }
    .stat-body{ min-width: 0; }
    .stat-value{
        display: block; font-family: 'Outfit', sans-serif;
        font-weight: 800; font-size: 1.65rem; color: #fff;
        line-height: 1.15; white-space: nowrap;
        text-shadow: 0 0 18px rgba(147, 51, 234, .5);
    }
    .stat-label{ display: block; color: var(--adm-muted); font-size: .8rem; margin-top: .1rem; }
    .stat-trend{
        display: inline-flex; align-items: center; gap: .35rem;
        margin-top: .5rem; padding: .2rem .6rem; border-radius: 99px;
        font-size: .68rem; font-weight: 600;
    }
    .stat-trend.up{
        color: #86efac;
        background: rgba(34, 197, 94, .1);
        border: 1px solid rgba(34, 197, 94, .3);
    }

    /* ---------- Accesos rápidos ---------- */
    .quick-grid{
        display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem;
    }
    .quick-card{
        display: flex; align-items: center; gap: 1rem;
        padding: 1.2rem 1.3rem;
        color: var(--adm-text);
        position: relative; overflow: hidden;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease, color .3s ease;
        animation: fadeUp .55s ease both;
    }
    .quick-card:nth-child(1){ animation-delay: .1s; }
    .quick-card:nth-child(2){ animation-delay: .17s; }
    .quick-card:nth-child(3){ animation-delay: .24s; }
    .quick-card::before{
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
        background: linear-gradient(180deg, var(--adm-violet), var(--adm-purple-900));
        opacity: 0; transition: opacity .3s ease;
    }
    .quick-card:hover{
        transform: translateY(-4px) scale(1.01);
        color: #fff;
        border-color: rgba(192, 132, 252, .55);
        box-shadow: 0 16px 38px rgba(0, 0, 0, .5), 0 0 28px rgba(147, 51, 234, .3);
    }
    .quick-card:hover::before{ opacity: 1; }
    .q-icon{
        width: 52px; height: 52px; flex: 0 0 52px;
        border-radius: 14px; display: grid; place-items: center;
        font-size: 1.25rem;
        transition: transform .3s ease;
    }
    .quick-card:hover .q-icon{ transform: scale(1.12) rotate(-6deg); }
    .qi-a{
        color: #c084fc; background: rgba(147, 51, 234, .16);
        border: 1px solid rgba(147, 51, 234, .45);
        box-shadow: inset 0 0 14px rgba(147, 51, 234, .25);
    }
    .qi-b{
        color: #e879f9; background: rgba(192, 38, 211, .13);
        border: 1px solid rgba(217, 70, 239, .4);
        box-shadow: inset 0 0 14px rgba(217, 70, 239, .2);
    }
    .qi-c{
        color: #a78bfa; background: rgba(124, 58, 237, .15);
        border: 1px solid rgba(124, 58, 237, .45);
        box-shadow: inset 0 0 14px rgba(124, 58, 237, .24);
    }
    .q-text{ min-width: 0; display: flex; flex-direction: column; }
    .q-title{ font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .98rem; color: #fff; }
    .q-desc{ font-size: .76rem; color: var(--adm-muted); }
    .q-arrow{
        margin-left: auto; font-size: .95rem; color: var(--adm-violet);
        opacity: .4; transition: all .3s ease;
    }
    .quick-card:hover .q-arrow{
        opacity: 1; transform: translateX(5px);
        text-shadow: 0 0 10px rgba(192, 132, 252, .9);
    }

    /* ---------- Tabla: últimos registros ---------- */
    .table-card{
        margin-top: 1.8rem;
        padding: 1.4rem 1.4rem .9rem;
        animation: fadeUp .6s ease both;
        animation-delay: .2s;
    }
    .table-head{
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; flex-wrap: wrap; margin-bottom: 1.1rem;
    }
    .table-head h2{
        display: flex; align-items: center; gap: .6rem;
        font-size: 1.12rem; font-weight: 700; color: #fff; margin: 0;
    }
    .table-head h2 i{
        color: var(--adm-violet);
        filter: drop-shadow(0 0 7px rgba(192, 132, 252, .7));
    }
    .table-head p{ margin: .15rem 0 0; font-size: .76rem; color: var(--adm-muted); }
    .btn-view-all{
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .5rem 1rem; border-radius: 11px;
        font-size: .8rem; font-weight: 600;
        color: var(--adm-violet);
        background: rgba(147, 51, 234, .12);
        border: 1px solid rgba(147, 51, 234, .4);
        transition: all .3s ease;
    }
    .btn-view-all:hover{
        color: #fff; background: var(--adm-purple-600);
        border-color: var(--adm-purple-600);
        box-shadow: 0 0 18px rgba(147, 51, 234, .5);
        transform: translateY(-1px);
    }

    .students-table{
        --bs-table-bg: transparent;
        --bs-table-color: var(--adm-text);
        margin-bottom: .3rem;
    }
    .students-table thead th{
        font-size: .66rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 1.6px;
        color: var(--adm-violet);
        border-bottom: 1px solid rgba(147, 51, 234, .35) !important;
        padding: .75rem; white-space: nowrap;
    }
    .students-table tbody td{
        padding: .85rem .75rem;
        border-bottom: 1px solid rgba(147, 51, 234, .12);
        font-size: .86rem;
    }
    .students-table tbody tr:last-child td{ border-bottom: none; }
    .students-table tbody tr{ transition: background .25s ease; }
    .students-table tbody tr:hover{ background: rgba(147, 51, 234, .09); }

    .student-cell{ display: flex; align-items: center; gap: .75rem; min-width: 210px; }
    .s-avatar{
        width: 38px; height: 38px; flex: 0 0 38px; border-radius: 11px;
        display: inline-grid; place-items: center;
        font-family: 'Outfit', sans-serif; font-size: .72rem; font-weight: 700; color: #fff;
        background: linear-gradient(135deg, var(--adm-purple-600), var(--adm-purple-900));
        border: 1px solid rgba(192, 132, 252, .4);
        box-shadow: 0 0 12px rgba(147, 51, 234, .35);
    }
    .s-name{ font-weight: 600; color: #fff; font-size: .87rem; }
    .s-dni{ letter-spacing: 1.5px; font-weight: 500; }
    .s-sem{
        display: inline-grid; place-items: center; min-width: 44px;
        padding: .28rem .55rem; border-radius: 9px;
        font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .78rem;
        color: var(--adm-violet);
        background: rgba(147, 51, 234, .14);
        border: 1px solid rgba(147, 51, 234, .4);
    }
    .btn-ver{
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .4rem .85rem; border-radius: 10px;
        font-size: .78rem; font-weight: 600;
        color: var(--adm-violet);
        border: 1px solid rgba(147, 51, 234, .4);
        background: rgba(147, 51, 234, .08);
        transition: all .3s ease;
    }
    .btn-ver:hover{
        color: #fff; background: var(--adm-purple-600);
        border-color: var(--adm-purple-600);
        box-shadow: 0 0 16px rgba(147, 51, 234, .5);
    }

    /* ---------- Responsivo ---------- */
    @media (max-width: 1199.98px){
        .stats-grid{ grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 991.98px){
        .quick-grid{ grid-template-columns: 1fr; }
    }
    @media (max-width: 575.98px){
        .stats-grid{ grid-template-columns: 1fr; }
        .dash-date{ align-self: flex-start; }
        .student-cell{ min-width: 180px; }
    }
</style>
@endpush