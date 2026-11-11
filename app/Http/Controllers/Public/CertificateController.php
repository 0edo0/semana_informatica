<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    // Mostrar formulario de búsqueda
    public function search()
    {
        return view('public.certificates.search');
    }

    // Procesar la búsqueda
    public function find(Request $request)
    {
        $request->validate([
            'search' => 'required|string' // Puede ser DNI o el código del certificado
        ]);

        $search = $request->input('search');

        // Buscamos por código de certificado O por el DNI del estudiante
        $certificate = Certificate::with(['student', 'activity'])
            ->where('code', $search)
            ->orWhereHas('student', function ($query) use ($search) {
                $query->where('dni', $search);
            })
            ->first();

        if (!$certificate) {
            return back()->with('error', 'No se encontró ningún certificado con esos datos.');
        }

        // REGLA DE NEGOCIO: Solo se muestra si está pagado Y habilitado
        if (!$certificate->is_paid || !$certificate->is_enabled) {
            return back()->with('error', 'Tu certificado aún no está habilitado para descarga. Verifica con administración.');
        }

        return view('public.certificates.show', compact('certificate'));
    }
}