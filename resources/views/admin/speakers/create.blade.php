@extends('layouts.admin')

@section('title', 'Nuevo Ponente')

@section('content')
    <div class="dash-header mb-4">
        <h1 class="dash-title">Registrar Nuevo Ponente</h1>
    </div>

    <div class="glass-panel p-4" style="max-width: 800px;">
        <form action="{{ route('admin.speakers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label text-white">Nombre Completo *</label>
                <input type="text" name="name" class="form-control bg-transparent text-white border-purple" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Profesión / Cargo</label>
                <input type="text" name="profession" class="form-control bg-transparent text-white border-purple">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Biografía</label>
                <textarea name="bio" rows="4" class="form-control bg-transparent text-white border-purple"></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label text-white">Fotografía</label>
                <input type="file" name="photo" class="form-control bg-transparent text-white border-purple" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary" style="background: var(--adm-purple-600); border: none;">Guardar Ponente</button>
            <a href="{{ route('admin.speakers.index') }}" class="btn btn-outline-light ms-2">Cancelar</a>
        </form>
    </div>
@endsection

@push('styles')
<style>
    .border-purple { border-color: rgba(147, 51, 234, 0.4) !important; }
    .border-purple:focus { box-shadow: 0 0 10px rgba(147, 51, 234, 0.5) !important; border-color: var(--adm-violet) !important; }
</style>
@endpush