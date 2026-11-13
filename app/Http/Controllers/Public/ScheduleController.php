<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ScheduleDay;

class ScheduleController extends Controller
{
    public function index()
    {
        // Traemos los días del evento con sus actividades y ponentes
        // Usamos 'with' para evitar el problema de N+1 consultas (optimización)
        $scheduleDays = ScheduleDay::with(['activities' => function ($query) {
            $query->orderBy('start_time', 'asc'); // Ordenamos las ponencias por hora
        }, 'activities.speaker'])
        ->orderBy('date', 'asc') // Ordenamos los días cronológicamente
        ->get();

        // Retornamos la vista (que crearemos en el siguiente paso)
        return view('public.schedule.index', compact('scheduleDays'));
    }
}