{{-- ==========================================================================
|   EXPODEV 2026 — SEMANA DE INFORMÁTICA DSI
|   Instituto Túpac Amaru — Cusco · 30 NOV al 04 DIC 2026 (5 días)
|
|   Vista:  resources/views/public/schedule/index.blade.php
|
|   REDISEÑO tipo "cartel / banner" para las ponencias del cronograma:
|   · Cada ponencia es un banner horizontal con imagen de fondo (estilo
|     cartel de programa semanal).
|   · La foto del ponente (photo_path) se usa como avatar Y como imagen
|     de fondo del banner de su ponencia.
|   · Si la imagen no existe o no carga, automáticamente se muestra una
|     imagen por defecto de sistemas/tecnología (desde la web).
|   · La lógica de base de datos NO cambia:
|       $scheduleDays → $day->title / $day->date / $day->activities
|       $activity     → title, start_time, end_time, description, speaker
|       $activity->speaker → name, photo_path, profession
|   · Mobile first: en pantallas pequeñas el banner se apila, la hora pasa
|     arriba y todo queda legible sobre el fondo morado oscuro.
========================================================================== --}}

@extends('layouts.public')

@section('title', 'Cronograma · EXPODEV 2026 — Semana Informática DSI')

@section('content')

    @php
        $MESES  = [1 => 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
        $SEMANA = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        /* Acento de color por día (R, G, B) → se usa como rgb(var(--day-rgb))
           y rgba(var(--day-rgb), .4) en los banners de ese día. */
        $DAY_RGB = [
            '147, 51, 234',   // Día 1 · púrpura eléctrico
            '192, 132, 252',  // Día 2 · violeta neón
            '217, 70, 239',   // Día 3 · fucsia
            '139, 92, 246',   // Día 4 · violeta profundo
            '168, 85, 247',   // Día 5 · púrpura suave
        ];

        /* Imágenes por defecto relacionadas a SISTEMAS / TECNOLOGÍA.
           Se usan cuando el ponente no tiene foto O cuando esa foto
           falla al cargar (el JS de abajo hace el intercambio). */
        $DF_BG = [
            'https://images.unsplash.com/photo-1518770660439-4636195af496?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?q=80&w=1600&auto=format&fit=crop',
        ];

        $dfImg = 0; /* contador global: varía la imagen por defecto de cada banner */
    @endphp

    <!-- ============================== HERO ============================== -->
    <header id="cronograma" class="sch-hero position-relative overflow-hidden">
        <div class="sch-orb sch-orb-1" aria-hidden="true"></div>
        <div class="sch-orb sch-orb-2" aria-hidden="true"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="text-center">
                <div data-aos="fade-down">
                    <span class="sch-badge">
                        <i class="fa-regular fa-calendar-days me-2"></i>
                        EXPODEV 2026 · 30 NOV — 04 DIC · Instituto Túpac Amaru — Cusco
                    </span>
                </div>

                <h1 class="sch-title" data-aos="fade-up" data-aos-delay="100">
                    <span class="title-line">Cronograma</span>
                    <span class="text-neon-sch">del Evento</span>
                </h1>

                <p class="sch-sub mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Cinco días de ponencias, talleres y tecnología. Explora el programa
                    día por día y <span class="sch-highlight">no te pierdas a tus ponentes favoritos</span>.
                </p>

                <nav class="sch-days-nav" data-aos="fade-up" data-aos-delay="300" aria-label="Ir a un día del evento">
                    @foreach($scheduleDays as $i => $day)
                        <a href="#dia-{{ $day->id }}" class="sch-chip">
                            <span class="chip-day">Día {{ $i + 1 }}</span>
                            <span class="chip-date">{{ \Carbon\Carbon::parse($day->date)->format('d') }} {{ $MESES[(int) \Carbon\Carbon::parse($day->date)->format('n')] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </header>

    <!-- ============================ LISTADO ============================= -->
    <main class="sch-main container position-relative" style="z-index: 2;">
        @forelse($scheduleDays as $i => $day)
            @php
                $schDate = \Carbon\Carbon::parse($day->date);
                $dayRgb  = $DAY_RGB[$i % count($DAY_RGB)];
            @endphp

            <section id="dia-{{ $day->id }}" class="day-block" style="--day-rgb: {{ $dayRgb }};" data-aos="fade-up">
                <!-- Cabecera del día -->
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

                <!-- Banners de ponencias del día -->
                <div class="day-banners">
                    @if($day->activities->isEmpty())
                        <div class="day-empty">
                            <i class="fa-solid fa-hourglass-half"></i>
                            <p>Próximamente más detalles</p>
                            <span>Estamos preparando la agenda de este día. ¡Mantente atento!</span>
                        </div>
                    @else
                        @foreach($day->activities as $activity)
                            @php
                                $ini = \Carbon\Carbon::parse($activity->start_time);
                                $fin = !empty($activity->end_time) ? \Carbon\Carbon::parse($activity->end_time) : null;
                                $spk = $activity->speaker;

                                /* Fondo del banner: foto del ponente; si no tiene o
                                   falla la carga → imagen por defecto de sistemas. */
                                $defaultImg = $DF_BG[$dfImg % count($DF_BG)];
                                $bgUrl      = ($spk && !empty($spk->photo_path)) ? $spk->photo_path : $defaultImg;
                                $fbUrl      = ($spk && !empty($spk->photo_path)) ? $defaultImg : $DF_BG[($dfImg + 1) % count($DF_BG)];
                                $dfImg++;
                            @endphp

                            <article class="talk-banner"
                                     data-aos="fade-up"
                                     data-aos-delay="{{ ($loop->index % 4) * 70 }}"
                                     style="--talk-img: url('{{ $bgUrl }}');"
                                     data-bg="{{ $bgUrl }}"
                                     data-fb="{{ $fbUrl }}">
                                {{-- Capas del banner: imagen → tinte del día → velo de lectura --}}
                                <span class="tb-bg" aria-hidden="true"></span>
                                <span class="tb-accent" aria-hidden="true"></span>
                                <span class="tb-shade" aria-hidden="true"></span>

                                <div class="tb-grid">
                                    <!-- Hora (estilo cartel: grande a la izquierda) -->
                                    <div class="tb-when">
                                        <span class="tb-weekday">{{ $SEMANA[(int) $schDate->format('w')] }}</span>
                                        <span class="tb-time-start">{{ $ini->format('H:i') }}</span>
                                        @if($fin)
                                            <span class="tb-time-end">— {{ $fin->format('H:i') }}</span>
                                        @endif
                                    </div>

                                    <!-- Contenido -->
                                    <div class="tb-main">
                                        <h3 class="tb-title">{{ $activity->title }}</h3>

                                        @if(!empty($activity->description))
                                            <p class="tb-desc">{{ \Illuminate\Support\Str::limit($activity->description, 140) }}</p>
                                        @endif

                                        @if(!empty($activity->speaker))
                                            <!-- Chip de ponente sobre el banner -->
                                                <div class="tb-speaker">
                                                    <span class="spk-avatar">
                                                        <span class="spk-initial">{{ strtoupper(mb_substr($activity->speaker->name ?? 'P', 0, 1)) }}</span>
                                                    </span>
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
                                </div>
                            </article>
                        @endforeach
                    @endif
                </div>
            </section>
        @empty
            <section class="day-block sch-empty-global text-center" style="--day-rgb: 147, 51, 234;">
                <i class="fa-regular fa-calendar-xmark"></i>
                <h2 class="day-title">El cronograma aún no ha sido publicado</h2>
                <p class="tb-desc mx-auto">Estamos preparando cinco días increíbles para ti. Vuelve a revisar muy pronto.</p>
            </section>
        @endforelse

        <!-- CTA final -->
        <section class="sch-cta text-center" data-aos="zoom-in">
            <h2 class="cta-title">¿Listo para no perderte nada?</h2>
            <p class="cta-sub">Asegura tu lugar en las ponencias y talleres del EXPODEV 2026.</p>
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
        --df-text:       var(--color-text, #e9e2f5);
        --df-muted:      var(--color-text-soft, #9b8fb3);
        --df-border:     rgba(147, 51, 234, .22);
    }

    #cronograma, .day-block, .talk-banner { scroll-margin-top: 96px; }

    /* ============================ HERO ============================ */
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

    /* Chips de acceso rápido por día */
    .sch-days-nav { display: flex; justify-content: center; flex-wrap: wrap; gap: .6rem; }
    .sch-chip { display: inline-flex; align-items: center; gap: .5rem; padding: .55rem 1rem; border-radius: 50rem; background: rgba(18, 11, 30, .55); border: 1px solid var(--df-border); backdrop-filter: blur(10px); transition: all .3s ease; text-decoration: none; }
    .sch-chip .chip-day { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .8rem; color: #fff; text-transform: uppercase; letter-spacing: 1px; }
    .sch-chip .chip-date { font-size: .74rem; color: var(--df-violet); font-weight: 600; letter-spacing: .5px; }
    .sch-chip:hover, .sch-chip.active { transform: translateY(-2px); border-color: rgba(192, 132, 252, .65); background: rgba(147, 51, 234, .18); box-shadow: 0 0 20px rgba(147, 51, 234, .40); }
    .sch-chip:focus-visible, .sch-btn-neon:focus-visible { outline: 2px solid var(--df-violet); outline-offset: 2px; }

    /* ========================= DÍA (sección) ======================= */
    .sch-main { padding-bottom: 3.5rem; }
    .day-block { --day-rgb: 147, 51, 234; margin-bottom: 2.6rem; }

    .day-head { display: flex; align-items: center; gap: 1.1rem; flex-wrap: wrap; padding: 1rem 1.25rem; margin-bottom: 1.1rem; border-radius: 18px; background: rgba(12, 7, 20, .55); backdrop-filter: blur(14px); border: 1px solid var(--df-border); box-shadow: 0 0 26px rgba(147, 51, 234, .10); }

    .date-block { flex: 0 0 auto; min-width: 84px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: .55rem .9rem; border-radius: 15px; background: linear-gradient(150deg, rgba(var(--day-rgb), .42), rgba(107, 33, 168, .14)); border: 1px solid rgba(var(--day-rgb), .55); box-shadow: 0 0 20px rgba(var(--day-rgb), .30); }
    .date-num { font-family: 'Outfit', sans-serif; font-size: 2.4rem; font-weight: 800; line-height: 1; color: #fff; text-shadow: 0 0 16px rgba(var(--day-rgb), .65); }
    .date-month { font-size: .76rem; font-weight: 700; letter-spacing: 3px; color: var(--df-violet); }

    .day-titles { flex: 1 1 220px; min-width: 0; }
    .day-tag { display: inline-flex; align-items: center; gap: .45rem; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: var(--df-violet); }
    .day-today { padding: .12rem .55rem; border-radius: 50rem; background: rgb(var(--day-rgb)); color: #fff; font-size: .62rem; letter-spacing: 1.5px; box-shadow: 0 0 14px rgba(var(--day-rgb), .65); }
    .day-title { font-size: 1.45rem; font-weight: 700; color: #fff; margin: .3rem 0 0; }
    .day-count { display: inline-flex; align-items: center; gap: .45rem; padding: .42rem .9rem; border-radius: 50rem; font-size: .76rem; font-weight: 600; color: rgb(var(--day-rgb)); background: rgba(var(--day-rgb), .10); border: 1px solid rgba(var(--day-rgb), .28); white-space: nowrap; }

    .day-banners { display: flex; flex-direction: column; gap: 1.05rem; }

    /* ================================================================
       BANNER DE PONENCIA — estilo cartel con imagen de fondo
       (capas: .tb-bg imagen → .tb-accent tinte del día → .tb-shade velo)
    ================================================================ */
    .talk-banner {
        position: relative;
        overflow: hidden;
        isolation: isolate;
        display: flex;
        min-height: 160px;
        border-radius: 20px;
        border: 1px solid rgba(var(--day-rgb), .40);
        background: rgba(12, 7, 20, .6);
        box-shadow: 0 10px 26px rgba(0, 0, 0, .35), 0 0 24px rgba(var(--day-rgb), .14);
        transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
    }
    .talk-banner:hover {
        transform: translateY(-4px);
        border-color: rgba(var(--day-rgb), .80);
        box-shadow: 0 16px 38px rgba(0, 0, 0, .45), 0 0 44px rgba(var(--day-rgb), .40);
    }
    /* Franja de color del día en el borde izquierdo (como el cartel) */
    .talk-banner::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 5px;
        z-index: 3;
        background: linear-gradient(180deg, rgb(var(--day-rgb)), rgba(var(--day-rgb), 0));
    }

    .tb-bg {
        position: absolute;
        inset: 0;
        background-image: var(--talk-img, none);
        background-size: cover;
        background-position: center 28%;
        transform: scale(1.01);
        transition: transform .6s ease;
    }
    .talk-banner:hover .tb-bg { transform: scale(1.07); }

    .tb-accent { position: absolute; inset: 0; background: rgb(var(--day-rgb)); opacity: .30; transition: opacity .35s ease; }
    .talk-banner:hover .tb-accent { opacity: .38; }

    .tb-shade { position: absolute; inset: 0; background: linear-gradient(100deg, rgba(5, 2, 8, .95) 0%, rgba(5, 2, 8, .80) 45%, rgba(5, 2, 8, .40) 100%); }

    .tb-grid { position: relative; z-index: 1; width: 100%; display: grid; grid-template-columns: auto minmax(0, 1fr); align-items: center; gap: 1rem 1.2rem; padding: 1.05rem 1.3rem 1.05rem 1.4rem; }

    /* Bloque de hora estilo cartel */
    .tb-when { display: flex; flex-direction: column; align-items: center; gap: .2rem; min-width: 96px; padding: .7rem .9rem; border-radius: 14px; background: rgba(5, 2, 8, .62); border: 1px solid rgba(var(--day-rgb), .50); box-shadow: 0 0 18px rgba(var(--day-rgb), .25); backdrop-filter: blur(6px); }
    .tb-weekday { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2.5px; color: rgb(var(--day-rgb)); filter: brightness(1.35); }
    .tb-time-start { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.6rem; line-height: 1; color: #fff; letter-spacing: .5px; text-shadow: 0 0 14px rgba(var(--day-rgb), .85); }
    .tb-time-end { font-size: .72rem; font-weight: 600; letter-spacing: .5px; color: var(--df-violet); white-space: nowrap; }

    .tb-main { min-width: 0; }
    .tb-title { font-family: 'Outfit', sans-serif; font-size: 1.12rem; font-weight: 700; color: #fff; margin: 0; line-height: 1.35; text-shadow: 0 2px 10px rgba(0, 0, 0, .5); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .tb-desc { color: var(--df-muted); font-size: .86rem; margin: .4rem 0 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    /* Chip de ponente sobre el banner */
    .tb-speaker { display: inline-flex; align-items: center; gap: .7rem; margin-top: .75rem; padding: .45rem .9rem .45rem .5rem; border-radius: 50rem; background: rgba(5, 2, 8, .55); border: 1px solid rgba(var(--day-rgb), .45); backdrop-filter: blur(6px); width: fit-content; max-width: 100%; transition: box-shadow .3s ease, border-color .3s ease; }
    .tb-speaker:hover { border-color: rgba(var(--day-rgb), .80); box-shadow: 0 0 18px rgba(var(--day-rgb), .35); }
    .spk-avatar { position: relative; width: 42px; height: 42px; flex: 0 0 42px; border-radius: 50%; overflow: hidden; border: 2px solid rgba(var(--day-rgb), .70); box-shadow: 0 0 12px rgba(var(--day-rgb), .50); }
    .spk-initial { position: absolute; inset: 0; display: grid; place-items: center; font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1rem; color: #fff; background: linear-gradient(135deg, var(--df-purple-600), var(--df-purple-900)); }
    .spk-photo { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .spk-info { min-width: 0; }
    .spk-label { display: block; font-size: .58rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--df-violet); opacity: .85; }
    .spk-name { display: block; color: #fff; font-weight: 600; font-size: .88rem; line-height: 1.25; }
    .spk-role { display: block; font-size: .72rem; color: var(--df-muted); }

    /* ======================= ESTADOS VACÍOS ======================== */
    .day-empty { text-align: center; padding: 2rem 1rem; border: 1px dashed rgba(var(--day-rgb), .45); border-radius: 20px; background: rgba(var(--day-rgb), .05); }
    .day-empty i { font-size: 1.7rem; color: var(--df-violet); margin-bottom: .5rem; }
    .day-empty p { color: #fff; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; margin: 0 0 .2rem; }
    .day-empty span { color: var(--df-muted); font-size: .82rem; }

    .sch-empty-global { padding: 2.6rem 1.5rem; border-radius: 20px; background: rgba(12, 7, 20, .55); backdrop-filter: blur(14px); border: 1px dashed rgba(var(--day-rgb), .45); }
    .sch-empty-global i { font-size: 2.2rem; color: var(--df-violet); margin-bottom: .6rem; }
    .sch-empty-global .day-title { margin: .2rem 0 .4rem; }

    /* ============================= CTA ============================= */
    .sch-cta { margin-top: 3rem; padding: 2.4rem 1.5rem; border-radius: 20px; background: linear-gradient(135deg, rgba(107, 33, 168, .35), rgba(147, 51, 234, .10)); border: 1px solid rgba(147, 51, 234, .35); box-shadow: 0 0 40px rgba(147, 51, 234, .22); }
    .cta-title { font-size: clamp(1.5rem, 3.4vw, 2.1rem); font-weight: 800; color: #fff; margin-bottom: .4rem; }
    .cta-sub { color: var(--df-muted); margin-bottom: 1.3rem; }
    .sch-btn-neon { display: inline-flex; align-items: center; padding: .8rem 1.9rem; border-radius: 14px; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .95rem; color: #fff; background: linear-gradient(135deg, var(--df-purple-600), var(--df-purple-900)); border: 1px solid rgba(192, 132, 252, .6); box-shadow: 0 0 22px rgba(147, 51, 234, .5); transition: all .3s ease; }
    .sch-btn-neon:hover { transform: translateY(-3px) scale(1.02); color: #fff; box-shadow: 0 0 38px rgba(147, 51, 234, .75); }

    /* ==========================================================
       RESPONSIVE — Mobile first friendly
    ========================================================== */
    @media (max-width: 991.98px) {
        .sch-hero { padding: 7rem 0 2.6rem; }
        .tb-time-start { font-size: 1.4rem; }
        .tb-when { min-width: 88px; }
        .tb-grid { padding: 1rem 1.15rem; column-gap: 1rem; }
    }

    @media (max-width: 767.98px) {
        .sch-hero { padding: 6.2rem 0 2.2rem; }
        .sch-sub { font-size: .93rem; }

        .day-head { padding: .9rem 1rem; gap: .85rem; border-radius: 16px; margin-bottom: .95rem; }
        .date-block { min-width: 70px; padding: .45rem .7rem; border-radius: 13px; }
        .date-num { font-size: 2rem; }
        .day-title { font-size: 1.22rem; }
        .day-banners { gap: .85rem; }

        /* Banner apilado: la hora pasa ARRIBA, todo en una columna */
        .talk-banner { min-height: 0; border-radius: 18px; }
        .tb-grid { grid-template-columns: 1fr; gap: .65rem; padding: 1rem 1.05rem 1.1rem 1.2rem; }
        .tb-when { flex-direction: row; align-items: baseline; gap: .55rem; min-width: 0; width: fit-content; padding: .38rem .8rem; border-radius: 50rem; }
        .tb-time-start { font-size: 1.22rem; }
        .tb-weekday { letter-spacing: 2px; }

        /* Velo más sólido para que el texto siempre se lea bien */
        .tb-shade { background: linear-gradient(180deg, rgba(5, 2, 8, .87) 0%, rgba(5, 2, 8, .93) 100%); }
        .tb-accent { opacity: .22; }

        .tb-title { font-size: 1rem; }
        .tb-desc { font-size: .82rem; -webkit-line-clamp: 3; }
        .tb-speaker { margin-top: .55rem; }
    }

    @media (max-width: 575.98px) {
        .sch-days-nav { flex-wrap: nowrap; justify-content: flex-start; overflow-x: auto; padding-bottom: .5rem; scrollbar-width: none; }
        .sch-days-nav::-webkit-scrollbar { display: none; }
        .spk-avatar { width: 38px; height: 38px; flex-basis: 38px; }
        .spk-name { font-size: .84rem; }
        .talk-banner::before { width: 4px; }
    }

    /* Accesibilidad: menos movimiento */
    @media (prefers-reduced-motion: reduce) {
        .sch-orb, .tb-bg, .tb-accent, .talk-banner, .tb-speaker, .sch-chip, .sch-btn-neon { animation: none !important; transition: none !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.AOS && typeof AOS.refreshHard === 'function') { AOS.refreshHard(); }

        /* ---------------------------------------------------------
         | 1) FONDO DE CADA PONENCIA con respaldo automático:
         |    se intenta cargar la imagen asignada (foto del ponente
         |    o imagen por defecto); si la carga FALLA, se cambia por
         |    la imagen de respaldo para que el banner nunca quede mal.
         --------------------------------------------------------- */
        document.querySelectorAll('.talk-banner[data-bg]').forEach(function (banner) {
            var bg = banner.getAttribute('data-bg');
            var fb = banner.getAttribute('data-fb');
            if (!bg || !fb || bg === fb) { return; }

            var probe = new Image();
            probe.onerror = function () {
                banner.style.setProperty('--talk-img', 'url("' + fb + '")');
            };
            probe.src = bg;
        });

        /* ---------------------------------------------------------
         | 2) SCROLLSPY: marca con .active el chip del día que está
         |    visible en pantalla mientras el usuario hace scroll.
         --------------------------------------------------------- */
        var chips  = Array.prototype.slice.call(document.querySelectorAll('.sch-chip'));
        var blocks = Array.prototype.slice.call(document.querySelectorAll('.day-block'));
        if ('IntersectionObserver' in window && chips.length && blocks.length) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) { return; }
                    var id = '#' + entry.target.id;
                    chips.forEach(function (chip) {
                        chip.classList.toggle('active', chip.getAttribute('href') === id);
                    });
                });
            }, { rootMargin: '-35% 0px -55% 0px' });
            blocks.forEach(function (b) { io.observe(b); });
        }
    });
</script>
@endpush