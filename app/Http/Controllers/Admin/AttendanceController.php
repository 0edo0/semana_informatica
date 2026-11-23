<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ScheduleDay;
use App\Models\Student;
use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener los días disponibles del evento
        $scheduleDays = ScheduleDay::orderBy('date', 'asc')->get();
        
        // 2. Determinar el día seleccionado (por defecto el primero)
        $selectedDayId = $request->input('day_id', $scheduleDays->first()->id ?? null);
        $selectedDay = $scheduleDays->find($selectedDayId);

        // 3. Obtener estudiantes con filtros de búsqueda y semestre
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('dni', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }

        $students = $query->orderBy('first_name', 'asc')->paginate(20)->withQueryString();

        // 4. Obtener los IDs de los estudiantes que YA tienen asistencia en el día seleccionado
        $attendedStudentIds = [];
        if ($selectedDay) {
            $attendedStudentIds = Attendance::whereDate('registered_at', $selectedDay->date)
                ->pluck('student_id')
                ->toArray();
        }

        // 5. Determinar la actividad "por defecto" para el día seleccionado
        // (Esto es por la restricción de la BD que exige un activity_id)
        $defaultActivity = null;
        if ($selectedDay) {
            $defaultActivity = Activity::where('schedule_day_id', $selectedDay->id)->first();
        }

        return view('admin.attendances.index', compact(
            'scheduleDays', 
            'selectedDay', 
            'students', 
            'attendedStudentIds',
            'defaultActivity'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'day_id' => 'required|exists:schedule_days,id',
        ]);

        $studentId = $request->student_id;
        $scheduleDay = ScheduleDay::findOrFail($request->day_id);

        // Verificar si ya está registrado en ese día
        $exists = Attendance::where('student_id', $studentId)
            ->whereDate('registered_at', $scheduleDay->date)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'El estudiante ya tiene registrada su asistencia para este día.'], 400);
        }

        // Buscar una actividad de ese día para cumplir con la relación (FK)
        $activity = Activity::where('schedule_day_id', $scheduleDay->id)->first();
        
        // Si no hay actividad, usamos la primera que encuentre (para evitar error de FK)
        if (!$activity) {
            $activity = Activity::first(); 
        }

        if (!$activity) {
            return response()->json(['error' => 'No hay actividades registradas en el sistema para vincular la asistencia.'], 400);
        }

        Attendance::create([
            'student_id' => $studentId,
            'activity_id' => $activity->id,
            'registered_at' => Carbon::parse($scheduleDay->date)->setTimeFromTimeString(now()->format('H:i:s')),
            'attended' => 1
        ]);

        return response()->json(['success' => 'Asistencia registrada correctamente.']);
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->back()->with('success', 'Asistencia eliminada correctamente.');
    }

    public function export(Request $request)
{
    $dayId = $request->input('day_id');
    $scheduleDay = ScheduleDay::findOrFail($dayId);

    $attendances = Attendance::with(['student'])
        ->whereDate('registered_at', $scheduleDay->date)
        ->get();

    $filename = "asistencia_" . \Carbon\Carbon::parse($scheduleDay->date)->format('Y-m-d') . ".csv";

    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function() use($attendances) {
        $file = fopen('php://output', 'w');
        // Añadir BOM para que Excel reconozca los acentos (UTF-8)
        fputs($file, "\xEF\xBB\xBF"); 
        
        fputcsv($file, ['DNI', 'Nombre Completo', 'Semestre', 'Carrera', 'Hora de Registro', 'Estado']);

        foreach ($attendances as $att) {
            fputcsv($file, [
                $att->student->dni,
                $att->student->first_name . ' ' . $att->student->last_name,
                $att->student->semester . '°',
                $att->student->career,
                \Carbon\Carbon::parse($att->registered_at)->format('H:i:s'),
                'Asistió'
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}