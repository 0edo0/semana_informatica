@extends('layouts.admin')

@section('title', 'Control de Asistencias')

@section('content')
@php
    // Un solo query para obtener el registro de asistencia de cada estudiante de la página
    // (se usa para poder "Quitar" la asistencia sin consultas repetidas por fila).
    $attendanceMap = $selectedDay
        ? \App\Models\Attendance::whereIn('student_id', $students->pluck('id'))
            ->whereDate('registered_at', $selectedDay->date)
            ->get()
            ->keyBy('student_id')
        : collect();

    $dayTotal   = is_countable($attendedStudentIds ?? null) ? count($attendedStudentIds) : 0;
    $pageTotal  = $students->count();
    $pageAttend = $students->filter(fn ($s) => in_array($s->id, $attendedStudentIds ?? []))->count();
@endphp

<div class="attendance-page">

    {{-- ===================== ENCABEZADO ===================== --}}
    <div class="at-header">
        <div>
            <h2 class="at-title">
                <i class="fa-solid fa-clipboard-user" aria-hidden="true"></i> Asistencias
            </h2>
            <p class="at-subtitle">Registra el ingreso diario de los estudiantes.</p>
        </div>

        @if ($selectedDay)
            <a href="{{ route('admin.attendances.export', ['day_id' => $selectedDay->id]) }}" class="btn at-btn-export">
                <i class="fa-solid fa-file-excel me-2" aria-hidden="true"></i> Exportar asistencia
            </a>
        @endif
    </div>

    <div class="glass-panel at-panel">

        {{-- ===================== SIN DÍAS CONFIGURADOS ===================== --}}
        @if ($scheduleDays->isEmpty() || !$selectedDay)
            <div class="at-empty">
                <i class="fa-regular fa-calendar-xmark" aria-hidden="true"></i>
                <p>No hay días del evento disponibles.</p>
                <span>Crea los días en el cronograma para empezar a tomar asistencia.</span>
            </div>
        @else

        {{-- ===================== SELECTOR DE DÍA + FILTROS ===================== --}}
        <form method="GET" action="{{ route('admin.attendances.index') }}" class="at-filters" id="filterForm">
            <div class="at-field at-field-day">
                <label for="f-day" class="at-label">Día del evento</label>
                <div class="at-input-icon">
                    <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                    <select id="f-day" name="day_id" class="form-select at-input">
                        @foreach ($scheduleDays as $day)
                            <option value="{{ $day->id }}" {{ $selectedDay && $selectedDay->id == $day->id ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }} — {{ $day->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="at-field at-field-search">
                <label for="f-search" class="at-label">Buscar estudiante</label>
                <div class="at-input-icon">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" id="f-search" name="search" class="form-control at-input"
                           placeholder="DNI o nombre" value="{{ request('search') }}" autocomplete="off" enterkeyhint="search">
                </div>
            </div>

            <div class="at-field at-field-semester">
                <label for="f-semester" class="at-label">Semestre</label>
                <select id="f-semester" name="semester" class="form-select at-input">
                    <option value="">Todos</option>
                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>2.º semestre</option>
                    <option value="4" {{ request('semester') == '4' ? 'selected' : '' }}>4.º semestre</option>
                    <option value="6" {{ request('semester') == '6' ? 'selected' : '' }}>6.º semestre</option>
                </select>
            </div>

            <div class="at-filter-actions">
                <button type="submit" class="btn at-btn-filter">
                    <i class="fa-solid fa-filter me-2" aria-hidden="true"></i> Filtrar
                </button>
                <a href="{{ route('admin.attendances.index', ['day_id' => $selectedDay->id ?? '']) }}" class="btn at-btn-clear">
                    <i class="fa-solid fa-rotate-left me-2" aria-hidden="true"></i> Limpiar
                </a>
            </div>
        </form>

        {{-- ===================== RESUMEN DEL DÍA ===================== --}}
        <section class="at-stats" aria-label="Resumen del día">
            <div class="at-stat at-stat-day">
                <span class="at-stat-icon"><i class="fa-solid fa-calendar-day" aria-hidden="true"></i></span>
                <div>
                    <div class="at-stat-value"><span id="dayTotal" data-value="{{ $dayTotal }}">{{ $dayTotal }}</span></div>
                    <div class="at-stat-label">Asistencias del día</div>
                </div>
            </div>
            <div class="at-stat">
                <span class="at-stat-icon at-ok"><i class="fa-solid fa-user-check" aria-hidden="true"></i></span>
                <div>
                    <div class="at-stat-value"><span id="pagePresent">{{ $pageAttend }}</span> <small>/ <span id="pageTotal">{{ $pageTotal }}</span></small></div>
                    <div class="at-stat-label">Presentes en esta lista</div>
                </div>
            </div>
            <div class="at-progress" role="progressbar" aria-label="Avance de asistencia en esta lista"
                 aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $pageTotal ? round($pageAttend / $pageTotal * 100) : 0 }}" id="pageProgress">
                <div class="at-progress-bar" id="pageProgressBar" style="width: {{ $pageTotal ? round($pageAttend / $pageTotal * 100) : 0 }}%"></div>
            </div>
        </section>

        {{-- ===================== FILTRO RÁPIDO POR ESTADO (en esta lista) ===================== --}}
        <div class="at-segment" role="group" aria-label="Filtrar por estado">
            <button type="button" class="at-seg is-active" data-filter="all">Todos</button>
            <button type="button" class="at-seg" data-filter="absent"><i class="fa-solid fa-xmark me-1" aria-hidden="true"></i> Ausentes</button>
            <button type="button" class="at-seg" data-filter="present"><i class="fa-solid fa-check me-1" aria-hidden="true"></i> Asistieron</button>
        </div>

        {{-- ===================== TABLA (tablet / escritorio) ===================== --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-dark align-middle mb-0 at-table">
                <thead>
                    <tr>
                        <th scope="col">Estudiante</th>
                        <th scope="col">DNI</th>
                        <th scope="col">Semestre</th>
                        <th scope="col">Estado</th>
                        <th scope="col" class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        @php
                            $isAttended = in_array($student->id, $attendedStudentIds ?? []);
                            $record     = $attendanceMap->get($student->id);
                        @endphp
                        <tr class="js-student"
                            data-student-id="{{ $student->id }}"
                            data-name="{{ $student->first_name }} {{ $student->last_name }}"
                            data-status="{{ $isAttended ? 'present' : 'absent' }}"
                            data-destroy-url="{{ $isAttended && $record ? route('admin.attendances.destroy', $record->id) : '' }}">
                            <td>
                                <div class="at-person">
                                    <div class="avatar-circle" aria-hidden="true">
                                        {{ mb_strtoupper(mb_substr($student->first_name, 0, 1)) }}{{ mb_strtoupper(mb_substr($student->last_name, 0, 1)) }}
                                    </div>
                                    <div class="at-name">{{ $student->first_name }} {{ $student->last_name }}</div>
                                </div>
                            </td>
                            <td class="at-dni">{{ $student->dni }}</td>
                            <td><span class="badge bg-purple-soft">{{ $student->semester }}.º</span></td>
                            <td>
                                <span class="at-badge at-badge-ok"><i class="fa-solid fa-check me-1" aria-hidden="true"></i> Asistió</span>
                                <span class="at-badge at-badge-no"><i class="fa-solid fa-xmark me-1" aria-hidden="true"></i> Ausente</span>
                            </td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn at-act at-act-register" data-action="register">
                                    <i class="fa-solid fa-check me-1" aria-hidden="true"></i> Registrar
                                </button>
                                <button type="button" class="btn at-act at-act-remove" data-action="remove">
                                    <i class="fa-solid fa-rotate-left me-1" aria-hidden="true"></i> Quitar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="at-empty">
                                    <i class="fa-regular fa-face-frown-open" aria-hidden="true"></i>
                                    <p>No se encontraron estudiantes.</p>
                                    <span>Prueba con otro DNI, nombre o semestre.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===================== LISTA TÁCTIL (móvil) ===================== --}}
        <div class="at-list d-md-none">
            @forelse ($students as $student)
                @php
                    $isAttended = in_array($student->id, $attendedStudentIds ?? []);
                    $record     = $attendanceMap->get($student->id);
                @endphp
                <article class="at-item js-student"
                         data-student-id="{{ $student->id }}"
                         data-name="{{ $student->first_name }} {{ $student->last_name }}"
                         data-status="{{ $isAttended ? 'present' : 'absent' }}"
                         data-destroy-url="{{ $isAttended && $record ? route('admin.attendances.destroy', $record->id) : '' }}">
                    <div class="at-person">
                        <div class="avatar-circle" aria-hidden="true">
                            {{ mb_strtoupper(mb_substr($student->first_name, 0, 1)) }}{{ mb_strtoupper(mb_substr($student->last_name, 0, 1)) }}
                        </div>
                        <div class="at-person-info">
                            <div class="at-name">{{ $student->first_name }} {{ $student->last_name }}</div>
                            <div class="at-meta">
                                <span><i class="fa-regular fa-id-card" aria-hidden="true"></i> {{ $student->dni }}</span>
                                <span class="badge bg-purple-soft">{{ $student->semester }}.º</span>
                            </div>
                        </div>
                    </div>

                    <div class="at-item-foot">
                        <span class="at-badge at-badge-ok"><i class="fa-solid fa-check me-1" aria-hidden="true"></i> Asistió</span>
                        <span class="at-badge at-badge-no"><i class="fa-solid fa-xmark me-1" aria-hidden="true"></i> Ausente</span>

                        <button type="button" class="btn at-act at-act-register" data-action="register">
                            <i class="fa-solid fa-check me-1" aria-hidden="true"></i> Registrar
                        </button>
                        <button type="button" class="btn at-act at-act-remove" data-action="remove">
                            <i class="fa-solid fa-rotate-left me-1" aria-hidden="true"></i> Quitar
                        </button>
                    </div>
                </article>
            @empty
                <div class="at-empty">
                    <i class="fa-regular fa-face-frown-open" aria-hidden="true"></i>
                    <p>No se encontraron estudiantes.</p>
                    <span>Prueba con otro DNI, nombre o semestre.</span>
                </div>
            @endforelse
        </div>

        {{-- Mensaje cuando el filtro rápido no muestra nada --}}
        <div class="at-empty d-none" id="filterEmpty">
            <i class="fa-regular fa-circle-check" aria-hidden="true"></i>
            <p>No hay estudiantes con este estado en la lista.</p>
            <span>Cambia el filtro para ver al resto.</span>
        </div>

        {{-- ===================== PAGINACIÓN ===================== --}}
        <div class="at-pagination">
            @if (method_exists($students, 'total') && $students->total() > 0)
                <small class="at-showing">Mostrando {{ $students->firstItem() }}–{{ $students->lastItem() }} de {{ $students->total() }}</small>
            @endif
            <div class="at-pagination-links">
                {{ $students->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>

        @endif
    </div>
</div>

{{-- ==========================================================
     MODAL ÚNICO: confirmar "Quitar asistencia"
========================================================== --}}
<div class="modal fade at-modal" id="removeModal" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered at-modal-sm">
        <div class="modal-content at-modal-content">
            <form id="removeForm" action="#" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body at-confirm">
                    <div class="at-confirm-icon" aria-hidden="true"><i class="fa-solid fa-rotate-left"></i></div>
                    <h5 id="removeModalLabel">¿Quitar asistencia?</h5>
                    <p><strong id="r-name"></strong> volverá a figurar como <em>ausente</em> en este día.</p>
                    <div class="at-confirm-actions">
                        <button type="button" class="btn at-btn-clear" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn at-btn-danger" id="removeSubmit">
                            <i class="fa-solid fa-rotate-left me-2" aria-hidden="true"></i> Sí, quitar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Avisos (toast) accesibles --}}
