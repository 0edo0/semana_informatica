<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        // Filtro por búsqueda (DNI, nombre, email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('dni', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por semestre
        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }

        $students = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|digits:8|unique:students,dni',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'semester' => 'required|in:2,4,6',
            'career' => 'required|string|max:255',
        ]);

        Student::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Estudiante registrado correctamente.');
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'dni' => ['required', 'digits:8', Rule::unique('students')->ignore($student->id)],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('students')->ignore($student->id)],
            'phone' => 'nullable|string|max:20',
            'semester' => 'required|in:2,4,6',
            'career' => 'required|string|max:255',
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Datos del estudiante actualizados.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')
            ->with('success', 'Estudiante eliminado del sistema.');
    }
}