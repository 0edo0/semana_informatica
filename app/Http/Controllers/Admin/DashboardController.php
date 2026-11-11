<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Certificate;
use App\Models\Payment;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas para mostrar en el panel principal
        $totalStudents = Student::count();
        $totalCertificates = Certificate::where('is_enabled', true)->count();
        $pendingPayments = Payment::where('status', 'pendiente')->count();
        $totalActivities = Activity::count();

        return view('admin.dashboard', compact(
            'totalStudents', 
            'totalCertificates', 
            'pendingPayments', 
            'totalActivities'
        ));
    }
}