<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleDay;
use Illuminate\Http\Request;

class ScheduleDayController extends Controller
{
    public function index()
    {
        $days = ScheduleDay::orderBy('date', 'asc')->get();
        // Cambiamos la ruta a la vista correcta
        return view('admin.activities.index', ['scheduleDays' => $days, 'speakers' => \App\Models\Speaker::all()]);
    }

    public function create()
    {
        // No necesitamos vista aquí porque usamos modales
        return redirect()->route('admin.activities.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:schedule_days,date',
            'title' => 'required|string|max:255'
        ]);

        ScheduleDay::create($request->all());

        // CORRECCIÓN AQUÍ: Redireccionar al index de actividades
        return redirect()->route('admin.activities.index')
            ->with('success', 'Día agregado al cronograma.');
    }

    public function show(ScheduleDay $scheduleDay)
    {
        return redirect()->route('admin.activities.index');
    }

    public function edit(ScheduleDay $scheduleDay)
    {
        return redirect()->route('admin.activities.index');
    }

    public function update(Request $request, ScheduleDay $scheduleDay)
    {
        $request->validate([
            'date' => 'required|date|unique:schedule_days,date,' . $scheduleDay->id,
            'title' => 'required|string|max:255'
        ]);
        
        $scheduleDay->update($request->all());
        return redirect()->route('admin.activities.index')->with('success', 'Día actualizado.');
    }

    public function destroy(ScheduleDay $scheduleDay)
    {
        $scheduleDay->delete();
        // CORRECCIÓN AQUÍ: Redireccionar al index de actividades
        return redirect()->route('admin.activities.index')
            ->with('success', 'Día eliminado correctamente.');
    }
}