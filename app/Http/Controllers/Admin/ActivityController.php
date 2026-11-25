<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ScheduleDay;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index()
    {
        // Traemos los días con sus actividades y ponentes ordenados
        $scheduleDays = ScheduleDay::with(['activities' => function($query) {
            $query->orderBy('start_time', 'asc');
        }, 'activities.speaker'])->orderBy('date', 'asc')->get();

        // Traemos todos los ponentes para los modales
        $speakers = Speaker::orderBy('name')->get();

        return view('admin.activities.index', compact('scheduleDays', 'speakers'));
    }

    public function create(Request $request)
    {
        $speakers = Speaker::orderBy('name')->get();
        $scheduleDays = ScheduleDay::orderBy('date', 'asc')->get();
        
        $selectedDayId = $request->query('day_id');

        return view('admin.activities.create', compact('speakers', 'scheduleDays', 'selectedDayId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_day_id' => 'required|exists:schedule_days,id',
            'speaker_id' => 'required|exists:speakers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $dayId = $request->schedule_day_id;
        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);

        // REGLA 1: Restricción de horario global (11:00 AM a 2:00 PM)
        $limiteInicio = Carbon::parse('11:00');
        $limiteFin = Carbon::parse('14:00');

        if ($startTime->lt($limiteInicio) || $endTime->gt($limiteFin)) {
            return back()->withInput()->with('error', 'Las actividades solo pueden programarse entre las 11:00 AM y las 2:00 PM.');
        }

        // REGLA 2: Máximo 5 actividades por día
        $actividadesDelDia = Activity::where('schedule_day_id', $dayId)->count();
        if ($actividadesDelDia >= 5) {
            return back()->withInput()->with('error', 'Límite alcanzado. Solo se permiten máximo 5 actividades por día.');
        }

        // REGLA 3 (ELIMINADA): Ya no se bloquea el choque de horarios.
        // Esto permite que una ponencia empiece a la misma hora que terminó la anterior
        // o que se solapen por temas de logística.

        Activity::create($request->all());

        return redirect()->route('admin.activities.index')
            ->with('success', 'Actividad programada correctamente.');
    }

    public function edit(Activity $activity)
    {
        $speakers = Speaker::orderBy('name')->get();
        $scheduleDays = ScheduleDay::orderBy('date', 'asc')->get();
        
        return view('admin.activities.edit', compact('activity', 'speakers', 'scheduleDays'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'schedule_day_id' => 'required|exists:schedule_days,id',
            'speaker_id' => 'required|exists:speakers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);

        // Regla 1: Horario permitido
        if ($startTime->lt(Carbon::parse('11:00')) || $endTime->gt(Carbon::parse('14:00'))) {
            return back()->withInput()->with('error', 'Las actividades solo pueden programarse entre las 11:00 AM y las 2:00 PM.');
        }

        // REGLA 2 (ELIMINADA): Ya no se valida el choque de horario al editar.
        // Se permite guardar sin importar si se cruza con otra ponencia.

        $activity->update($request->all());

        return redirect()->route('admin.activities.index')
            ->with('success', 'Actividad actualizada correctamente.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('admin.activities.index')
            ->with('success', 'Actividad eliminada del cronograma.');
    }
}