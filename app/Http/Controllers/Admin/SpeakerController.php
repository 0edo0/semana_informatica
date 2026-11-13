<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class SpeakerController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        // Inicializamos Cloudinary con la URL del .env
        $this->cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
    }

    public function index()
    {
        $speakers = Speaker::latest()->paginate(10);
        return view('admin.speakers.index', compact('speakers'));
    }

    public function create()
    {
        return view('admin.speakers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            // Subir a Cloudinary usando el paquete oficial
            $result = $this->cloudinary->uploadApi()->upload(
                $request->file('photo')->getRealPath(),
                ['folder' => 'devfest_ponentes']
            );
            
            // Guardamos la URL segura (https)
            $data['photo_path'] = $result['secure_url'];
        }

        Speaker::create($data);

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Ponente registrado exitosamente.');
    }

    public function edit(Speaker $speaker)
    {
        return view('admin.speakers.edit', compact('speaker'));
    }

    public function update(Request $request, Speaker $speaker)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $result = $this->cloudinary->uploadApi()->upload(
                $request->file('photo')->getRealPath(),
                ['folder' => 'devfest_ponentes']
            );
            $data['photo_path'] = $result['secure_url'];
        }

        $speaker->update($data);

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Ponente actualizado correctamente.');
    }

    public function destroy(Speaker $speaker)
    {
        $speaker->delete();
        return redirect()->route('admin.speakers.index')
            ->with('success', 'Ponente eliminado correctamente.');
    }
}