@extends('layouts.admin')

@section('title', 'Gestión de Ponentes')

@section('content')

    {{-- ===== Encabezado de la página ===== --}}
    <div class="page-header">
        <div>
            <span class="page-badge">
                <i class="fa-solid fa-microphone-lines"></i> Ponentes · DEV FEST 2026
            </span>
            <h1 class="page-title">Gestión de Ponentes</h1>
            <p class="page-sub">Administra a los expositores de la Semana Informática DSI: datos, fotos y biografías.</p>
        </div>

        <button type="button" class="btn-primary-neon btn-new" data-bs-toggle="modal"
                data-bs-target="#createSpeakerModal" aria-label="Nuevo Ponente" title="Nuevo Ponente">
            <i class="fa-solid fa-user-plus"></i>
            <span class="btn-label">Nuevo Ponente</span>
        </button>
    </div>

    {{-- ===== Listado de ponentes (DATOS REALES) ===== --}}
    <div class="speakers-card glass-panel">
        <div class="sp-head">
            <div>
                <h2 class="sp-title"><i class="fa-solid fa-list-ul"></i> Listado de ponentes</h2>
                <p class="sp-count">{{ $speakers->total() }} ponentes registrados hasta el momento</p>
            </div>
        </div>

        <div class="table-responsive sp-table-wrap">
            <table class="table align-middle speakers-table">
                <thead>
                    <tr>
                        <th style="width:84px">Foto</th>
                        <th>Nombre</th>
                        <th>Profesión</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($speakers as $speaker)
                        <tr>
                            <td>
                                @if($speaker->photo_path)
                                    <img src="{{ $speaker->photo_path }}" class="sp-avatar" alt="{{ $speaker->name }}" loading="lazy">
                                @else
                                    <div class="sp-avatar d-flex align-items-center justify-content-center" style="background: var(--adm-purple-900); color: white; font-weight: bold;">
                                        {{ strtoupper(substr($speaker->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td><span class="sp-name">{{ $speaker->name }}</span></td>
                            <td><span class="sp-role">{{ $speaker->profession ?? 'No especificada' }}</span></td>
                            <td class="text-end">
                                <span class="sp-actions">
                                    {{-- Editar / Eliminar se manejan con JS (el JS también
                                         reubica los modales al body, ver la sección final de scripts) --}}
                                    <button type="button" class="btn-ver btn-edit" title="Editar ponente"
                                            data-id="{{ $speaker->id }}"
                                            data-name="{{ $speaker->name }}"
                                            data-profession="{{ $speaker->profession }}"
                                            data-bio="{{ $speaker->bio }}"
                                            data-photo="{{ $speaker->photo_path ? asset('storage/' . $speaker->photo_path) : '' }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn-ver btn-del" title="Eliminar ponente"
                                            data-id="{{ $speaker->id }}"
                                            data-name="{{ $speaker->name }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="sp-empty">
                                <i class="fa-solid fa-microphone-slash fs-2 mb-3 d-block" style="color: var(--adm-violet);"></i>
                                No hay ponentes registrados aún. <br> ¡Haz clic en "Nuevo Ponente" para agregar el primero!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sp-pagination">
            {{ $speakers->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- MODAL A · Crear ponente --}}
    <div class="modal fade" id="createSpeakerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable sp-modal-dialog">
            <div class="modal-content sp-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-user-plus"></i> Nuevo Ponente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('admin.speakers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="createName">Nombre completo</label>
                            <input type="text" class="form-control" id="createName" name="name" value="{{ old('name') }}" placeholder="Ej.: Dra. Alejandra Quispe Castro" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="createProfession">Profesión / Cargo</label>
                            <input type="text" class="form-control" id="createProfession" name="profession" value="{{ old('profession') }}" placeholder="Ej.: Especialista en Ciberseguridad" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="createBio">Biografía</label>
                            <textarea class="form-control" id="createBio" name="bio" rows="3" placeholder="Breve descripción del ponente...">{{ old('bio') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="createPhoto">Foto</label>
                            <input type="file" class="form-control" id="createPhoto" name="photo" accept="image/*">
                            <div class="form-text">Formatos JPG o PNG · Máximo 2 MB.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-ghost-neon" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-primary-neon"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL B · Editar ponente --}}
    <div class="modal fade" id="editSpeakerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable sp-modal-dialog">
            <div class="modal-content sp-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-pen-to-square"></i> Editar Ponente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                {{-- Usamos una ruta base, el JS se encargará de poner el ID correcto --}}
                <form id="editSpeakerForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Foto actual</label>
                            <div class="current-photo-box">
                                <img id="editCurrentPhoto" src="" alt="Foto actual">
                                <div class="cp-text">
                                    <span class="cp-name" id="editSpeakerNameDisplay"></span>
                                    <span class="cp-hint">Sube una imagen nueva solo si deseas reemplazarla</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="editName">Nombre completo</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="editProfession">Profesión / Cargo</label>
                            <input type="text" class="form-control" id="editProfession" name="profession" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="editBio">Biografía</label>
                            <textarea class="form-control" id="editBio" name="bio" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="editPhoto">Reemplazar foto (opcional)</label>
                            <input type="file" class="form-control" id="editPhoto" name="photo" accept="image/*">
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

    {{-- MODAL C · Eliminar ponente --}}
    <div class="modal fade" id="deleteSpeakerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered sp-modal-dialog sp-modal-dialog-sm">
            <div class="modal-content sp-modal sp-modal-danger">
                <div class="modal-body delete-body">
                    <div class="danger-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <h5 class="delete-title">¿Eliminar ponente?</h5>
                    <p class="delete-text">
                        ¿Estás seguro de que deseas eliminar a <strong id="deleteSpeakerName"></strong>?
                        Esta acción no se puede deshacer.
                    </p>
                    <form id="deleteSpeakerForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="delete-actions">
                            <button type="button" class="btn-ghost-neon" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn-danger-neon"><i class="fa-solid fa-trash-can"></i> Sí, eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    /* ==================================================
       Módulo Ponentes · DEV FEST 2026
       ================================================== */

    /* ═══════════════ FIX RAÍZ DEL BLOQUEO ═══════════════
       El layout deja un transform persistente en .page-enter
       (animation-fill-mode: both + keyframe final translateY(0)),
       y TODO ancestro con transform se convierte en el
       "containing block" de los elementos position:fixed:
       el .modal y su .modal-backdrop dejaban de posicionarse
       contra el viewport real, quedaban atrapados dentro de
       .page-enter (por debajo del topbar/sidebar) y se movían
       al hacer scroll => modal "congelado" e imposible de cerrar.
       Con "backwards" la animación de entrada se ve idéntica,
       pero al terminar NO queda ningún transform aplicado.
       (Además, el JS sube los modales al <body> por si acaso.) */
    .page-enter{ animation-fill-mode: backwards; }
    /* ════════════════════════════════════════════════════ */

    .page-header{ display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.7rem; }
    .page-badge{ display: inline-flex; align-items: center; gap: .45rem; padding: .34rem .8rem; border-radius: 99px; font-size: .7rem; font-weight: 600; letter-spacing: 1.2px; text-transform: uppercase; color: var(--adm-violet); background: rgba(147, 51, 234, .13); border: 1px solid rgba(147, 51, 234, .4); box-shadow: 0 0 14px rgba(147, 51, 234, .2); }
    .page-badge i{ text-shadow: 0 0 8px rgba(192, 132, 252, .8); }
    .page-title{ font-size: clamp(1.7rem, 3.2vw, 2.3rem); font-weight: 800; color: #fff; letter-spacing: .5px; margin: .6rem 0 .4rem; text-shadow: 0 0 28px rgba(147, 51, 234, .45); }
    .page-sub{ color: var(--adm-muted); font-size: .92rem; margin: 0; max-width: 62ch; }

    .btn-primary-neon{ display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-height: 44px; padding: .6rem 1.25rem; border-radius: 12px; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .9rem; color: #fff; cursor: pointer; background: linear-gradient(135deg, var(--adm-purple-600), var(--adm-purple-900)); border: 1px solid rgba(192, 132, 252, .6); box-shadow: 0 0 18px rgba(147, 51, 234, .45); transition: all .3s ease; }
    .btn-primary-neon:hover{ transform: translateY(-2px) scale(1.02); color: #fff; box-shadow: 0 0 30px rgba(147, 51, 234, .65); }
    .btn-ghost-neon{ display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-height: 44px; padding: .6rem 1.1rem; border-radius: 12px; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: .88rem; color: var(--adm-violet); cursor: pointer; background: rgba(147, 51, 234, .08); border: 1px solid rgba(147, 51, 234, .4); transition: all .3s ease; }
    .btn-ghost-neon:hover{ color: #fff; background: rgba(147, 51, 234, .25); box-shadow: 0 0 16px rgba(147, 51, 234, .35); }
    .btn-danger-neon{ display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-height: 44px; padding: .6rem 1.1rem; border-radius: 12px; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .88rem; color: #fff; cursor: pointer; background: linear-gradient(135deg, #e11d48, #9f1239); border: 1px solid rgba(251, 113, 133, .55); box-shadow: 0 0 14px rgba(225, 29, 72, .3); transition: all .3s ease; }
    .btn-danger-neon:hover{ transform: translateY(-1px); color: #fff; box-shadow: 0 0 26px rgba(225, 29, 72, .55); }

    .speakers-card{ padding: 1.4rem 1.4rem 1.1rem; animation: fadeUp .5s ease both; }
    .sp-head{ display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.1rem; }
    .sp-title{ display: flex; align-items: center; gap: .6rem; font-size: 1.12rem; font-weight: 700; color: #fff; margin: 0; }
    .sp-title i{ color: var(--adm-violet); filter: drop-shadow(0 0 7px rgba(192, 132, 252, .7)); }
    .sp-count{ margin: .15rem 0 0; font-size: .76rem; color: var(--adm-muted); }

    .sp-table-wrap{ overflow: visible; }
    .speakers-table{ --bs-table-bg: transparent; --bs-table-color: var(--adm-text); margin-bottom: .3rem; }
    .speakers-table thead th{ font-size: .66rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.6px; color: var(--adm-violet); border-bottom: 1px solid rgba(147, 51, 234, .35) !important; padding: .75rem; white-space: nowrap; }
    .speakers-table tbody td{ padding: .85rem .75rem; border-bottom: 1px solid rgba(147, 51, 234, .12); font-size: .88rem; }
    .speakers-table tbody tr{ transition: background .25s ease, transform .25s ease; }
    .speakers-table tbody tr:hover{ background: rgba(147, 51, 234, .09); }

    .sp-avatar{ width: 48px; height: 48px; object-fit: cover; border-radius: 14px; border: 1px solid rgba(192, 132, 252, .45); box-shadow: 0 0 12px rgba(147, 51, 234, .35); }
    .sp-name{ color: #fff; font-weight: 600; font-size: .92rem; }
    .sp-role{ color: var(--adm-muted); font-size: .8rem; }

    .sp-actions{ display: inline-flex; align-items: center; gap: .4rem; }
    .btn-ver{ display: inline-grid; place-items: center; width: 38px; height: 38px; padding: 0; border-radius: 10px; cursor: pointer; font-size: .85rem; transition: all .3s ease; }
    .btn-ver.btn-edit{ color: var(--adm-violet); background: rgba(147, 51, 234, .08); border: 1px solid rgba(147, 51, 234, .4); }
    .btn-ver.btn-edit:hover{ color: #fff; background: var(--adm-purple-600); border-color: var(--adm-purple-600); box-shadow: 0 0 16px rgba(147, 51, 234, .5); transform: translateY(-1px); }
    .btn-ver.btn-del{ color: #fda4af; background: rgba(244, 63, 94, .08); border: 1px solid rgba(244, 63, 94, .35); }
    .btn-ver.btn-del:hover{ color: #fff; background: rgba(244, 63, 94, .5); border-color: rgba(244, 63, 94, .6); box-shadow: 0 0 16px rgba(244, 63, 94, .4); transform: translateY(-1px); }

    .sp-pagination{ margin-top: 1rem; display: flex; justify-content: center; }
    .sp-pagination .pagination{ gap: .3rem; margin: 0; }
    .sp-pagination .page-item{ background: transparent; }
    .sp-pagination .page-link{ min-width: 42px; text-align: center; background: rgba(147, 51, 234, .1); border: 1px solid rgba(147, 51, 234, .35); color: var(--adm-violet); font-weight: 600; border-radius: 10px !important; transition: all .3s ease; }
    .sp-pagination .page-link:hover{ background: rgba(147, 51, 234, .3); color: #fff; border-color: rgba(192, 132, 252, .5); }
    .sp-pagination .page-item.active .page-link{ background: var(--adm-purple-600); border-color: var(--adm-purple-600); color: #fff; box-shadow: 0 0 14px rgba(147, 51, 234, .5); }
    .sp-pagination .page-item.disabled .page-link{ background: transparent; border-color: rgba(147, 51, 234, .15); color: rgba(155, 143, 179, .5); }

    .modal-backdrop.show{ opacity: .78; background: #030108; }
    .sp-modal-dialog{ max-width: 540px; }
    .sp-modal-dialog-sm{ max-width: 420px; }
    .modal-content.sp-modal{ background: rgba(14, 8, 23, .92); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(147, 51, 234, .35); border-radius: 18px; color: var(--adm-text); box-shadow: 0 20px 60px rgba(0, 0, 0, .6), 0 0 40px rgba(147, 51, 234, .15); }
    .sp-modal .modal-header{ border-bottom: 1px solid rgba(147, 51, 234, .2); padding: 1rem 1.1rem; }
    .sp-modal .modal-title{ display: flex; align-items: center; gap: .6rem; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.05rem; color: #fff; }
    .sp-modal .modal-title i{ color: var(--adm-violet); text-shadow: 0 0 10px rgba(192, 132, 252, .8); }
    .sp-modal .modal-body{ padding: 1.15rem 1.1rem; }
    .sp-modal .modal-footer{ border-top: 1px solid rgba(147, 51, 234, .2); padding: .9rem 1.1rem; gap: .5rem; }

    /* ═══════════ FIX · Botón de cierre táctil (≥ 44px) ═══════════
       Antes era diminuto y en el teléfono costaba acertarle. Ahora
       tiene un área de toque grande con un chip morado visible. */
    .sp-modal .btn-close{ width: 2.75rem; height: 2.75rem; padding: .95rem; margin: -.85rem -.5rem -.85rem auto; background-size: .9rem .9rem; background-color: rgba(147, 51, 234, .16); border-radius: 12px; transition: background-color .25s ease; }
    .sp-modal .btn-close:hover{ background-color: rgba(147, 51, 234, .4); }
    /* ══════════════════════════════════════════════════════════════ */

    .sp-modal .form-label{ font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.6px; color: var(--adm-violet); margin-bottom: .35rem; }
    .sp-modal .form-control{ background: rgba(5, 2, 8, .6); border: 1px solid rgba(147, 51, 234, .3); color: var(--adm-text); border-radius: 11px; padding: .65rem .85rem; font-size: .9rem; min-height: 44px; transition: all .3s ease; }
    .sp-modal .form-control::placeholder{ color: rgba(155, 143, 179, .55); }
    .sp-modal .form-control:focus{ background: rgba(5, 2, 8, .8); border-color: var(--adm-purple-600); color: #fff; box-shadow: 0 0 0 .2rem rgba(147, 51, 234, .25); }
    .sp-modal .form-control[type="file"]{ padding: .45rem .55rem; }
    .sp-modal .form-control::file-selector-button{ margin-right: .8rem; border: none; border-radius: 8px; background: rgba(147, 51, 234, .25); color: var(--adm-violet); padding: .5rem .9rem; font-weight: 600; cursor: pointer; transition: all .3s ease; }
    .sp-modal .form-control:hover::file-selector-button{ background: rgba(147, 51, 234, .45); color: #fff; }
    .sp-modal .form-text{ color: rgba(155, 143, 179, .85); font-size: .72rem; }

    .current-photo-box{ display: flex; align-items: center; gap: .85rem; padding: .75rem; border-radius: 14px; background: rgba(5, 2, 8, .45); border: 1px dashed rgba(147, 51, 234, .35); }
    .current-photo-box img{ width: 56px; height: 56px; flex: 0 0 56px; object-fit: cover; border-radius: 12px; border: 1px solid rgba(192, 132, 252, .45); box-shadow: 0 0 12px rgba(147, 51, 234, .35); }
    .cp-name{ display: block; color: #fff; font-weight: 600; font-size: .88rem; }
    .cp-hint{ display: block; font-size: .72rem; color: var(--adm-muted); }

    .sp-modal-danger{ border-color: rgba(244, 63, 94, .45) !important; }
    .delete-body{ padding: 1.6rem 1.4rem 1.4rem; text-align: center; }
    .danger-icon{ width: 64px; height: 64px; margin: 0 auto 1rem; border-radius: 50%; display: grid; place-items: center; font-size: 1.6rem; color: #fb7185; background: rgba(244, 63, 94, .12); border: 1px solid rgba(244, 63, 94, .4); animation: glowPulse 2s ease-in-out infinite; }
    @keyframes glowPulse{ 0%, 100%{ box-shadow: 0 0 14px rgba(244, 63, 94, .25); } 50%     { box-shadow: 0 0 30px rgba(244, 63, 94, .5); } }
    .delete-title{ font-family: 'Outfit', sans-serif; font-weight: 800; color: #fff; margin-bottom: .5rem; }
    .delete-text{ color: var(--adm-muted); font-size: .9rem; margin-bottom: 1.2rem; }
    .delete-text strong{ color: #fda4af; }
    .delete-actions{ display: flex; gap: .6rem; }
    .delete-actions .btn-ghost-neon, .delete-actions .btn-danger-neon{ flex: 1; }

    /* Estado vacío (con padding propio para que también se vea bien
       cuando la tabla se convierte en tarjetas en el móvil) */
    .sp-empty{ padding: 2.6rem 1.2rem; color: var(--adm-muted); font-size: .92rem; }
    .sp-empty i{ text-shadow: 0 0 12px rgba(192, 132, 252, .6); }

    /* ==================================================
       RESPONSIVE · Mobile First
       ================================================== */
    @media (max-width: 767.98px){
        .page-title{ font-size: 1.55rem; }
        .page-sub{ font-size: .85rem; }
        .sp-title{ font-size: 1rem; }
        .sp-count{ font-size: .72rem; }
        .btn-primary-neon{ padding: .6rem .9rem; font-size: .82rem; }
        .speakers-table thead th{ padding: .6rem .5rem; font-size: .6rem; }
        .speakers-table tbody td{ padding: .65rem .5rem; }
        .speakers-table{ display: block; }
        .speakers-table thead{ display: none; }
        .speakers-table tbody{ display: flex; flex-direction: column; gap: .75rem; }
        .speakers-table tbody tr{ display: grid; grid-template-columns: 52px minmax(0, 1fr) auto; grid-template-rows: auto auto; grid-template-areas: "foto nombre    acciones" "foto profesion acciones"; align-items: center; gap: .15rem .8rem; padding: .85rem; border: 1px solid var(--adm-border) !important; border-radius: 16px; background: rgba(18, 11, 30, .55); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }
        .speakers-table tbody tr:hover{ transform: translateY(-2px); }
        .speakers-table tbody td{ display: block; width: 100%; padding: 0 !important; border-bottom: 0 !important; font-size: .85rem; }
        .speakers-table tbody td:nth-child(1){ grid-area: foto; }
        .speakers-table tbody td:nth-child(2){ grid-area: nombre; }
        .speakers-table tbody td:nth-child(3){ grid-area: profesion; }
        .speakers-table tbody td:nth-child(4){ grid-area: acciones; }
        /* El estado vacío no es una tarjeta: mantiene su padding */
        .speakers-table tbody td.sp-empty{ padding: 2.4rem .9rem !important; text-align: center; }
        .speakers-table .sp-avatar{ width: 52px; height: 52px; }
        .speakers-table .sp-name{ font-size: .85rem; }
        .speakers-table .sp-role{ font-size: .72rem; }
        .speakers-table .sp-actions{ display: grid; gap: .45rem; justify-items: end; }
        .btn-ver{ width: 44px; height: 44px; font-size: .9rem; border-radius: 11px; }
        .sp-modal-dialog{ width: 95%; max-width: 95%; margin: .55rem auto; }
        .sp-modal .modal-title{ font-size: .95rem; }
        .sp-modal .modal-body{ padding: 1rem .85rem; font-size: .85rem; }
        .sp-modal .form-label{ font-size: .64rem; }
        .sp-modal .form-control{ font-size: .85rem; }
        .sp-modal .modal-footer{ padding: .8rem .95rem; }
        .sp-modal .modal-footer .btn-ghost-neon, .sp-modal .modal-footer .btn-primary-neon{ flex: 1 1 0; padding: .6rem .5rem; font-size: .85rem; }
        .sp-modal .btn-close{ width: 2.6rem; height: 2.6rem; margin: -.7rem -.4rem -.7rem auto; }
        .delete-text{ font-size: .85rem; }
    }
    @media (max-width: 575.98px){
        .btn-primary-neon.btn-new{ width: 48px; min-height: 48px; padding: 0; }
        .btn-primary-neon.btn-new .btn-label{ display: none; }
        .page-header{ align-items: center; }
    }
</style>
@endpush

@push('scripts')
<script>
    // ==================================================
    //  Módulo Ponentes · Lógica de Modales (FIX bloqueo)
    // ==================================================
    document.addEventListener('DOMContentLoaded', () => {

        // ═══════════════ FIX CRÍTICO ═══════════════
        // Los 3 modales estaban dentro de .page-enter del layout, que
        // mantiene un transform persistente por su animación
        // (animation-fill-mode: both). Un ancestro con transform se
        // convierte en el "containing block" de los elementos
        // position:fixed: el .modal y su .modal-backdrop dejaban de
        // posicionarse contra el viewport, quedaban POR DEBAJO del
        // topbar y del sidebar, se desplazaban con el scroll y quedaban
        // fuera de la pantalla => el modal parecía "congelado" y nada
        // se podía cerrar.
        //
        // Solución: subir cada .modal a <body> ANTES de instanciarlo.
        // Así el modal y su backdrop vuelven a ser fijos respecto al
        // viewport real, cubren TODA la pantalla (sidebar incluido) y
        // el cierre (X, Cancelar, clic fuera, ESC) funciona siempre.
        document.querySelectorAll('.modal').forEach(modalEl => {
            document.body.appendChild(modalEl);
        });
        // ═══════════════════════════════════════════

        if (typeof bootstrap === 'undefined') {
            console.error('[Ponentes] Bootstrap JS no está cargado: los modales no funcionarán.');
            return;
        }

        // ---- Elementos del formulario de edición ----
        const editForm         = document.getElementById('editSpeakerForm');
        const editName         = document.getElementById('editName');
        const editProfession   = document.getElementById('editProfession');
        const editBio          = document.getElementById('editBio');
        const editPhotoInput   = document.getElementById('editPhoto');
        const editCurrentPhoto = document.getElementById('editCurrentPhoto');
        const editNameDisplay  = document.getElementById('editSpeakerNameDisplay');
        const editModalEl      = document.getElementById('editSpeakerModal');

        // ---- Elementos del formulario de eliminación ----
        const deleteForm    = document.getElementById('deleteSpeakerForm');
        const deleteName    = document.getElementById('deleteSpeakerName');
        const deleteModalEl = document.getElementById('deleteSpeakerModal');

        // getOrCreateInstance evita instancias duplicadas del mismo modal
        // (antes, dos instancias sobre el mismo elemento dejaban estados
        //  inconsistentes: backdrop sin quitar, modal que no respondía).
        const editModal   = bootstrap.Modal.getOrCreateInstance(editModalEl);
        const deleteModal = bootstrap.Modal.getOrCreateInstance(deleteModalEl);

        // Ruta base: /admin/speakers  (el JS le añade /{id})
        const baseUrl = "{{ route('admin.speakers.index') }}";

        // ---- Lógica para EDITAR ----
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = btn.dataset;

                // 1. Action dinámico con el ID real => /admin/speakers/{id}
                editForm.action = baseUrl + '/' + data.id;

                // 2. Rellenar los campos
                editName.value       = data.name || '';
                editProfession.value = data.profession || '';
                editBio.value        = data.bio || '';
                editPhotoInput.value = ''; // limpiar input de archivo

                // 3. Foto actual
                editNameDisplay.textContent = data.name || '';
                if (data.photo) {
                    editCurrentPhoto.style.display = 'block';
                    editCurrentPhoto.src = data.photo;
                } else {
                    editCurrentPhoto.style.display = 'none';
                    editCurrentPhoto.removeAttribute('src');
                }

                // 4. Mostrar el modal (ahora sí: vive en <body>)
                editModal.show();
            });
        });

        // ---- Lógica para ELIMINAR ----
        document.querySelectorAll('.btn-del').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = btn.dataset;

                // 1. Action dinámico con el ID real => /admin/speakers/{id}
                deleteForm.action = baseUrl + '/' + data.id;

                // 2. Nombre en el mensaje de confirmación
                deleteName.textContent = data.name || 'este ponente';

                // 3. Mostrar el modal
                deleteModal.show();
            });
        });

        // ---- Limpieza al cerrar el modal de edición ----
        editModalEl.addEventListener('hidden.bs.modal', () => {
            editPhotoInput.value = '';
        });
    });
</script>
@endpush