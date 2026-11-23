@extends('layouts.admin')

@section('title', 'Gestión de Estudiantes')

@section('content')
<div class="students-page">

    {{-- ===================== ENCABEZADO ===================== --}}
    <div class="st-header">
        <div class="st-header-text">
            <h2 class="st-title">
                <i class="fa-solid fa-user-graduate" aria-hidden="true"></i> Estudiantes
            </h2>
            <p class="st-subtitle">Administra los participantes de la Semana Informática.</p>
        </div>
        <button type="button" class="btn btn-neon st-btn-new" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fa-solid fa-plus me-2" aria-hidden="true"></i> Nuevo estudiante
        </button>
    </div>

    {{-- ===================== ERRORES DE VALIDACIÓN ===================== --}}
    @if ($errors->any())
        <div class="st-alert st-alert-danger" role="alert">
            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
            <div>
                <strong>No se pudo guardar. Revisa estos datos:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="glass-panel st-panel">

        {{-- ===================== FILTROS ===================== --}}
        <form method="GET" action="{{ route('admin.students.index') }}" class="st-filters" id="filterForm">
            <div class="st-field st-field-search">
                <label for="f-search" class="st-label">Buscar</label>
                <div class="st-input-icon">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" id="f-search" name="search" class="form-control st-input"
                           placeholder="DNI, nombre o correo" value="{{ request('search') }}" autocomplete="off">
                </div>
            </div>

            <div class="st-field st-field-semester">
                <label for="f-semester" class="st-label">Semestre</label>
                <select id="f-semester" name="semester" class="form-select st-input">
                    <option value="">Todos</option>
                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>2.º semestre</option>
                    <option value="4" {{ request('semester') == '4' ? 'selected' : '' }}>4.º semestre</option>
                    <option value="6" {{ request('semester') == '6' ? 'selected' : '' }}>6.º semestre</option>
                </select>
            </div>

            <div class="st-filter-actions">
                <button type="submit" class="btn st-btn-filter">
                    <i class="fa-solid fa-filter me-2" aria-hidden="true"></i> Filtrar
                </button>
                <a href="{{ route('admin.students.index') }}" class="btn st-btn-clear">
                    <i class="fa-solid fa-rotate-left me-2" aria-hidden="true"></i> Limpiar
                </a>
            </div>
        </form>

        {{-- Resumen + filtros activos --}}
        <div class="st-summary">
            <span class="st-count">
                <i class="fa-solid fa-users" aria-hidden="true"></i>
                @if (method_exists($students, 'total'))
                    <strong>{{ $students->total() }}</strong> {{ $students->total() === 1 ? 'estudiante' : 'estudiantes' }}
                @else
                    <strong>{{ $students->count() }}</strong> en esta página
                @endif
            </span>
            @if (request('search'))
                <span class="st-chip"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> “{{ request('search') }}”</span>
            @endif
            @if (request('semester'))
                <span class="st-chip"><i class="fa-solid fa-layer-group" aria-hidden="true"></i> {{ request('semester') }}.º semestre</span>
            @endif
        </div>

        {{-- ===================== TABLA (tablet / escritorio) ===================== --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-dark align-middle mb-0 st-table">
                <thead>
                    <tr>
                        <th scope="col">Estudiante</th>
                        <th scope="col">DNI</th>
                        <th scope="col">Semestre</th>
                        <th scope="col">Carrera</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr class="js-student"
                            data-name="{{ $student->first_name }} {{ $student->last_name }}"
                            data-first-name="{{ $student->first_name }}"
                            data-last-name="{{ $student->last_name }}"
                            data-dni="{{ $student->dni }}"
                            data-email="{{ $student->email }}"
                            data-phone="{{ $student->phone }}"
                            data-semester="{{ $student->semester }}"
                            data-career="{{ $student->career }}"
                            data-created="{{ $student->created_at ? $student->created_at->format('d/m/Y H:i') : 'Sin fecha' }}"
                            data-update-url="{{ route('admin.students.update', $student) }}"
                            data-destroy-url="{{ route('admin.students.destroy', $student) }}">
                            <td>
                                <div class="st-person">
                                    <div class="avatar-circle" aria-hidden="true">
                                        {{ mb_strtoupper(mb_substr($student->first_name, 0, 1)) }}{{ mb_strtoupper(mb_substr($student->last_name, 0, 1)) }}
                                    </div>
                                    <div class="st-person-info">
                                        <div class="st-name">{{ $student->first_name }} {{ $student->last_name }}</div>
                                        <div class="st-email">{{ $student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="st-dni">{{ $student->dni }}</td>
                            <td><span class="badge bg-purple-soft">{{ $student->semester }}.º</span></td>
                            <td class="st-career">{{ $student->career }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-action" data-action="view" title="Ver detalles" aria-label="Ver detalles de {{ $student->first_name }}">
                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-action" data-action="edit" title="Editar" aria-label="Editar a {{ $student->first_name }}">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-action btn-action-danger" data-action="delete" title="Eliminar" aria-label="Eliminar a {{ $student->first_name }}">
                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="st-empty">
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

        {{-- ===================== TARJETAS (móvil) ===================== --}}
        <div class="st-cards d-md-none">
            @forelse ($students as $student)
                <article class="st-card js-student"
                         data-name="{{ $student->first_name }} {{ $student->last_name }}"
                         data-first-name="{{ $student->first_name }}"
                         data-last-name="{{ $student->last_name }}"
                         data-dni="{{ $student->dni }}"
                         data-email="{{ $student->email }}"
                         data-phone="{{ $student->phone }}"
                         data-semester="{{ $student->semester }}"
                         data-career="{{ $student->career }}"
                         data-created="{{ $student->created_at ? $student->created_at->format('d/m/Y H:i') : 'Sin fecha' }}"
                         data-update-url="{{ route('admin.students.update', $student) }}"
                         data-destroy-url="{{ route('admin.students.destroy', $student) }}">
                    <div class="st-person">
                        <div class="avatar-circle" aria-hidden="true">
                            {{ mb_strtoupper(mb_substr($student->first_name, 0, 1)) }}{{ mb_strtoupper(mb_substr($student->last_name, 0, 1)) }}
                        </div>
                        <div class="st-person-info">
                            <div class="st-name">{{ $student->first_name }} {{ $student->last_name }}</div>
                            <div class="st-email">{{ $student->email }}</div>
                        </div>
                    </div>

                    <div class="st-card-meta">
                        <span><i class="fa-regular fa-id-card" aria-hidden="true"></i> {{ $student->dni }}</span>
                        <span class="badge bg-purple-soft">{{ $student->semester }}.º semestre</span>
                        <span class="st-card-career"><i class="fa-solid fa-laptop-code" aria-hidden="true"></i> {{ $student->career }}</span>
                    </div>

                    <div class="st-card-actions">
                        <button type="button" class="btn btn-action" data-action="view">
                            <i class="fa-solid fa-eye me-1" aria-hidden="true"></i> Ver
                        </button>
                        <button type="button" class="btn btn-action" data-action="edit">
                            <i class="fa-solid fa-pen me-1" aria-hidden="true"></i> Editar
                        </button>
                        <button type="button" class="btn btn-action btn-action-danger" data-action="delete">
                            <i class="fa-solid fa-trash me-1" aria-hidden="true"></i> Eliminar
                        </button>
                    </div>
                </article>
            @empty
                <div class="st-empty">
                    <i class="fa-regular fa-face-frown-open" aria-hidden="true"></i>
                    <p>No se encontraron estudiantes.</p>
                    <span>Prueba con otro DNI, nombre o semestre.</span>
                </div>
            @endforelse
        </div>

        {{-- ===================== PAGINACIÓN ===================== --}}
        <div class="st-pagination">
            @if (method_exists($students, 'total') && $students->total() > 0)
                <small class="st-showing">Mostrando {{ $students->firstItem() }}–{{ $students->lastItem() }} de {{ $students->total() }}</small>
            @endif
            <div class="st-pagination-links">
                {{ $students->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- ==========================================================
     MODALES ÚNICOS Y REUTILIZABLES
     (uno solo por acción, en lugar de uno por estudiante;
      se rellenan con JavaScript y se mueven al <body>)
========================================================== --}}

{{-- Modal: Ver detalles --}}
<div class="modal fade st-modal" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content st-modal-content">
            <div class="modal-header st-modal-header">
                <h5 class="modal-title" id="viewModalLabel"><i class="fa-solid fa-id-badge me-2" aria-hidden="true"></i> Detalles del estudiante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="st-view-head">
                    <div class="avatar-circle avatar-lg" id="v-avatar" aria-hidden="true"></div>
                    <div>
                        <div class="st-name st-name-lg" id="v-name"></div>
                        <span class="badge bg-purple-soft" id="v-semester"></span>
                    </div>
                </div>
                <dl class="st-dl">
                    <div><dt><i class="fa-regular fa-id-card" aria-hidden="true"></i> DNI</dt><dd id="v-dni"></dd></div>
                    <div><dt><i class="fa-regular fa-envelope" aria-hidden="true"></i> Correo</dt><dd><a id="v-email" href="#"></a></dd></div>
                    <div><dt><i class="fa-solid fa-phone" aria-hidden="true"></i> Teléfono</dt><dd id="v-phone"></dd></div>
                    <div><dt><i class="fa-solid fa-laptop-code" aria-hidden="true"></i> Carrera</dt><dd id="v-career"></dd></div>
                    <div><dt><i class="fa-regular fa-calendar" aria-hidden="true"></i> Registro</dt><dd id="v-created"></dd></div>
                </dl>
            </div>
            <div class="modal-footer st-modal-footer">
                <button type="button" class="btn st-btn-clear" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-neon" id="v-edit-btn"><i class="fa-solid fa-pen me-2" aria-hidden="true"></i> Editar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Editar --}}
<div class="modal fade st-modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-sm-down">
        <div class="modal-content st-modal-content">
            <form id="editForm" action="#" method="POST" class="js-submit-once st-form-flex" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-header st-modal-header">
                    <h5 class="modal-title" id="editModalLabel"><i class="fa-solid fa-pen me-2" aria-hidden="true"></i> Editar estudiante</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="e-dni" class="st-label">DNI</label>
                            <input type="text" id="e-dni" name="dni" class="form-control st-input" inputmode="numeric" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="e-email" class="st-label">Correo</label>
                            <input type="email" id="e-email" name="email" class="form-control st-input" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="e-first_name" class="st-label">Nombres</label>
                            <input type="text" id="e-first_name" name="first_name" class="form-control st-input" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="e-last_name" class="st-label">Apellidos</label>
                            <input type="text" id="e-last_name" name="last_name" class="form-control st-input" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="e-phone" class="st-label">Teléfono</label>
                            <input type="text" id="e-phone" name="phone" class="form-control st-input" inputmode="tel">
                        </div>
                        <div class="col-6 col-sm-3">
                            <label for="e-semester" class="st-label">Semestre</label>
                            <select id="e-semester" name="semester" class="form-select st-input" required>
                                <option value="2">2.º</option>
                                <option value="4">4.º</option>
                                <option value="6">6.º</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-3">
                            <label for="e-career" class="st-label">Carrera</label>
                            <input type="text" id="e-career" name="career" class="form-control st-input" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer st-modal-footer">
                    <button type="button" class="btn st-btn-clear" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-neon"><i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i> Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Crear --}}
<div class="modal fade st-modal" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-sm-down">
        <div class="modal-content st-modal-content">
            <form action="{{ route('admin.students.store') }}" method="POST" class="js-submit-once st-form-flex">
                @csrf
                <input type="hidden" name="_form" value="create">
                <div class="modal-header st-modal-header">
                    <h5 class="modal-title" id="createModalLabel"><i class="fa-solid fa-user-plus me-2" aria-hidden="true"></i> Nuevo estudiante</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="c-dni" class="st-label">DNI</label>
                            <input type="text" id="c-dni" name="dni" class="form-control st-input" inputmode="numeric" value="{{ old('dni') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="c-email" class="st-label">Correo</label>
                            <input type="email" id="c-email" name="email" class="form-control st-input" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="c-first_name" class="st-label">Nombres</label>
                            <input type="text" id="c-first_name" name="first_name" class="form-control st-input" value="{{ old('first_name') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="c-last_name" class="st-label">Apellidos</label>
                            <input type="text" id="c-last_name" name="last_name" class="form-control st-input" value="{{ old('last_name') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="c-phone" class="st-label">Teléfono</label>
                            <input type="text" id="c-phone" name="phone" class="form-control st-input" inputmode="tel" value="{{ old('phone') }}">
                        </div>
                        <div class="col-6 col-sm-3">
                            <label for="c-semester" class="st-label">Semestre</label>
                            <select id="c-semester" name="semester" class="form-select st-input" required>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>2.º</option>
                                <option value="4" {{ old('semester') == '4' ? 'selected' : '' }}>4.º</option>
                                <option value="6" {{ old('semester') == '6' ? 'selected' : '' }}>6.º</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-3">
                            <label for="c-career" class="st-label">Carrera</label>
                            <input type="text" id="c-career" name="career" class="form-control st-input" value="{{ old('career', 'Desarrollo de Sistemas') }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer st-modal-footer">
                    <button type="button" class="btn st-btn-clear" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-neon"><i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i> Guardar estudiante</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Confirmar eliminación --}}
<div class="modal fade st-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm-confirm">
        <div class="modal-content st-modal-content">
            <form id="deleteForm" action="#" method="POST" class="js-submit-once">
                @csrf
                @method('DELETE')
                <div class="modal-body st-confirm">
                    <div class="st-confirm-icon" aria-hidden="true"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <h5 id="deleteModalLabel">¿Eliminar estudiante?</h5>
                    <p>Vas a eliminar a <strong id="d-name"></strong> (DNI <span id="d-dni"></span>).<br>Esta acción no se puede deshacer.</p>
                    <div class="st-confirm-actions">
                        <button type="button" class="btn st-btn-clear" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn st-btn-danger"><i class="fa-solid fa-trash me-2" aria-hidden="true"></i> Sí, eliminar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ==========================================================
       ESTUDIANTES — estilos de la vista
       (colores claros y legibles sobre fondo oscuro)
    ========================================================== */
    :root {
        --st-text:   #f5f1fd;
        --st-soft:   #cfc4e8;
        --st-faint:  #a79bc4;
        --st-line:   rgba(192, 132, 252, .22);
        --st-field:  rgba(24, 15, 40, .85);
        --st-violet: var(--adm-violet, #c084fc);
        --st-p600:   var(--adm-purple-600, #9333ea);
        --st-p900:   var(--adm-purple-900, #6b21a8);
    }

    /* El texto "muted" de Bootstrap es gris oscuro y se pierde: se aclara en esta vista */
    .students-page .text-muted { color: var(--st-soft) !important; }

    /* ---------- Encabezado ---------- */
    .st-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.4rem;
    }
    .st-title {
        margin: 0 0 .2rem;
        color: #fff;
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: clamp(1.45rem, 4vw, 1.9rem);
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .st-title i { color: var(--st-violet); }
    .st-subtitle { margin: 0; color: var(--st-soft); font-size: .92rem; }

    /* ---------- Botones ---------- */
    .btn-neon {
        background: linear-gradient(135deg, var(--st-p600), var(--st-p900));
        color: #fff;
        border: 1px solid rgba(192, 132, 252, .5);
        border-radius: 10px;
        font-weight: 600;
        padding: .6rem 1.2rem;
        box-shadow: 0 0 15px rgba(147, 51, 234, .3);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .btn-neon:hover, .btn-neon:focus-visible {
        transform: translateY(-2px);
        box-shadow: 0 0 25px rgba(147, 51, 234, .6);
        color: #fff;
    }
    .btn-neon:disabled { opacity: .7; transform: none; }

    .st-btn-filter {
        background: rgba(147, 51, 234, .18);
        border: 1px solid var(--st-p600);
        color: #fff;
        border-radius: 10px;
        font-weight: 600;
    }
    .st-btn-filter:hover { background: var(--st-p600); color: #fff; }

    .st-btn-clear {
        background: rgba(255, 255, 255, .05);
        border: 1px solid rgba(255, 255, 255, .22);
        color: var(--st-text);
        border-radius: 10px;
        font-weight: 500;
    }
    .st-btn-clear:hover { background: rgba(255, 255, 255, .12); color: #fff; }

    .st-btn-danger {
        background: linear-gradient(135deg, #f43f5e, #be123c);
        border: 1px solid rgba(253, 164, 175, .5);
        color: #fff;
        border-radius: 10px;
        font-weight: 600;
        padding: .6rem 1.2rem;
    }
    .st-btn-danger:hover { color: #fff; box-shadow: 0 0 22px rgba(244, 63, 94, .5); }
    .st-btn-danger:disabled { opacity: .7; }

    /* Foco visible para teclado */
    .btn:focus-visible, .st-input:focus-visible, .btn-action:focus-visible {
        outline: 2px solid var(--st-violet);
        outline-offset: 2px;
    }

    /* ---------- Alertas ---------- */
    .st-alert {
        display: flex;
        gap: .8rem;
        align-items: flex-start;
        padding: .9rem 1.1rem;
        border-radius: 12px;
        margin-bottom: 1.2rem;
        font-size: .92rem;
    }
    .st-alert i { margin-top: .2rem; }
    .st-alert-danger {
        background: rgba(244, 63, 94, .12);
        border: 1px solid rgba(244, 63, 94, .45);
        color: #ffd5dc;
    }

    /* ---------- Panel ---------- */
    .st-panel { padding: 1.4rem; }

    /* ---------- Campos (filtros y formularios) ---------- */
    .st-label {
        display: block;
        margin-bottom: .4rem;
        color: var(--st-soft);
        font-size: .82rem;
        font-weight: 600;
        letter-spacing: .2px;
    }
    .st-input,
    .form-select.st-input {
        background-color: var(--st-field);
        border: 1px solid var(--st-line);
        color: #fff;
        border-radius: 10px;
        min-height: 44px;
        font-size: 1rem;
    }
    .st-input::placeholder { color: #8f82b0; opacity: 1; }
    .st-input:focus {
        background-color: rgba(30, 19, 50, .95);
        border-color: var(--st-violet);
        box-shadow: 0 0 0 .22rem rgba(147, 51, 234, .3);
        color: #fff;
    }
    .form-select.st-input {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23c084fc' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    }
    .st-input option, .st-input optgroup { background: #150d24; color: #fff; }
    .st-input:-webkit-autofill {
        -webkit-text-fill-color: #fff;
        box-shadow: 0 0 0 1000px #1d1230 inset;
    }

    /* Input con icono a la izquierda */
    .st-input-icon { position: relative; }
    .st-input-icon > i {
        position: absolute;
        left: 14px; top: 50%;
        transform: translateY(-50%);
        color: var(--st-violet);
        pointer-events: none;
    }
    .st-input-icon .st-input { padding-left: 2.6rem; }

    /* ---------- Filtros ---------- */
    .st-filters {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        align-items: end;
        padding: 1rem;
        border-radius: 14px;
        background: rgba(147, 51, 234, .06);
        border: 1px solid var(--st-line);
    }
    .st-filter-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .6rem;
    }
    .st-filter-actions .btn { min-height: 44px; display: inline-flex; align-items: center; justify-content: center; }

    @media (min-width: 768px) {
        .st-filters { grid-template-columns: minmax(0, 2fr) minmax(0, 1fr) auto; }
        .st-filter-actions { grid-template-columns: auto auto; }
    }

    /* ---------- Resumen ---------- */
    .st-summary {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .6rem;
        margin: 1.1rem 0 .9rem;
    }
    .st-count { color: var(--st-soft); font-size: .9rem; }
    .st-count i { color: var(--st-violet); margin-right: .35rem; }
    .st-count strong { color: #fff; }
    .st-chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .25rem .75rem;
        border-radius: 50rem;
        font-size: .8rem;
        color: var(--st-text);
        background: rgba(147, 51, 234, .2);
        border: 1px solid rgba(192, 132, 252, .4);
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ---------- Tabla ---------- */
    .st-table {
        --bs-table-bg: transparent;
        --bs-table-color: var(--st-text);
        --bs-table-hover-bg: rgba(147, 51, 234, .1);
        --bs-table-hover-color: #fff;
        --bs-table-border-color: var(--st-line);
        background: transparent;
    }
    .st-table thead th {
        color: var(--st-violet);
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        padding: .8rem .75rem;
        border-bottom: 1px solid var(--st-line);
        white-space: nowrap;
    }
    .st-table tbody td { padding: .8rem .75rem; }
    .st-table tbody tr { cursor: default; }
    .st-dni { color: #fff; font-variant-numeric: tabular-nums; }
    .st-career { color: var(--st-soft); font-size: .88rem; }

    /* ---------- Persona (avatar + nombre) ---------- */
    .st-person { display: flex; align-items: center; gap: .85rem; min-width: 0; }
    .st-person-info { min-width: 0; }
    .st-name { color: #fff; font-weight: 700; line-height: 1.25; overflow-wrap: anywhere; }
    .st-name-lg { font-size: 1.2rem; font-family: 'Outfit', sans-serif; margin-bottom: .35rem; }
    .st-email { color: var(--st-soft); font-size: .84rem; overflow-wrap: anywhere; }

    .avatar-circle {
        flex: 0 0 auto;
        width: 42px; height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--st-p600), var(--st-p900));
        display: grid; place-items: center;
        font-weight: 700; color: #fff; font-family: 'Outfit', sans-serif;
        border: 2px solid rgba(192, 132, 252, .45);
    }
    .avatar-lg { width: 60px; height: 60px; font-size: 1.2rem; }

    .bg-purple-soft {
        background: rgba(147, 51, 234, .25);
        color: #ead7ff;
        border: 1px solid rgba(192, 132, 252, .4);
        padding: .4em .8em;
        font-weight: 600;
    }

    /* ---------- Botones de acción ---------- */
    .btn-action {
        background: rgba(147, 51, 234, .14);
        border: 1px solid var(--st-line);
        color: #e9d5ff;
        border-radius: 8px;
        margin: 0 2px;
        transition: background .2s ease, color .2s ease;
    }
    .btn-action:hover { background: rgba(147, 51, 234, .45); color: #fff; }
    .btn-action-danger {
        background: rgba(244, 63, 94, .12);
        border-color: rgba(244, 63, 94, .4);
        color: #fecdd3;
    }
    .btn-action-danger:hover { background: rgba(244, 63, 94, .5); color: #fff; }
    .st-table .btn-action { width: 36px; height: 36px; padding: 0; }

    /* ---------- Tarjetas móviles ---------- */
    .st-cards { display: grid; gap: .8rem; }
    .st-card {
        padding: 1rem;
        border-radius: 14px;
        background: rgba(147, 51, 234, .07);
        border: 1px solid var(--st-line);
    }
    .st-card-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .5rem .9rem;
        margin: .85rem 0;
        color: var(--st-text);
        font-size: .88rem;
    }
    .st-card-meta i { color: var(--st-violet); margin-right: .35rem; }
    .st-card-career { color: var(--st-soft); }
    .st-card-actions { display: grid; grid-template-columns: repeat(3, 1fr); gap: .5rem; }
    .st-card-actions .btn-action {
        margin: 0;
        min-height: 44px;
        font-size: .85rem;
        font-weight: 600;
        padding: .4rem .3rem;
    }

    /* ---------- Estado vacío ---------- */
    .st-empty { text-align: center; padding: 2.5rem 1rem; color: var(--st-soft); }
    .st-empty i { font-size: 2.4rem; color: var(--st-violet); margin-bottom: .8rem; }
    .st-empty p { color: #fff; font-weight: 600; margin: 0 0 .2rem; }
    .st-empty span { font-size: .88rem; }

    /* ---------- Paginación ---------- */
    .st-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .8rem;
        margin-top: 1.4rem;
    }
    .st-showing { color: var(--st-soft); }
    .st-pagination-links { max-width: 100%; overflow-x: auto; }
    .st-pagination-links nav > div:first-child { display: none; } /* oculta el texto duplicado de Laravel */
    .st-pagination-links .pagination { margin: 0; flex-wrap: wrap; gap: 2px; }
    .pagination .page-link {
        background: rgba(24, 15, 40, .85);
        border-color: var(--st-line);
        color: #e9d5ff;
        min-width: 40px;
        text-align: center;
    }
    .pagination .page-item.active .page-link {
        background: var(--st-p600);
        border-color: var(--st-p600);
        color: #fff;
    }
    .pagination .page-item.disabled .page-link {
        background: rgba(24, 15, 40, .5);
        color: #8f82b0;
        border-color: var(--st-line);
    }
    .pagination .page-link:hover { background: rgba(147, 51, 234, .35); color: #fff; }
    .pagination .page-link:focus { box-shadow: 0 0 0 .2rem rgba(147, 51, 234, .3); }

    @media (max-width: 575.98px) {
        .st-panel { padding: 1rem .85rem; }
        .st-btn-new { width: 100%; min-height: 46px; }
        .st-pagination { justify-content: center; }
        .st-showing { width: 100%; text-align: center; }
    }

    /* ==========================================================
       MODALES — sin backdrop-filter (era la causa del lag)
    ========================================================== */
    .st-modal .st-modal-content {
        background: #0e0818;
        border: 1px solid var(--st-line);
        border-radius: 16px;
        color: var(--st-text);
        box-shadow: 0 25px 60px rgba(0, 0, 0, .6), 0 0 40px rgba(147, 51, 234, .15);
    }
    .st-modal .st-modal-header {
        border-bottom: 1px solid var(--st-line);
        padding: 1rem 1.25rem;
    }
    .st-modal .modal-title {
        color: #fff;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .st-modal .modal-title i { color: var(--st-violet); }
    .st-modal .modal-body { padding: 1.25rem; }
    .st-modal .st-modal-footer {
        border-top: 1px solid var(--st-line);
        padding: .9rem 1.25rem;
        gap: .5rem;
    }
    .st-modal .st-label { color: var(--st-soft); }
    .st-modal .st-input { color: #fff; }
    .st-form-flex { display: flex; flex-direction: column; min-height: 0; max-height: 100%; }
    .st-form-flex .modal-body { overflow-y: auto; }

    /* El fondo oscuro del modal, ligero */
    .modal-backdrop.show { opacity: .72; }
    .modal.fade .modal-dialog { transition: transform .22s ease-out; }

    @media (max-width: 575.98px) {
        .st-modal .st-modal-footer { flex-direction: column-reverse; }
        .st-modal .st-modal-footer .btn { width: 100%; min-height: 46px; margin: 0; }
        .st-modal .modal-content { border-radius: 0; }
    }

    /* Detalle */
    .st-view-head {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--st-line);
    }
    .st-dl { margin: 0; display: grid; gap: .75rem; }
    .st-dl > div {
        display: grid;
        grid-template-columns: 130px 1fr;
        gap: .5rem;
        align-items: baseline;
    }
    .st-dl dt { color: var(--st-soft); font-weight: 500; font-size: .88rem; }
    .st-dl dt i { color: var(--st-violet); width: 1.4rem; }
    .st-dl dd { margin: 0; color: #fff; overflow-wrap: anywhere; }
    .st-dl dd a { color: #e9d5ff; text-decoration: underline; text-underline-offset: 3px; }
    @media (max-width: 420px) {
        .st-dl > div { grid-template-columns: 1fr; gap: .1rem; }
    }

    /* Confirmación de eliminar */
    .modal-sm-confirm { max-width: 400px; }
    .st-confirm { text-align: center; padding: 2rem 1.5rem 1.5rem !important; }
    .st-confirm-icon {
        width: 68px; height: 68px;
        margin: 0 auto 1rem;
        display: grid; place-items: center;
        border-radius: 50%;
        font-size: 1.7rem;
        color: #fda4af;
        background: rgba(244, 63, 94, .15);
        border: 1px solid rgba(244, 63, 94, .45);
        box-shadow: 0 0 28px rgba(244, 63, 94, .3);
    }
    .st-confirm h5 { color: #fff; font-family: 'Outfit', sans-serif; font-weight: 700; margin-bottom: .6rem; }
    .st-confirm p { color: var(--st-soft); margin-bottom: 1.4rem; line-height: 1.6; }
    .st-confirm p strong { color: #fff; }
    .st-confirm-actions { display: flex; gap: .6rem; justify-content: center; }
    .st-confirm-actions .btn { flex: 1; min-height: 46px; }
    @media (max-width: 420px) {
        .st-confirm-actions { flex-direction: column-reverse; }
    }

    @media (prefers-reduced-motion: reduce) {
        .modal.fade .modal-dialog, .btn, .btn-action { transition: none !important; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.bootstrap) { return; }

    var $ = function (id) { return document.getElementById(id); };

    /* Los modales se mueven al <body>: así ningún contenedor con
       backdrop-filter / transform del layout los deforma o los hace lentos. */
    document.querySelectorAll('.st-modal').forEach(function (m) { document.body.appendChild(m); });

    var viewModal   = bootstrap.Modal.getOrCreateInstance($('viewModal'));
    var editModal   = bootstrap.Modal.getOrCreateInstance($('editModal'));
    var deleteModal = bootstrap.Modal.getOrCreateInstance($('deleteModal'));

    var current = null; // estudiante seleccionado (dataset de la fila/tarjeta)

    /* ---------- Rellenar modal de detalle ---------- */
    function fillView(d) {
        var initials = ((d.firstName || '').charAt(0) + (d.lastName || '').charAt(0)).toUpperCase();
        $('v-avatar').textContent   = initials;
        $('v-name').textContent     = d.name;
        $('v-semester').textContent = d.semester + '.º semestre';
        $('v-dni').textContent      = d.dni;
        $('v-career').textContent   = d.career;
        $('v-created').textContent  = d.created;
        $('v-phone').textContent    = d.phone ? d.phone : 'No registrado';

        var mail = $('v-email');
        mail.textContent = d.email;
        mail.href = 'mailto:' + d.email;
    }

    /* ---------- Rellenar modal de edición ---------- */
    function fillEdit(d) {
        $('editForm').action = d.updateUrl;
        $('e-dni').value        = d.dni;
        $('e-email').value      = d.email;
        $('e-first_name').value = d.firstName;
        $('e-last_name').value  = d.lastName;
        $('e-phone').value      = d.phone || '';
        $('e-semester').value   = d.semester;
        $('e-career').value     = d.career;
    }

    /* ---------- Rellenar confirmación de eliminar ---------- */
    function fillDelete(d) {
        $('deleteForm').action = d.destroyUrl;
        $('d-name').textContent = d.name;
        $('d-dni').textContent  = d.dni;
    }

    /* ---------- Un solo listener para todas las acciones ---------- */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-action]');
        if (!btn) { return; }

        var item = btn.closest('.js-student');
        if (!item) { return; }

        current = item.dataset;

        switch (btn.dataset.action) {
            case 'view':   fillView(current);   viewModal.show();   break;
            case 'edit':   fillEdit(current);   editModal.show();   break;
            case 'delete': fillDelete(current); deleteModal.show(); break;
        }
    });

    /* Botón "Editar" dentro del modal de detalle */
    $('v-edit-btn').addEventListener('click', function () {
        if (!current) { return; }
        $('viewModal').addEventListener('hidden.bs.modal', function openEdit() {
            fillEdit(current);
            editModal.show();
        }, { once: true });
        viewModal.hide();
    });

    /* ---------- Evitar envíos dobles + estado de carga ---------- */
    document.querySelectorAll('.js-submit-once').forEach(function (form) {
        form.addEventListener('submit', function () {
            var submit = form.querySelector('button[type="submit"]');
            if (!submit || submit.dataset.loading) { return; }
            submit.dataset.loading = '1';
            submit.dataset.original = submit.innerHTML;
            // Se deshabilita después de que el navegador arme el envío
            setTimeout(function () {
                submit.disabled = true;
                submit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Procesando…';
            }, 0);
        });
    });

    /* Restaurar botones si el usuario vuelve con el botón "atrás" */
    window.addEventListener('pageshow', function (ev) {
        if (!ev.persisted) { return; }
        document.querySelectorAll('.js-submit-once button[type="submit"]').forEach(function (b) {
            if (b.dataset.original) { b.innerHTML = b.dataset.original; }
            b.disabled = false;
            delete b.dataset.loading;
        });
    });

    /* ---------- Filtros: aplicar al cambiar el semestre ---------- */
    var semester = $('f-semester');
    if (semester) {
        semester.addEventListener('change', function () { $('filterForm').submit(); });
    }

    /* ---------- Si hubo errores al crear, reabrir el modal con los datos ---------- */
    @if ($errors->any() && old('_form') === 'create')
        bootstrap.Modal.getOrCreateInstance($('createModal')).show();
    @endif
});
</script>
@endpush