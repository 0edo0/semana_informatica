@extends('layouts.admin')

@section('title', 'Cronograma y Actividades')

@section('content')

    {{-- ===== Encabezado ===== --}}
    <div class="page-header">
        <div>
            <span class="page-badge">
                <i class="fa-solid fa-calendar-days"></i> Cronograma · DEV FEST 2026
            </span>
            <h1 class="page-title">Gestión del Cronograma</h1>
            <p class="page-sub">
                Administra los días del evento y sus respectivas ponencias. <br>
                <strong style="color: var(--adm-violet);">Horario permitido: 11:00 AM - 2:00 PM (Máx. 5 por día).</strong>
            </p>
        </div>
        
        <div class="d-flex gap-2">
            {{-- Botón para Crear Día --}}
            <button type="button" class="btn-ghost-neon" data-bs-toggle="modal" data-bs-target="#createDayModal">
                <i class="fa-solid fa-calendar-plus"></i> <span class="btn-label">Nuevo Día</span>
            </button>
            {{-- Botón para Crear Actividad --}}
            <button type="button" class="btn-primary-neon" data-bs-toggle="modal" data-bs-target="#createActivityModal">
                <i class="fa-solid fa-plus"></i> <span class="btn-label">Nueva Ponencia</span>
            </button>
        </div>
    </div>

    {{-- ===== Listado de Días y Actividades ===== --}}
    <div class="schedule-wrapper">
        @forelse($scheduleDays as $day)
            <div class="day-card glass-panel">
                {{-- Cabecera del Día --}}
                <div class="day-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="day-date-badge">
                            <span class="dd-month">{{ \Carbon\Carbon::parse($day->date)->translatedFormat('M') }}</span>
                            <span class="dd-day">{{ \Carbon\Carbon::parse($day->date)->format('d') }}</span>
                        </div>
                        <div>
                            <h3 class="day-title">{{ $day->title }}</h3>
                            <p class="day-subtitle">{{ \Carbon\Carbon::parse($day->date)->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-count">{{ $day->activities->count() }} / 5 Actividades</span>
                        
                        {{-- Botón para agregar actividad a este día específico (Máximo 5) --}}
                        @if($day->activities->count() < 5)
                            <button type="button" class="btn-icon btn-add-to-day" title="Agregar actividad a este día"
                                    data-day-id="{{ $day->id }}"
                                    data-day-title="{{ $day->title }}">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        @endif
                        
                        {{-- Botón Eliminar Día --}}
                        <button type="button" class="btn-icon btn-del-day" title="Eliminar Día"
                                data-id="{{ $day->id }}" data-name="{{ $day->title }}">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>

                {{-- Actividades del día (Timeline) --}}
                <div class="activities-list">
                    @forelse($day->activities as $activity)
                        <div class="activity-item">
                            <div class="activity-time">
                                <i class="fa-regular fa-clock"></i>
                                {{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($activity->end_time)->format('H:i') }}
                            </div>
                            <div class="activity-info">
                                <h4 class="activity-title">{{ $activity->title }}</h4>
                                <div class="activity-speaker">
                                    @if($activity->speaker)
                                        @if($activity->speaker->photo_path)
                                            <img src="{{ $activity->speaker->photo_path }}" class="speaker-mini-avatar" alt="Foto">
                                        @else
                                            <div class="speaker-mini-avatar d-flex align-items-center justify-content-center bg-purple text-white" style="font-size: 0.7rem;">
                                                {{ strtoupper(substr($activity->speaker->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span>{{ $activity->speaker->name }}</span>
                                    @else
                                        <span class="text-muted">Sin ponente asignado</span>
                                    @endif
                                </div>
                            </div>
                            <div class="activity-actions">
                                {{-- Botón Editar --}}
                                <button type="button" class="btn-icon btn-edit-activity" title="Editar"
                                        data-id="{{ $activity->id }}"
                                        data-day-id="{{ $activity->schedule_day_id }}"
                                        data-speaker-id="{{ $activity->speaker_id }}"
                                        data-title="{{ $activity->title }}"
                                        data-description="{{ $activity->description }}"
                                        data-start="{{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }}"
                                        data-end="{{ \Carbon\Carbon::parse($activity->end_time)->format('H:i') }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                {{-- Botón Eliminar --}}
                                <button type="button" class="btn-icon btn-del-activity" title="Eliminar"
                                        data-id="{{ $activity->id }}" data-name="{{ $activity->title }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-activities">
                            <i class="fa-solid fa-mug-hot"></i> No hay ponencias programadas para este día.
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="glass-panel p-5 text-center">
                <i class="fa-solid fa-calendar-xmark fs-1 mb-3" style="color: var(--adm-violet);"></i>
                <h3 class="text-white">No hay días registrados</h3>
                <p class="text-muted">Comienza creando el primer día del evento (30 de Noviembre).</p>
                <button type="button" class="btn-primary-neon mt-3" data-bs-toggle="modal" data-bs-target="#createDayModal">
                    Crear Primer Día
                </button>
            </div>
        @endforelse
    </div>

    {{-- ================================================== --}}
    {{-- MODALES                                           --}}
    {{-- ================================================== --}}

    {{-- 1. MODAL CREAR DÍA --}}
    <div class="modal fade" id="createDayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered sp-modal-dialog">
            <div class="modal-content sp-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-calendar-plus"></i> Nuevo Día</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('admin.schedule-days.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Fecha del Evento</label>
                            <input type="date" class="form-control" name="date" required min="2026-11-30" max="2026-12-04">
                            <div class="form-text">El evento dura del 30 Nov al 04 Dic.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Título del Día</label>
                            <input type="text" class="form-control" name="title" required placeholder="Ej.: Día 1 - Inauguración">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-ghost-neon" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-primary-neon"><i class="fa-solid fa-floppy-disk"></i> Guardar Día</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. MODAL ELIMINAR DÍA --}}
    <div class="modal fade" id="deleteDayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered sp-modal-dialog sp-modal-dialog-sm">
            <div class="modal-content sp-modal sp-modal-danger">
                <div class="modal-body delete-body">
                    <div class="danger-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <h5 class="delete-title">¿Eliminar Día?</h5>
                    <p class="delete-text">¿Estás seguro de eliminar el día <strong id="deleteDayName"></strong>? Se borrarán también todas sus ponencias.</p>
                    <form id="deleteDayForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="delete-actions">
                            <button type="button" class="btn-ghost-neon" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn-danger-neon">Sí, eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. MODAL CREAR ACTIVIDAD --}}
    <div class="modal fade" id="createActivityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable sp-modal-dialog">
            <div class="modal-content sp-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-plus"></i> Nueva Ponencia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('admin.activities.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Día del Evento</label>
                                <select class="form-select" name="schedule_day_id" id="create_day_id" required>
                                    <option value="">Seleccione un día...</option>
                                    @foreach($scheduleDays as $d)
                                        <option value="{{ $d->id }}" {{ $d->activities->count() >= 5 ? 'disabled' : '' }}>
                                            {{ \Carbon\Carbon::parse($d->date)->format('d/m/Y') }} - {{ $d->title }}{{ $d->activities->count() >= 5 ? ' (Lleno)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ponente</label>
                                <select class="form-select" name="speaker_id" id="create_speaker_id" required>
                                    <option value="">Seleccione un ponente...</option>
                                    @foreach($speakers as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Título de la Ponencia</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción (Opcional)</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Inicio</label>
                                <input type="time" class="form-control" name="start_time" min="11:00" max="13:30" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Fin</label>
                                <input type="time" class="form-control" name="end_time" min="11:30" max="14:00" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-ghost-neon" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-primary-neon"><i class="fa-solid fa-floppy-disk"></i> Programar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 4. MODAL EDITAR ACTIVIDAD --}}
    <div class="modal fade" id="editActivityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable sp-modal-dialog">
            <div class="modal-content sp-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-pen-to-square"></i> Editar Ponencia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="editActivityForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Día del Evento</label>
                                <select class="form-select" name="schedule_day_id" id="edit_day_id" required>
                                    @foreach($scheduleDays as $d)
                                        <option value="{{ $d->id }}">{{ \Carbon\Carbon::parse($d->date)->format('d/m/Y') }} - {{ $d->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ponente</label>
                                <select class="form-select" name="speaker_id" id="edit_speaker_id" required>
                                    <option value="">Seleccione un ponente...</option>
                                    @foreach($speakers as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Título de la Ponencia</label>
                                <input type="text" class="form-control" name="title" id="edit_title" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción (Opcional)</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Inicio</label>
                                <input type="time" class="form-control" name="start_time" id="edit_start_time" min="11:00" max="13:30" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Fin</label>
                                <input type="time" class="form-control" name="end_time" id="edit_end_time" min="11:30" max="14:00" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-ghost-neon" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-primary-neon"><i class="fa-solid fa-arrows-rotate"></i> Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 5. MODAL ELIMINAR ACTIVIDAD --}}
    <div class="modal fade" id="deleteActivityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered sp-modal-dialog sp-modal-dialog-sm">
            <div class="modal-content sp-modal sp-modal-danger">
                <div class="modal-body delete-body">
                    <div class="danger-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <h5 class="delete-title">¿Eliminar Ponencia?</h5>
                    <p class="delete-text">¿Estás seguro de eliminar la ponencia <strong id="deleteActivityName"></strong>?</p>
                    <form id="deleteActivityForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="delete-actions">
                            <button type="button" class="btn-ghost-neon" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn-danger-neon">Sí, eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    /* Estilos Base */
    .page-header{ display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.7rem; }
    .page-badge{ display: inline-flex; align-items: center; gap: .45rem; padding: .34rem .8rem; border-radius: 99px; font-size: .7rem; font-weight: 600; letter-spacing: 1.2px; text-transform: uppercase; color: var(--adm-violet); background: rgba(147, 51, 234, .13); border: 1px solid rgba(147, 51, 234, .4); box-shadow: 0 0 14px rgba(147, 51, 234, .2); }
    .page-title{ font-size: clamp(1.7rem, 3.2vw, 2.3rem); font-weight: 800; color: #fff; letter-spacing: .5px; margin: .6rem 0 .4rem; text-shadow: 0 0 28px rgba(147, 51, 234, .45); }
    .page-sub{ color: var(--adm-muted); font-size: .92rem; margin: 0; max-width: 62ch; }
    
    .btn-primary-neon{ display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-height: 44px; padding: .6rem 1.25rem; border-radius: 12px; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .9rem; color: #fff; cursor: pointer; background: linear-gradient(135deg, var(--adm-purple-600), var(--adm-purple-900)); border: 1px solid rgba(192, 132, 252, .6); box-shadow: 0 0 18px rgba(147, 51, 234, .45); transition: all .3s ease; }
    .btn-primary-neon:hover{ transform: translateY(-2px) scale(1.02); color: #fff; box-shadow: 0 0 30px rgba(147, 51, 234, .65); }
    .btn-ghost-neon{ display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-height: 44px; padding: .6rem 1.1rem; border-radius: 12px; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: .88rem; color: var(--adm-violet); cursor: pointer; background: rgba(147, 51, 234, .08); border: 1px solid rgba(147, 51, 234, .4); transition: all .3s ease; }
    .btn-ghost-neon:hover{ color: #fff; background: rgba(147, 51, 234, .25); box-shadow: 0 0 16px rgba(147, 51, 234, .35); }
    .btn-danger-neon{ display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-height: 44px; padding: .6rem 1.1rem; border-radius: 12px; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .88rem; color: #fff; cursor: pointer; background: linear-gradient(135deg, #e11d48, #9f1239); border: 1px solid rgba(251, 113, 133, .55); box-shadow: 0 0 14px rgba(225, 29, 72, .3); transition: all .3s ease; }
    .btn-danger-neon:hover{ transform: translateY(-1px); color: #fff; box-shadow: 0 0 26px rgba(225, 29, 72, .55); }

    /* Estilos del Cronograma */
    .schedule-wrapper { display: flex; flex-direction: column; gap: 1.5rem; }
    .day-card { padding: 1.5rem; border: 1px solid var(--adm-border); border-radius: 18px; position: relative; overflow: hidden; }
    .day-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--adm-purple-600); }
    
    .day-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(147, 51, 234, 0.2); padding-bottom: 1rem; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem; }
    .day-date-badge { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 14px; background: rgba(147, 51, 234, 0.15); border: 1px solid rgba(192, 132, 252, 0.4); }
    .dd-month { font-size: 0.7rem; text-transform: uppercase; color: var(--adm-violet); font-weight: 700; letter-spacing: 1px; }
    .dd-day { font-size: 1.4rem; font-weight: 800; color: #fff; line-height: 1; }
    .day-title { font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0; }
    .day-subtitle { font-size: 0.8rem; color: var(--adm-muted); margin: 0; text-transform: capitalize; }
    .badge-count { background: rgba(5, 2, 8, 0.5); padding: 0.3rem 0.8rem; border-radius: 99px; font-size: 0.7rem; color: var(--adm-violet); border: 1px solid rgba(147, 51, 234, 0.3); }
    
    .btn-icon { display: inline-grid; place-items: center; width: 38px; height: 38px; border-radius: 10px; background: transparent; border: 1px solid transparent; cursor: pointer; transition: all 0.2s; }
    .btn-icon:hover { background: rgba(147, 51, 234, 0.2); }
    .btn-add-to-day { color: var(--adm-violet); border-color: rgba(147, 51, 234, 0.4); }
    .btn-del-day { color: #fda4af; }
    .btn-del-day:hover { background: rgba(244, 63, 94, 0.2); border-color: rgba(244, 63, 94, 0.4); }

    /* Timeline de Actividades */
    .activities-list { display: flex; flex-direction: column; gap: 0.8rem; position: relative; margin-left: 20px; }
    .activities-list::before { content: ''; position: absolute; left: 0; top: 15px; bottom: 15px; width: 2px; background: rgba(147, 51, 234, 0.2); z-index: 0; }
    
    .activity-item { display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(5, 2, 8, 0.4); border-radius: 12px; border: 1px solid rgba(147, 51, 234, 0.15); transition: all 0.2s; flex-wrap: wrap; position: relative; z-index: 1; margin-left: 15px; }
    .activity-item::before { content: ''; position: absolute; left: -20px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; border-radius: 50%; background: var(--adm-purple-600); border: 2px solid var(--adm-dark); }
    .activity-item:hover { background: rgba(147, 51, 234, 0.08); border-color: rgba(147, 51, 234, 0.3); }
    
    .activity-time { font-weight: 600; color: var(--adm-violet); font-size: 0.85rem; min-width: 110px; display: flex; align-items: center; gap: 0.4rem; }
    .activity-info { flex: 1; min-width: 200px; }
    .activity-title { font-size: 0.95rem; font-weight: 600; color: #fff; margin: 0 0 0.2rem 0; }
    .activity-speaker { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--adm-muted); }
    .speaker-mini-avatar { width: 24px; height: 24px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(192, 132, 252, 0.4); }
    
    .activity-actions { display: flex; gap: 0.4rem; margin-left: auto; }
    .btn-edit-activity { color: var(--adm-violet); border-color: rgba(147, 51, 234, 0.3); }
    .btn-edit-activity:hover { background: var(--adm-purple-600); color: #fff; }
    .btn-del-activity { color: #fda4af; border-color: rgba(244, 63, 94, 0.3); }
    .btn-del-activity:hover { background: rgba(244, 63, 94, 0.5); color: #fff; }
    
    .empty-activities { text-align: center; padding: 1.5rem; color: var(--adm-muted); font-size: 0.85rem; font-style: italic; }

    /* Modales (Glassmorphism corregido) */
    /* z-index altos para quedar por encima de sidebar/topbar del layout admin.
       El modal va SIEMPRE por encima del backdrop. */
    .modal-backdrop { --bs-backdrop-zindex: 1990; z-index: 1990; }
    .modal-backdrop.show { opacity: .78; background: #030108; }
    .modal { z-index: 2000; }
    .sp-modal-dialog{ max-width: 540px; }
    .sp-modal-dialog-sm{ max-width: 420px; }
    .modal-content.sp-modal{ background: rgba(14, 8, 23, .95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(147, 51, 234, .35); border-radius: 18px; color: var(--adm-text); box-shadow: 0 20px 60px rgba(0, 0, 0, .6), 0 0 40px rgba(147, 51, 234, .15); }
    .sp-modal .modal-header{ border-bottom: 1px solid rgba(147, 51, 234, .2); padding: 1rem 1.1rem; }
    .sp-modal .modal-title{ display: flex; align-items: center; gap: .6rem; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.05rem; color: #fff; }
    .sp-modal .modal-title i{ color: var(--adm-violet); text-shadow: 0 0 10px rgba(192, 132, 252, .8); }
    .sp-modal .modal-body{ padding: 1.15rem 1.1rem; }
    .sp-modal .modal-footer{ border-top: 1px solid rgba(147, 51, 234, .2); padding: .9rem 1.1rem; gap: .5rem; }
    .sp-modal .form-label{ font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.6px; color: var(--adm-violet); margin-bottom: .35rem; }
    .sp-modal .form-control, .sp-modal .form-select { background: rgba(5, 2, 8, .6); border: 1px solid rgba(147, 51, 234, .3); color: var(--adm-text); border-radius: 11px; padding: .65rem .85rem; font-size: .9rem; min-height: 44px; transition: all .3s ease; }
    .sp-modal .form-control:focus, .sp-modal .form-select:focus { background: rgba(5, 2, 8, .8); border-color: var(--adm-purple-600); color: #fff; box-shadow: 0 0 0 .2rem rgba(147, 51, 234, .25); }
    .sp-modal .form-text{ color: rgba(155, 143, 179, .85); font-size: .72rem; }
    
    .sp-modal-danger{ border-color: rgba(244, 63, 94, .45) !important; }
    .delete-body{ padding: 1.6rem 1.4rem 1.4rem; text-align: center; }
    .danger-icon{ width: 64px; height: 64px; margin: 0 auto 1rem; border-radius: 50%; display: grid; place-items: center; font-size: 1.6rem; color: #fb7185; background: rgba(244, 63, 94, .12); border: 1px solid rgba(244, 63, 94, .4); }
    .delete-title{ font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-bottom: .5rem; }
    .delete-text{ color: var(--adm-muted); font-size: .9rem; margin-bottom: 1.2rem; }
    .delete-actions{ display: flex; gap: .6rem; }
    .delete-actions .btn-ghost-neon, .delete-actions .btn-danger-neon{ flex: 1; }

    /* Responsive */
    @media (max-width: 768px) {
        .day-header { flex-direction: column; align-items: flex-start; }
        .activity-item { flex-direction: column; align-items: flex-start; gap: 0.5rem; position: relative; padding-right: 3.5rem; margin-left: 0; }
        .activity-item::before { display: none; }
        .activities-list { margin-left: 0; }
        .activities-list::before { left: 10px; }
        .activity-time { min-width: 100%; }
        .activity-actions { position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); flex-direction: column; }
        .btn-icon { width: 44px; height: 44px; background: rgba(147, 51, 234, 0.1); }
        .sp-modal-dialog { width: 95%; max-width: 95%; margin: .55rem auto; }
    }
    @media (max-width: 575.98px) {
        .btn-primary-neon .btn-label, .btn-ghost-neon .btn-label { display: none; }
        .btn-primary-neon, .btn-ghost-neon { width: 48px; min-height: 48px; padding: 0; border-radius: 50%; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // =====================================================
        // FIX PRINCIPAL: mover los modales al <body>.
        // Dentro del layout quedaban atrapados en un stacking
        // context y el backdrop oscuro los tapaba (pantalla
        // oscura y sin poder escribir/clickear).
        // =====================================================
        ['createDayModal', 'deleteDayModal', 'createActivityModal', 'editActivityModal', 'deleteActivityModal']
            .forEach(id => {
                const el = document.getElementById(id);
                if (el) document.body.appendChild(el);
            });

        // Reutiliza la instancia si ya existe (evita instancias duplicadas)
        const getModal = (id) => bootstrap.Modal.getOrCreateInstance(document.getElementById(id));

        // Seguridad: si no queda ningún modal abierto, limpiar restos de backdrop/bloqueo de scroll
        document.addEventListener('hidden.bs.modal', () => {
            if (!document.querySelector('.modal.show')) {
                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
            }
        });

        // Evitar doble envío en todos los formularios de los modales
        document.querySelectorAll('.modal form').forEach(form => {
            form.addEventListener('submit', () => {
                setTimeout(() => {
                    form.querySelectorAll('button[type="submit"]').forEach(b => b.disabled = true);
                }, 0);
            });
        });

        // Validación: la hora de fin debe ser posterior a la de inicio
        const bindTimeValidation = (startEl, endEl) => {
            const check = () => {
                if (startEl.value && endEl.value && endEl.value <= startEl.value) {
                    endEl.setCustomValidity('La hora de fin debe ser posterior a la hora de inicio.');
                } else {
                    endEl.setCustomValidity('');
                }
            };
            startEl.addEventListener('input', check);
            endEl.addEventListener('input', check);
            return check;
        };

        // --- LÓGICA DÍAS ---
        const deleteDayForm = document.getElementById('deleteDayForm');
        const deleteDayName = document.getElementById('deleteDayName');
        const deleteDayModal = getModal('deleteDayModal');

        document.querySelectorAll('.btn-del-day').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = btn.dataset;
                deleteDayForm.action = `/admin/schedule-days/${data.id}`;
                deleteDayName.textContent = data.name;
                deleteDayModal.show();
            });
        });

        // --- LÓGICA ACTIVIDADES ---
        const createActivityModalEl = document.getElementById('createActivityModal');
        const createActivityModal = getModal('createActivityModal');
        const createDayIdInput = document.getElementById('create_day_id');

        bindTimeValidation(
            createActivityModalEl.querySelector('[name="start_time"]'),
            createActivityModalEl.querySelector('[name="end_time"]')
        );

        // Al cerrar el modal de crear, limpiar el formulario (evita que quede
        // el día preseleccionado de la vez anterior al abrirlo desde "Nueva Ponencia")
        createActivityModalEl.addEventListener('hidden.bs.modal', () => {
            createActivityModalEl.querySelector('form').reset();
        });

        // 1. Abrir modal de crear actividad con el DÍA PRESELECCIONADO
        document.querySelectorAll('.btn-add-to-day').forEach(btn => {
            btn.addEventListener('click', () => {
                createDayIdInput.value = btn.dataset.dayId;
                createActivityModal.show();
            });
        });

        // 2. Editar Actividad
        const editActivityForm = document.getElementById('editActivityForm');
        const editDayId = document.getElementById('edit_day_id');
        const editSpeakerId = document.getElementById('edit_speaker_id');
        const editTitle = document.getElementById('edit_title');
        const editDescription = document.getElementById('edit_description');
        const editStartTime = document.getElementById('edit_start_time');
        const editEndTime = document.getElementById('edit_end_time');
        const editActivityModal = getModal('editActivityModal');
        const checkEditTimes = bindTimeValidation(editStartTime, editEndTime);

        document.querySelectorAll('.btn-edit-activity').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;

                editActivityForm.action = `/admin/activities/${d.id}`;

                editDayId.value = d.dayId;
                editSpeakerId.value = d.speakerId || '';
                editTitle.value = d.title;
                editDescription.value = d.description || '';
                editStartTime.value = d.start;
                editEndTime.value = d.end;
                checkEditTimes();

                editActivityModal.show();
            });
        });

        // 3. Eliminar Actividad
        const deleteActivityForm = document.getElementById('deleteActivityForm');
        const deleteActivityName = document.getElementById('deleteActivityName');
        const deleteActivityModal = getModal('deleteActivityModal');

        document.querySelectorAll('.btn-del-activity').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = btn.dataset;
                deleteActivityForm.action = `/admin/activities/${data.id}`;
                deleteActivityName.textContent = data.name;
                deleteActivityModal.show();
            });
        });

    });
</script>
@endpush