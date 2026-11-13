<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Certificate;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total de Estudiantes registrados
        $totalStudents = Student::count();

        // 2. Inscripciones de HOY
        $registrationsToday = Student::whereDate('created_at', Carbon::today())->count();

        // 3. Certificados Emitidos (Habilitados por el admin)
        $totalCertificates = Certificate::where('is_enabled', true)->count();

        // 4. Ingresos por Pagos (Solo pagos completados)
        $totalRevenue = Payment::where('status', 'completado')->sum('amount');

        // 5. Últimos 5 estudiantes registrados (con su fecha)
        $latestStudents = Student::latest()->take(5)->get();

        // Retornamos la vista pasando todas las variables
        return view('admin.dashboard', compact(
            'totalStudents',
            'registrationsToday',
            'totalCertificates',
            'totalRevenue',
            'latestStudents'
        ));
    }
}