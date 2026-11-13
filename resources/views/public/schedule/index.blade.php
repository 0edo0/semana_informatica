{{-- ==========================================================================
|   SEMANA INFORMÁTICA — DEV FEST 2026
|   Vista:   resources/views/public/schedule/index.blade.php
|   (Corregido: Alineación de tiempos y grid responsive)
========================================================================== --}}

@extends('layouts.public')

@section('title', 'Cronograma · DEV FEST 2026 — Semana Informática DSI')

@section('content')

    @php
        $MESES  = [1 => 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
        $SEMANA = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    @endphp

    <!-- HERO -->
    <header id="cronograma" class="sch-hero position-relative overflow-hidden">
        <div class="sch-orb sch-orb-1" aria-hidden="true"></div>
        <div class="sch-orb sch-orb-2" aria-hidden="true"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="text-center">
                <div data-aos="fade-down">
                    <span class="sch-badge">
                        <i class="fa-regular fa-calendar-days me-2"></i>
                        30 NOV — 04 DIC 2026 · Instituto Túpac Amaru — Cusco
                    </span>
                </div>

                <h1 class="sch-title" data-aos="fade-up" data-aos-delay="100">
                    <span class="title-line">Cronograma</span>
                    <span class="text-neon-sch">del Evento</span>
                </h1>

                <p class="sch-sub mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Cinco días de ponencias, talleres y tecnología. Revisa las horas de cada
                    actividad y <span class="sch-highlight">no te pierdas a tus ponentes favoritos</span>.
                </p>

                <nav class="sch-days-nav" data-aos="fade-up" data-aos-delay="300" aria-label="Ir a un día del evento">
                    @foreach($scheduleDays as $i => $day)
                        @php($chipDate = \Carbon\Carbon::parse($day->date))
                        <a href="#dia-{{ $day->id }}" class="sch-chip">
                            <span class="chip-day">Día {{ $i + 1 }}</span>
                            <span class="chip-date">{{ $chipDate->format('d') }} {{ $MESES[(int) $chipDate->format('n')] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </header>

    <!-- LISTADO -->
    <main class="sch-main container position-relative" style="z-index: 2;">
        @forelse($scheduleDays as $i => $day)
            @php($schDate = \Carbon\Carbon::parse($day->date))

            <section id="dia-{{ $day->id }}" class="day-card" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                <header class="day-head">
                    <div class="date-block" aria-hidden="true">
                        <span class="date-num">{{ $schDate->format('d') }}</span>
                        <span class="date-month">{{ $MESES[(int) $schDate->format('n')] }}</span>
                    </div>

                    <div class="day-titles">
                        <span class="day-tag">
                            <i class="fa-solid fa-bolt"></i>
                            Día {{ $i + 1 }} · {{ $SEMANA[(int) $schDate->format('w')] }}
                            @if($schDate->isToday())<span class="day-today">¡Hoy!</span>@endif
                        </span>
                        <h2 class="day-title">{{ $day->title }}</h2>
                    </div>

                    <span class="day-count">
                        <i class="fa-solid fa-microphone-lines"></i>
                        {{ $day->activities->count() }} {{ $day->activities->count() === 1 ? 'ponencia' : 'ponencias' }}
                    </span>
                </header>

                <div class="timeline">
                    @if($day->activities->isEmpty())
                        <div class="tl-empty">
                            <i class="fa-solid fa-hourglass-half"></i>
                            <p>Próximamente más detalles</p>
                            <span>Estamos preparando la agenda de este día. ¡Mantente atento!</span>
                        </div>
                    @else
                        @foreach($day->activities as $activity)
                            @php($ini = \Carbon\Carbon::parse($activity->start_time))
                            @php($fin = !empty($activity->end_time) ? \Carbon\Carbon::parse($activity->end_time) : null)

                            <article class="tl-item" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 5) * 60 }}">
                                
                                {{-- CONTENEDOR DE LA HORA (Corregido) --}}
                                <div class="tl-time">
                                    <span class="time-start">{{ $ini->format('H:i') }}</span>
                                    @if($fin)
                                        <span class="time-sep">—</span>
                                        <span class="time-end">{{ $fin->format('H:i') }}</span>
                                    @endif
                                </div>

                                <div class="tl-rail"><span class="tl-dot"></span></div>

                                <div class="tl-body">
                                    <div class="tl-top">
                                        <h3 class="tl-title">{{ $activity->title }}</h3>
                                    </div>
                                    
                                    @if(!empty($activity->description))
                                        <p class="tl-desc">{{ \Illuminate\Support\Str::limit($activity->description, 160) }}</p>
                                    @endif

                                    @if(!empty($activity->speaker))
                                        <div class="speaker-chip">
                                            @if(!empty($activity->speaker->photo_path))
                                                <img class="spk-photo" src="{{ $activity->speaker->photo_path }}" alt="{{ $activity->speaker->name }}" loading="lazy">
                                            @else
                                                <span class="spk-photo spk-initial">{{ strtoupper(substr($activity->speaker->name, 0, 1)) }}</span>
                                            @endif
                                            <span class="spk-info">
                                                <span class="spk-label">Ponente</span>
                                                <span class="spk-name">{{ $activity->speaker->name }}</span>
                                                @if(!empty($activity->speaker->profession))
                                                    <span class="spk-role">{{ $activity->speaker->profession }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    @endif
                </div>
            </section>
        @empty
            <section class="day-card sch-empty-global text-center">
                <i class="fa-regular fa-calendar-xmark"></i>
                <h2 class="day-title">El cronograma aún no ha sido publicado</h2>
                <p class="tl-desc mx-auto">Estamos preparando cinco días increíbles para ti. Vuelve a revisar muy pronto.</p>
            </section>
        @endforelse

        <section class="sch-cta text-center" data-aos="zoom-in">
            <h2 class="cta-title">¿Listo para no perderte nada?</h2>
            <p class="cta-sub">Asegura tu lugar en las ponencias y talleres del DEV FEST 2026.</p>
            <a href="{{ route('registration.create') }}" class="sch-btn-neon">
                <i class="fa-solid fa-user-plus me-2"></i> Inscribirme ahora
            </a>
        </section>
    </main>

@endsection

@push('styles')
<style>
    :root {
        --df-black:      var(--color-deep, #050208);
        --df-purple-900: var(--color-purple, #6b21a8);
        --df-purple-600: var(--color-electric, #9333ea);
        --df-violet:     var(--color-violet, #c084fc);
        --df-text:       #e9e2f5;
        --df-muted:      #9b8fb3;
        --df-border:     rgba(147, 51, 234, .22);
    }

    #cronograma, .day-card { scroll-margin-top: 96px; }

    /* HERO */
    .sch-hero { padding: 8rem 0 3rem; }
    .sch-orb { position: absolute; border-radius: 50%; filter: blur(90px); z-index: 1; pointer-events: none; }
    .sch-orb-1 { width: 420px; height: 420px; top: -140px; right: -100px; background: radial-gradient(circle, rgba(147, 51, 234, .28), transparent 70%); animation: schDrift 13s ease-in-out infinite alternate; }
    .sch-orb-2 { width: 340px; height: 340px; bottom: -110px; left: -110px; background: radial-gradient(circle, rgba(107, 33, 168, .26), transparent 70%); animation: schDrift 15s ease-in-out infinite alternate-reverse; }
    @keyframes schDrift { from { transform: translate(0, 0) scale(1); } to { transform: translate(-35px, 25px) scale(1.1); } }

    .sch-badge { display: inline-flex; align-items: center; padding: .5rem 1.2rem; border-radius: 50rem; border: 1px solid rgba(192, 132, 252, .40); background: rgba(147, 51, 234, .12); backdrop-filter: blur(10px); color: var(--df-violet); font-size: .85rem; font-weight: 500; letter-spacing: .5px; box-shadow: 0 0 18px rgba(147, 51, 234, .25); margin-bottom: 1rem; }
    
    .sch-title { font-size: clamp(2.4rem, 6vw, 4.4rem); font-weight: 800; line-height: 1; letter-spacing: -1.5px; margin-bottom: 1rem; }
    .sch-title .title-line { display: block; background: linear-gradient(180deg, #ffffff 30%, #d8b4fe 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 22px rgba(147, 51, 234, .35)); }
    .text-neon-sch { color: var(--df-violet); text-shadow: 0 0 18px rgba(192, 132, 252, .75), 0 0 45px rgba(147, 51, 234, .45); }
    .sch-sub { color: var(--df-muted); font-size: 1rem; max-width: 58ch; margin-bottom: 1.6rem; }
    .sch-highlight { color: var(--df-violet); font-weight: 600; }

    /* CHIPS */
    .sch-days-nav { display: flex; justify-content: center; flex-wrap: wrap; gap: .6rem; }
    .sch-chip { display: inline-flex; align-items: center; gap: .5rem; padding: .5rem .95rem; border-radius: 50rem; background: rgba(18, 11, 30, .55); border: 1px solid var(--df-border); backdrop-filter: blur(10px); transition: all .3s ease; text-decoration: none; }
    .sch-chip .chip-day { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .8rem; color: #fff; text-transform: uppercase; letter-spacing: 1px; }
    .sch-chip .chip-date { font-size: .74rem; color: var(--df-violet); font-weight: 600; letter-spacing: .5px; }
    .sch-chip:hover, .sch-chip.active { transform: translateY(-2px); border-color: rgba(192, 132, 252, .65); background: rgba(147, 51, 234, .18); box-shadow: 0 0 20px rgba(147, 51, 234, .40); }

    /* TARJETA DÍA */
    .sch-main { padding-bottom: 3.5rem; }
    .day-card { background: rgba(12, 7, 20, .55); backdrop-filter: blur(14px); border: 1px solid var(--df-border); border-radius: 20px; padding: 1.6rem 1.7rem 1.2rem; margin-bottom: 2rem; box-shadow: 0 0 30px rgba(147, 51, 234, .12); transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease; }
    .day-card:hover { transform: translateY(-4px); border-color: rgba(192, 132, 252, .45); box-shadow: 0 0 45px rgba(147, 51, 234, .28); }

    .day-head { display: flex; align-items: center; gap: 1.1rem; flex-wrap: wrap; padding-bottom: 1.2rem; margin-bottom: 1.4rem; border-bottom: 1px solid rgba(147, 51, 234, .18); }
    .date-block { flex: 0 0 auto; min-width: 86px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: .6rem .9rem; border-radius: 16px; background: linear-gradient(150deg, rgba(147, 51, 234, .38), rgba(107, 33, 168, .16)); border: 1px solid rgba(192, 132, 252, .45); box-shadow: 0 0 20px rgba(147, 51, 234, .30); }
    .date-num { font-family: 'Outfit', sans-serif; font-size: 2.5rem; font-weight: 800; line-height: 1; color: #fff; text-shadow: 0 0 16px rgba(192, 132, 252, .6); }
    .date-month { font-size: .78rem; font-weight: 700; letter-spacing: 3px; color: var(--df-violet); }
    
    .day-titles { flex: 1 1 220px; min-width: 0; }
    .day-tag { display: inline-flex; align-items: center; gap: .45rem; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: var(--df-violet); }
    .day-today { padding: .12rem .55rem; border-radius: 50rem; background: var(--df-purple-600); color: #fff; font-size: .62rem; letter-spacing: 1.5px; box-shadow: 0 0 14px rgba(147, 51, 234, .65); }
    .day-title { font-size: 1.5rem; font-weight: 700; color: #fff; margin: .3rem 0 0; }
    .day-count { display: inline-flex; align-items: center; gap: .45rem; padding: .42rem .9rem; border-radius: 50rem; font-size: .76rem; font-weight: 600; color: var(--df-violet); background: rgba(147, 51, 234, .10); border: 1px solid var(--df-border); white-space: nowrap; }

    /* ==========================================================
       TIMELINE CORREGIDO
    ========================================================== */
    .timeline { position: relative; }

    .tl-item {
        display: grid;
        /* Ajustamos las columnas: La hora ahora mide 105px para que quepa "11:00 — 11:30" */
        grid-template-columns: 105px 26px minmax(0, 1fr);
        column-gap: 1rem;
        padding-bottom: 1.4rem;
    }
    .tl-item:last-child { padding-bottom: .3rem; }

    /* Cajita de la hora corregida */
    .tl-time {
        justify-self: end;
        align-self: start;
        display: inline-flex;
        align-items: center;
        justify-content: center; /* Centrado para que se vea simétrico */
        gap: .2rem;
        padding: .4rem .6rem;
        margin-top: .15rem;
        border-radius: 11px;
        background: rgba(147, 51, 234, .10);
        border: 1px solid var(--df-border);
        color: var(--df-violet);
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: .82rem; /* Un poco más pequeño para que no desborde */
        white-space: nowrap; /* Evita que el texto salte de línea */
        width: 100%; /* Ocupa los 105px asignados */
        max-width: 105px;
    }
    .time-sep { opacity: .55; font-weight: 400; margin: 0 2px; }

    /* Línea vertical */
    .tl-rail { position: relative; display: flex; justify-content: center; }
    .tl-rail::before { content: ''; position: absolute; top: 0; bottom: -1.4rem; left: 50%; width: 2px; transform: translateX(-50%); background: linear-gradient(180deg, rgba(147, 51, 234, .55), rgba(147, 51, 234, .14)); }
    .tl-item:last-child .tl-rail::before { bottom: calc(100% - 14px); }
    
    .tl-dot { position: relative; z-index: 1; width: 14px; height: 14px; margin-top: .9rem; border-radius: 50%; background: linear-gradient(135deg, var(--df-purple-600), var(--df-violet)); box-shadow: 0 0 12px rgba(147, 51, 234, .85); border: 2px solid rgba(5, 2, 8, .9); }

    /* Tarjeta interior */
    .tl-body { background: rgba(255, 255, 255, .03); border: 1px solid rgba(147, 51, 234, .16); border-radius: 15px; padding: 1rem 1.1rem; transition: border-color .3s ease, box-shadow .3s ease, transform .3s ease, background .3s ease; }
    .tl-body:hover { transform: translateX(4px); border-color: rgba(192, 132, 252, .45); background: rgba(147, 51, 234, .07); box-shadow: 0 0 24px rgba(147, 51, 234, .22); }
    
    .tl-top { display: flex; align-items: baseline; justify-content: space-between; gap: .8rem; flex-wrap: wrap; }
    .tl-title { font-size: 1.06rem; font-weight: 700; color: #fff; margin: 0; }
    .tl-desc { color: var(--df-muted); font-size: .87rem; margin: .5rem 0 0; }

    /* Ponente */
    .speaker-chip { display: flex; align-items: center; gap: .8rem; margin-top: .85rem; padding: .6rem .8rem; border-radius: 13px; background: rgba(147, 51, 234, .10); border: 1px solid rgba(147, 51, 234, .30); transition: box-shadow .3s ease, border-color .3s ease; }
    .speaker-chip:hover { border-color: rgba(192, 132, 252, .55); box-shadow: 0 0 16px rgba(147, 51, 234, .30); }
    .spk-photo { width: 46px; height: 46px; flex: 0 0 46px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(192, 132, 252, .55); box-shadow: 0 0 12px rgba(147, 51, 234, .45); }
    .spk-initial { display: grid; place-items: center; font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.1rem; color: #fff; background: linear-gradient(135deg, var(--df-purple-600), var(--df-purple-900)); }
    .spk-info { min-width: 0; }
    .spk-label { display: block; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--df-violet); opacity: .85; }
    .spk-name { display: block; color: #fff; font-weight: 600; font-size: .9rem; line-height: 1.3; }
    .spk-role { display: block; font-size: .72rem; color: var(--df-muted); }

    /* Empty states */
    .tl-empty { text-align: center; padding: 1.8rem 1rem; border: 1px dashed rgba(147, 51, 234, .40); border-radius: 15px; background: rgba(147, 51, 234, .05); }
    .tl-empty i { font-size: 1.7rem; color: var(--df-violet); margin-bottom: .5rem; }
    .tl-empty p { color: #fff; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; margin: 0 0 .2rem; }
    .tl-empty span { color: var(--df-muted); font-size: .82rem; }

    /* CTA */
    .sch-cta { margin-top: 3rem; padding: 2.4rem 1.5rem; border-radius: 20px; background: linear-gradient(135deg, rgba(107, 33, 168, .35), rgba(147, 51, 234, .10)); border: 1px solid rgba(147, 51, 234, .35); box-shadow: 0 0 40px rgba(147, 51, 234, .22); }
    .cta-title { font-size: clamp(1.5rem, 3.4vw, 2.1rem); font-weight: 800; color: #fff; margin-bottom: .4rem; }
    .cta-sub { color: var(--df-muted); margin-bottom: 1.3rem; }
    .sch-btn-neon { display: inline-flex; align-items: center; padding: .8rem 1.9rem; border-radius: 14px; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .95rem; color: #fff; background: linear-gradient(135deg, var(--df-purple-600), var(--df-purple-900)); border: 1px solid rgba(192, 132, 252, .6); box-shadow: 0 0 22px rgba(147, 51, 234, .5); transition: all .3s ease; }
    .sch-btn-neon:hover { transform: translateY(-3px) scale(1.02); color: #fff; box-shadow: 0 0 38px rgba(147, 51, 234, .75); }

    /* ==========================================================
       RESPONSIVE (Corregido para Móviles)
    ========================================================== */
    @media (max-width: 991.98px){
        .sch-hero { padding: 7rem 0 2.6rem; }
        .day-card { padding: 1.4rem 1.3rem 1rem; }
    }

    @media (max-width: 767.98px){
        .sch-hero { padding: 6.2rem 0 2.2rem; }
        .sch-sub { font-size: .93rem; }
        .day-card { padding: 1.2rem 1rem 1rem; border-radius: 18px; margin-bottom: 1.5rem; }
        .date-block { min-width: 72px; padding: .45rem .7rem; border-radius: 14px; }
        .date-num { font-size: 2rem; }
        .day-title { font-size: 1.22rem; }
        
        /* En móvil, la hora va ARRIBA de la tarjeta, no a la izquierda */
        .tl-item {
            grid-template-columns: 26px minmax(0, 1fr);
            grid-template-areas: 
                "rail time" 
                "rail body";
            row-gap: .4rem;
            column-gap: .8rem;
            padding-bottom: 1.2rem;
        }
        .tl-rail { grid-area: rail; }
        .tl-dot { margin-top: .5rem; }
        
        /* La cajita de hora en móvil vuelve a ser compacta */
        .tl-time { 
            grid-area: time; 
            justify-self: start; 
            width: auto; 
            max-width: none; 
            padding: .3rem .7rem; 
            font-size: .8rem; 
        }
        .tl-body { grid-area: body; padding: .85rem .9rem; }
        .spk-photo { width: 40px; height: 40px; flex-basis: 40px; }
    }

    @media (max-width: 575.98px){
        .sch-days-nav { flex-wrap: nowrap; justify-content: flex-start; overflow-x: auto; padding-bottom: .5rem; scrollbar-width: none; }
        .sch-days-nav::-webkit-scrollbar { display: none; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.AOS && typeof AOS.refreshHard === 'function') { AOS.refreshHard(); }
        // Scrollspy logic se mantiene igual...
    });
</script>
@endpush