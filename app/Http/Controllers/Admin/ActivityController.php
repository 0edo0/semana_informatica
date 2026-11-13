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

        // AGREGA ESTA LÍNEA: Traemos todos los ponentes para los modales
        $speakers = Speaker::orderBy('name')->get();

        // Pasamos ambas variables a la vista
        return view('admin.activities.index', compact('scheduleDays', 'speakers'));
    }

    // Formulario para crear una actividad
    public function create(Request $request)
    {
        $speakers = Speaker::orderBy('name')->get();
        $scheduleDays = ScheduleDay::orderBy('date', 'asc')->get();
        
        // Si vienen desde un día en específico, seleccionamos ese día por defecto
        $selectedDayId = $request->query('day_id');

        return view('admin.activities.create', compact('speakers', 'scheduleDays', 'selectedDayId'));
    }

    // Guardar nueva actividad (CON LAS REGLAS DE NEGOCIO)
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

        // REGLA 2: Máximo 2 actividades por día
        $actividadesDelDia = Activity::where('schedule_day_id', $dayId)->count();
        if ($actividadesDelDia >= 5) { // <--- CAMBIAR 2 POR 5
            return back()->withInput()->with('error', 'Límite alcanzado. Solo se permiten máximo 5 actividades por día.');
        }

        // REGLA 3: Sin choques de horario (si ya hay una actividad, la nueva debe empezar después de que termine la anterior)
        $ultimaActividad = Activity::where('schedule_day_id', $dayId)->orderBy('end_time', 'desc')->first();
        if ($ultimaActividad) {
            $finAnterior = Carbon::parse($ultimaActividad->end_time);
            if ($startTime->lt($finAnterior)) {
                return back()->withInput()->with('error', 'La hora de inicio no puede ser antes de que termine la actividad anterior (Termina a las ' . $finAnterior->format('H:i') . ').');
            }
        }

        // Si pasa todas las validaciones, se crea
        Activity::create($request->all());

        return redirect()->route('admin.activities.index')
            ->with('success', 'Actividad programada correctamente.');
    }

    // Formulario para editar
    public function edit(Activity $activity)
    {
        $speakers = Speaker::orderBy('name')->get();
        $scheduleDays = ScheduleDay::orderBy('date', 'asc')->get();
        
        return view('admin.activities.edit', compact('activity', 'speakers', 'scheduleDays'));
    }

    // Actualizar actividad (Mismas reglas que el store)
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

        $dayId = $request->schedule_day_id;
        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);

        // Regla 1: Horario
        if ($startTime->lt(Carbon::parse('11:00')) || $endTime->gt(Carbon::parse('14:00'))) {
            return back()->withInput()->with('error', 'Las actividades solo pueden programarse entre las 11:00 AM y las 2:00 PM.');
        }

        // Regla 3: Choque de horario (excluyendo la actividad actual)
        $ultimaActividad = Activity::where('schedule_day_id', $dayId)
            ->where('id', '!=', $activity->id)
            ->orderBy('end_time', 'desc')
            ->first();

        if ($ultimaActividad) {
            $finAnterior = Carbon::parse($ultimaActividad->end_time);
            if ($startTime->lt($finAnterior)) {
                return back()->withInput()->with('error', 'La hora de inicio choca con la actividad anterior que termina a las ' . $finAnterior->format('H:i') . '.');
            }
        }

        $activity->update($request->all());

        return redirect()->route('admin.activities.index')
            ->with('success', 'Actividad actualizada correctamente.');
    }

    // Eliminar actividad
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('admin.activities.index')
            ->with('success', 'Actividad eliminada del cronograma.');
    }
}