<div class="at-toasts" id="toasts" aria-live="polite" aria-atomic="false"></div>
@endsection

@push('styles')
<style>
    /* ==========================================================
       ASISTENCIAS — estilos de la vista (móvil primero)
    ========================================================== */
    :root {
        --at-text:   #f5f1fd;
        --at-soft:   #cfc4e8;
        --at-line:   rgba(192, 132, 252, .22);
        --at-field:  rgba(24, 15, 40, .85);
        --at-violet: var(--adm-violet, #c084fc);
        --at-p600:   var(--adm-purple-600, #9333ea);
        --at-p900:   var(--adm-purple-900, #6b21a8);
        --at-ok:     #34d399;
        --at-ok-bg:  rgba(16, 185, 129, .16);
        --at-no:     #fda4af;
        --at-no-bg:  rgba(244, 63, 94, .14);
    }

    .attendance-page .text-muted { color: var(--at-soft) !important; }

    /* ---------- Encabezado ---------- */
    .at-header {
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; flex-wrap: wrap; margin-bottom: 1.4rem;
    }
    .at-title {
        margin: 0 0 .2rem; color: #fff;
        font-family: 'Outfit', sans-serif; font-weight: 800;
        font-size: clamp(1.45rem, 4vw, 1.9rem);
        display: flex; align-items: center; gap: .6rem;
    }
    .at-title i { color: var(--at-violet); }
    .at-subtitle { margin: 0; color: var(--at-soft); font-size: .92rem; }

    /* ---------- Botones generales ---------- */
    .at-btn-export {
        background: linear-gradient(135deg, #059669, #047857);
        border: 1px solid #10b981; color: #fff;
        border-radius: 10px; font-weight: 600; padding: .6rem 1.2rem;
        box-shadow: 0 0 15px rgba(16, 185, 129, .25);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .at-btn-export:hover, .at-btn-export:focus-visible {
        color: #fff; transform: translateY(-2px);
        box-shadow: 0 0 25px rgba(16, 185, 129, .5);
    }
    .at-btn-filter {
        background: rgba(147, 51, 234, .18); border: 1px solid var(--at-p600);
        color: #fff; border-radius: 10px; font-weight: 600;
    }
    .at-btn-filter:hover { background: var(--at-p600); color: #fff; }
    .at-btn-clear {
        background: rgba(255, 255, 255, .05); border: 1px solid rgba(255, 255, 255, .22);
        color: var(--at-text); border-radius: 10px; font-weight: 500;
    }
    .at-btn-clear:hover { background: rgba(255, 255, 255, .12); color: #fff; }
    .at-btn-danger {
        background: linear-gradient(135deg, #f43f5e, #be123c);
        border: 1px solid rgba(253, 164, 175, .5); color: #fff;
        border-radius: 10px; font-weight: 600;
    }
    .at-btn-danger:hover { color: #fff; box-shadow: 0 0 22px rgba(244, 63, 94, .5); }
    .at-btn-danger:disabled { opacity: .7; }

    .btn:focus-visible, .at-input:focus-visible, .at-seg:focus-visible {
        outline: 2px solid var(--at-violet); outline-offset: 2px;
    }

    .at-panel { padding: 1.4rem; }

    /* ---------- Campos ---------- */
    .at-label {
        display: block; margin-bottom: .4rem; color: var(--at-soft);
        font-size: .82rem; font-weight: 600;
    }
    .at-input, .form-select.at-input {
        background-color: var(--at-field); border: 1px solid var(--at-line);
        color: #fff; border-radius: 10px; min-height: 46px; font-size: 1rem;
    }
    .at-input::placeholder { color: #8f82b0; opacity: 1; }
    .at-input:focus {
        background-color: rgba(30, 19, 50, .95); border-color: var(--at-violet);
        box-shadow: 0 0 0 .22rem rgba(147, 51, 234, .3); color: #fff;
    }
    .form-select.at-input {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23c084fc' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    }
    .at-input option { background: #150d24; color: #fff; }
    .at-input-icon { position: relative; }
    .at-input-icon > i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--at-violet); pointer-events: none; z-index: 2;
    }
    .at-input-icon .at-input { padding-left: 2.6rem; }

    /* ---------- Filtros ---------- */
    .at-filters {
        display: grid; grid-template-columns: 1fr; gap: 1rem; align-items: end;
        padding: 1rem; border-radius: 14px;
        background: rgba(147, 51, 234, .06); border: 1px solid var(--at-line);
    }
    .at-filter-actions { display: grid; grid-template-columns: 1fr 1fr; gap: .6rem; }
    .at-filter-actions .btn { min-height: 46px; display: inline-flex; align-items: center; justify-content: center; }
    @media (min-width: 768px) {
        .at-filters { grid-template-columns: repeat(6, minmax(0, 1fr)); }
        .at-field-day      { grid-column: span 3; }
        .at-field-search   { grid-column: span 3; }
        .at-field-semester { grid-column: span 2; }
        .at-filter-actions { grid-column: span 4; grid-template-columns: auto auto; justify-content: end; }
    }

    /* ---------- Resumen ---------- */
    .at-stats {
        display: grid; grid-template-columns: 1fr 1fr; gap: .8rem;
        margin: 1.1rem 0 .9rem;
    }
    .at-stat {
        display: flex; align-items: center; gap: .8rem;
        padding: .8rem .9rem; border-radius: 14px;
        background: rgba(147, 51, 234, .08); border: 1px solid var(--at-line);
    }
    .at-stat-icon {
        flex: 0 0 auto; width: 40px; height: 40px; display: grid; place-items: center;
        border-radius: 12px; color: #e9d5ff; background: rgba(147, 51, 234, .3);
    }
    .at-stat-icon.at-ok { color: #a7f3d0; background: var(--at-ok-bg); }
    .at-stat-value { color: #fff; font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.45rem; line-height: 1.1; }
    .at-stat-value small { color: var(--at-soft); font-size: .9rem; font-weight: 600; }
    .at-stat-label { color: var(--at-soft); font-size: .76rem; line-height: 1.25; }
    .at-progress {
        grid-column: 1 / -1; height: 8px; border-radius: 50rem; overflow: hidden;
        background: rgba(255, 255, 255, .08);
    }
    .at-progress-bar {
        height: 100%; border-radius: 50rem;
        background: linear-gradient(90deg, #10b981, #34d399);
        transition: width .4s ease;
    }

    /* ---------- Filtro rápido por estado ---------- */
    .at-segment {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: .35rem;
        padding: .3rem; margin-bottom: 1rem; border-radius: 12px;
        background: rgba(255, 255, 255, .05); border: 1px solid var(--at-line);
    }
    .at-seg {
        min-height: 42px; border: 0; border-radius: 9px; background: transparent;
        color: var(--at-soft); font-weight: 600; font-size: .88rem; padding: .3rem .4rem;
        transition: background .2s ease, color .2s ease;
    }
    .at-seg.is-active { background: var(--at-p600); color: #fff; box-shadow: 0 0 14px rgba(147, 51, 234, .4); }
    @media (min-width: 768px) {
        .at-segment { display: inline-grid; grid-template-columns: repeat(3, auto); }
        .at-seg { padding: .3rem 1.2rem; }
    }

    /* ---------- Estados (se muestran según data-status) ---------- */
    .at-badge {
        display: none; align-items: center; padding: .38em .8em;
        border-radius: 50rem; font-size: .8rem; font-weight: 700; white-space: nowrap;
    }
    .at-badge-ok { color: #a7f3d0; background: var(--at-ok-bg); border: 1px solid rgba(52, 211, 153, .5); }
    .at-badge-no { color: var(--at-no); background: var(--at-no-bg); border: 1px solid rgba(244, 63, 94, .45); }

    .js-student[data-status="present"] .at-badge-ok,
    .js-student[data-status="absent"]  .at-badge-no { display: inline-flex; }

    .js-student[data-status="present"] .at-act-register,
    .js-student[data-status="absent"]  .at-act-remove { display: none; }

    /* ---------- Botones de acción ---------- */
    .at-act {
        min-height: 46px; min-width: 124px; padding: .45rem 1rem;
        border-radius: 10px; font-weight: 700; font-size: .95rem;
        display: inline-flex; align-items: center; justify-content: center;
        transition: background .2s ease, transform .1s ease;
    }
    .at-act:active { transform: scale(.97); }
    .at-act-register {
        background: linear-gradient(135deg, #059669, #047857);
        border: 1px solid #10b981; color: #fff;
        box-shadow: 0 0 14px rgba(16, 185, 129, .25);
    }
    .at-act-register:hover, .at-act-register:focus-visible { color: #fff; background: linear-gradient(135deg, #10b981, #059669); }
    .at-act-remove {
        background: rgba(244, 63, 94, .1); border: 1px solid rgba(244, 63, 94, .4); color: #fecdd3;
    }
    .at-act-remove:hover { background: rgba(244, 63, 94, .45); color: #fff; }
    .at-act:disabled { opacity: .65; }
    .at-table .at-act { min-height: 38px; min-width: 112px; font-size: .88rem; }

    /* ---------- Tabla ---------- */
    .at-table {
        --bs-table-bg: transparent; --bs-table-color: var(--at-text);
        --bs-table-hover-bg: rgba(147, 51, 234, .1); --bs-table-hover-color: #fff;
        --bs-table-border-color: var(--at-line); background: transparent;
    }
    .at-table thead th {
        color: var(--at-violet); font-size: .8rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .6px;
        padding: .8rem .75rem; border-bottom: 1px solid var(--at-line); white-space: nowrap;
    }
    .at-table tbody td { padding: .75rem; }
    .at-dni { color: #fff; font-variant-numeric: tabular-nums; }
    .at-table tbody tr[data-status="present"] { box-shadow: inset 3px 0 0 #10b981; }

    /* ---------- Persona ---------- */
    .at-person { display: flex; align-items: center; gap: .85rem; min-width: 0; }
    .at-person-info { min-width: 0; }
    .at-name { color: #fff; font-weight: 700; line-height: 1.25; overflow-wrap: anywhere; }
    .avatar-circle {
        flex: 0 0 auto; width: 42px; height: 42px; border-radius: 50%;
        background: linear-gradient(135deg, var(--at-p600), var(--at-p900));
        display: grid; place-items: center; font-weight: 700; color: #fff;
        font-family: 'Outfit', sans-serif; border: 2px solid rgba(192, 132, 252, .45);
    }
    .bg-purple-soft {
        background: rgba(147, 51, 234, .25); color: #ead7ff;
        border: 1px solid rgba(192, 132, 252, .4); padding: .4em .8em; font-weight: 600;
    }

    /* ---------- Lista táctil móvil ---------- */
    .at-list { display: grid; gap: .75rem; }
    .at-item {
        padding: .95rem; border-radius: 14px;
        background: rgba(147, 51, 234, .07); border: 1px solid var(--at-line);
        border-left: 4px solid rgba(244, 63, 94, .55);
        transition: border-color .25s ease, background .25s ease;
    }
    .at-item[data-status="present"] { border-left-color: #10b981; background: rgba(16, 185, 129, .07); }
    .at-meta { display: flex; flex-wrap: wrap; align-items: center; gap: .3rem .8rem; margin-top: .3rem; color: var(--at-soft); font-size: .86rem; }
    .at-meta i { color: var(--at-violet); margin-right: .3rem; }
    .at-item-foot {
        display: flex; align-items: center; justify-content: space-between;
        gap: .8rem; margin-top: .85rem;
    }
    .at-item-foot .at-act { flex: 1 1 auto; max-width: 190px; margin-left: auto; }

    /* ---------- Vacío ---------- */
    .at-empty { text-align: center; padding: 2.5rem 1rem; color: var(--at-soft); }
    .at-empty i { font-size: 2.4rem; color: var(--at-violet); margin-bottom: .8rem; }
    .at-empty p { color: #fff; font-weight: 600; margin: 0 0 .2rem; }
    .at-empty span { font-size: .88rem; }

    /* ---------- Paginación ---------- */
    .at-pagination {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: .8rem; margin-top: 1.4rem;
    }
    .at-showing { color: var(--at-soft); }
    .at-pagination-links { max-width: 100%; overflow-x: auto; }
    .at-pagination-links nav > div:first-child { display: none; }
    .at-pagination-links .pagination { margin: 0; flex-wrap: wrap; gap: 2px; }
    .pagination .page-link {
        background: rgba(24, 15, 40, .85); border-color: var(--at-line);
        color: #e9d5ff; min-width: 40px; text-align: center;
    }
    .pagination .page-item.active .page-link { background: var(--at-p600); border-color: var(--at-p600); color: #fff; }
    .pagination .page-item.disabled .page-link { background: rgba(24, 15, 40, .5); color: #8f82b0; border-color: var(--at-line); }
    .pagination .page-link:hover { background: rgba(147, 51, 234, .35); color: #fff; }

    @media (max-width: 575.98px) {
        .at-panel { padding: 1rem .85rem; }
        .at-btn-export { width: 100%; min-height: 46px; text-align: center; }
        .at-pagination { justify-content: center; }
        .at-showing { width: 100%; text-align: center; }
    }

    /* ---------- Modal (sin backdrop-filter para que sea fluido) ---------- */
    .at-modal .at-modal-content {
        background: #0e0818; border: 1px solid var(--at-line); border-radius: 16px;
        color: var(--at-text);
        box-shadow: 0 25px 60px rgba(0, 0, 0, .6), 0 0 40px rgba(147, 51, 234, .15);
    }
    .at-modal-sm { max-width: 400px; }
    .at-confirm { text-align: center; padding: 2rem 1.5rem 1.5rem !important; }
    .at-confirm-icon {
        width: 68px; height: 68px; margin: 0 auto 1rem; display: grid; place-items: center;
        border-radius: 50%; font-size: 1.6rem; color: #fda4af;
        background: rgba(244, 63, 94, .15); border: 1px solid rgba(244, 63, 94, .45);
        box-shadow: 0 0 28px rgba(244, 63, 94, .3);
    }
    .at-confirm h5 { color: #fff; font-family: 'Outfit', sans-serif; font-weight: 700; margin-bottom: .6rem; }
    .at-confirm p { color: var(--at-soft); margin-bottom: 1.4rem; line-height: 1.6; }
    .at-confirm p strong { color: #fff; }
    .at-confirm-actions { display: flex; gap: .6rem; }
    .at-confirm-actions .btn { flex: 1; min-height: 46px; }
    @media (max-width: 420px) { .at-confirm-actions { flex-direction: column-reverse; } }
    .modal-backdrop.show { opacity: .72; }

    /* ---------- Avisos (toast) ---------- */
    .at-toasts {
        position: fixed; left: 50%; bottom: calc(1rem + env(safe-area-inset-bottom, 0px));
        transform: translateX(-50%); z-index: 2000;
        display: grid; gap: .5rem; width: min(92vw, 420px); pointer-events: none;
    }
    .at-toast {
        display: flex; align-items: center; gap: .7rem;
        padding: .8rem 1rem; border-radius: 12px; color: #fff; font-weight: 600; font-size: .92rem;
        background: #12301f; border: 1px solid #10b981;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .5);
        animation: atToastIn .25s ease-out;
    }
    .at-toast.is-error { background: #3a1220; border-color: #f43f5e; }
    .at-toast i { font-size: 1.1rem; }
    .at-toast.is-leaving { opacity: 0; transform: translateY(8px); transition: all .25s ease; }
    @keyframes atToastIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

    .is-hidden-by-filter { display: none !important; }

    @media (prefers-reduced-motion: reduce) {
        .at-toast, .at-progress-bar, .at-item, .btn { animation: none !important; transition: none !important; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var ROUTE_STORE = @json(route('admin.attendances.store'));
    var DESTROY_TPL = @json(route('admin.attendances.destroy', '__ID__'));
    var CSRF        = @json(csrf_token());
    var DAY_ID      = @json($selectedDay->id ?? null);

    var $ = function (id) { return document.getElementById(id); };

    /* ---------- Restaurar posición del scroll tras recargar ---------- */
    try {
        var saved = sessionStorage.getItem('att-scroll');
        if (saved !== null) {
            sessionStorage.removeItem('att-scroll');
            window.scrollTo(0, parseInt(saved, 10) || 0);
        }
    } catch (e) {}

    function rememberScroll() {
        try { sessionStorage.setItem('att-scroll', String(window.scrollY)); } catch (e) {}
    }

    /* ---------- Aviso (toast) ---------- */
    function toast(message, type) {
        var box = $('toasts');
        if (!box) { return; }
        var el = document.createElement('div');
        el.className = 'at-toast' + (type === 'error' ? ' is-error' : '');
        el.setAttribute('role', type === 'error' ? 'alert' : 'status');

        var icon = document.createElement('i');
        icon.className = type === 'error' ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check';
        icon.setAttribute('aria-hidden', 'true');
        var text = document.createElement('span');
        text.textContent = message;

        el.appendChild(icon);
        el.appendChild(text);
        box.appendChild(el);

        setTimeout(function () {
            el.classList.add('is-leaving');
            setTimeout(function () { el.remove(); }, 300);
        }, 2600);
    }

    function vibrate(ms) {
        if (navigator.vibrate) { try { navigator.vibrate(ms); } catch (e) {} }
    }

    /* ---------- Contadores y progreso ---------- */
    function refreshCounters() {
        var rows = document.querySelectorAll('.at-table tbody tr.js-student');
        if (!rows.length) { rows = document.querySelectorAll('.at-list .js-student'); }
        var total = rows.length, present = 0;
        rows.forEach(function (r) { if (r.dataset.status === 'present') { present++; } });

        var pp = $('pagePresent'), pt = $('pageTotal'), bar = $('pageProgressBar'), prog = $('pageProgress');
        if (pp) { pp.textContent = present; }
        if (pt) { pt.textContent = total; }
        var pct = total ? Math.round(present / total * 100) : 0;
        if (bar)  { bar.style.width = pct + '%'; }
        if (prog) { prog.setAttribute('aria-valuenow', pct); }
    }

    function bumpDayTotal(delta) {
        var el = $('dayTotal');
        if (!el) { return; }
        var v = Math.max(0, (parseInt(el.textContent, 10) || 0) + delta);
        el.textContent = v;
    }

    /* ---------- Filtro rápido por estado (solo sobre la lista visible) ---------- */
    var currentFilter = 'all';

    function applyFilter() {
        var visible = 0;
        document.querySelectorAll('.js-student').forEach(function (item) {
            var show = currentFilter === 'all' || item.dataset.status === currentFilter;
            item.classList.toggle('is-hidden-by-filter', !show);
        });

        // Cuenta solo la vista activa (tabla o lista móvil) para el mensaje vacío
        var table = document.querySelector('.at-table');
        var useTable = table && table.offsetParent !== null;
        var scope = useTable ? '.at-table tbody .js-student' : '.at-list .js-student';
        document.querySelectorAll(scope).forEach(function (i) {
            if (!i.classList.contains('is-hidden-by-filter')) { visible++; }
        });

        var empty = $('filterEmpty');
        if (empty) {
            var hasRows = document.querySelectorAll(scope).length > 0;
            empty.classList.toggle('d-none', !(hasRows && visible === 0));
        }
    }

    document.querySelectorAll('.at-seg').forEach(function (btn) {
        btn.addEventListener('click', function () {
            currentFilter = btn.dataset.filter;
            document.querySelectorAll('.at-seg').forEach(function (b) {
                b.classList.toggle('is-active', b === btn);
            });
            applyFilter();
        });
    });
    window.addEventListener('resize', applyFilter);

    /* ---------- Registrar asistencia (AJAX, sin recargar) ---------- */
    function setLoading(items, loading) {
        items.forEach(function (item) {
            var b = item.querySelector('[data-action="register"]');
            if (!b) { return; }
            if (loading) {
                b.dataset.html = b.innerHTML;
                b.disabled = true;
                b.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Guardando…';
            } else {
                if (b.dataset.html) { b.innerHTML = b.dataset.html; }
                b.disabled = false;
            }
        });
    }

    async function registerAttendance(studentId) {
        if (!DAY_ID) {
            toast('Selecciona un día primero.', 'error');
            return;
        }

        var items = Array.prototype.slice.call(document.querySelectorAll('.js-student[data-student-id="' + studentId + '"]'));
        if (!items.length || items[0].dataset.busy) { return; }
        items.forEach(function (i) { i.dataset.busy = '1'; });
        setLoading(items, true);

        try {
            var res = await fetch(ROUTE_STORE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({ student_id: studentId, day_id: DAY_ID })
            });

            var data = {};
            try { data = await res.json(); } catch (e) {}

            if (!res.ok || data.error) {
                setLoading(items, false);
                toast(data.error || data.message || 'No se pudo registrar la asistencia.', 'error');
                vibrate([60, 40, 60]);
                return;
            }

            // Intenta obtener el ID del registro creado para poder "Quitar" sin recargar
            var newId = data.id || data.attendance_id ||
                        (data.attendance && data.attendance.id) ||
                        (data.data && data.data.id) || null;

            setLoading(items, false);
            items.forEach(function (item) {
                item.dataset.status = 'present';
                if (newId) { item.dataset.destroyUrl = DESTROY_TPL.replace('__ID__', newId); }
            });

            bumpDayTotal(1);
            refreshCounters();
            applyFilter();
            toast('Asistencia registrada: ' + items[0].dataset.name);
            vibrate(40);

            // Si el servidor no devuelve el ID, se recarga conservando la posición
            if (!newId) {
                rememberScroll();
                setTimeout(function () { window.location.reload(); }, 700);
            }
        } catch (err) {
            console.error(err);
            setLoading(items, false);
            toast('Sin conexión o error del servidor. Intenta de nuevo.', 'error');
            vibrate([60, 40, 60]);
        } finally {
            items.forEach(function (i) { delete i.dataset.busy; });
        }
    }

    /* ---------- Quitar asistencia (modal de confirmación único) ---------- */
    var removeModalEl = $('removeModal');
    var removeModal = null;
    if (removeModalEl && window.bootstrap) {
        document.body.appendChild(removeModalEl); // evita problemas de apilado con el layout
        removeModal = bootstrap.Modal.getOrCreateInstance(removeModalEl);
    }

    function askRemove(item) {
        var url = item.dataset.destroyUrl;
        if (!url) {
            toast('Recarga la página para poder quitar esta asistencia.', 'error');
            return;
        }
        $('removeForm').action = url;
        $('r-name').textContent = item.dataset.name;
        if (removeModal) {
            removeModal.show();
        } else if (confirm('¿Quitar la asistencia de ' + item.dataset.name + '?')) {
            $('removeForm').submit();
        }
    }

    var removeForm = $('removeForm');
    if (removeForm) {
        removeForm.addEventListener('submit', function () {
            rememberScroll();
            var btn = $('removeSubmit');
            setTimeout(function () {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Quitando…';
            }, 0);
        });
    }

    /* ---------- Un solo listener para todas las acciones ---------- */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-action]');
        if (!btn) { return; }
        var item = btn.closest('.js-student');
        if (!item) { return; }

        if (btn.dataset.action === 'register') { registerAttendance(item.dataset.studentId); }
        if (btn.dataset.action === 'remove')   { askRemove(item); }
    });

    /* ---------- Filtros: día y semestre se aplican al cambiar ---------- */
    ['f-day', 'f-semester'].forEach(function (id) {
        var el = $(id);
        if (el) { el.addEventListener('change', function () { $('filterForm').submit(); }); }
    });

    /* Restaurar el botón de quitar si se vuelve con "atrás" */
    window.addEventListener('pageshow', function (ev) {
        if (!ev.persisted) { return; }
        var btn = $('removeSubmit');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-rotate-left me-2" aria-hidden="true"></i> Sí, quitar';
        }
    });
});
</script>
@endpush