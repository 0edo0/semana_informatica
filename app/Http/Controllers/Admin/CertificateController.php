<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index()
    {
        // Traemos los certificados con su estudiante asociado
        $certificates = Certificate::with('student')->latest()->paginate(15);
        return view('admin.certificates.index', compact('certificates'));
    }

    // Generar un certificado para un estudiante
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'activity_id' => 'nullable|exists:activities,id',
        ]);

        // Generamos un código único para la búsqueda (Ej: CERT-X8F9A1)
        $code = 'CERT-' . strtoupper(Str::random(6));

        Certificate::create([
            'student_id' => $request->student_id,
            'activity_id' => $request->activity_id,
            'code' => $code,
            'is_paid' => false,
            'is_enabled' => false, // El admin lo activará luego
        ]);

        return back()->with('success', 'Certificado generado y listo para habilitar.');
    }

    // Habilitar o marcar como pagado
    public function update(Request $request, Certificate $certificate)
    {
        $certificate->update([
            'is_paid' => $request->has('is_paid') ? true : $certificate->is_paid,
            'is_enabled' => $request->has('is_enabled') ? true : $certificate->is_enabled,
        ]);

        return back()->with('success', 'Estado del certificado actualizado.');
    }
}