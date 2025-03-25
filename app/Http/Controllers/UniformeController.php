<?php

namespace App\Http\Controllers;

use App\Models\Uniforme;
use App\Models\UniformeFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UniformeController extends Controller
{
    public function create()
    {
        return view('uniforme_form');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'categoria' => 'required|in:Industriales,Médicos,Escolares,Corporativos',
                'tipo' => 'required|string|max:255',
                'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Soporte para múltiples fotos
            ]);

            $uniforme = new Uniforme();
            $uniforme->nombre = $validatedData['nombre'];
            $uniforme->descripcion = $validatedData['descripcion'];
            $uniforme->categoria = $validatedData['categoria'];
            $uniforme->tipo = $validatedData['tipo'];
            $uniforme->save();

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) { // Procesa cada foto subida
                    $path = $foto->store('uploads', 'public');
                    $uniforme->fotos()->create(['foto_path' => $path]);
                }
            }

            return redirect()->route('uniformes.index')->with('success', 'Uniforme creado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar el uniforme: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al guardar el uniforme: ' . $e->getMessage())->withInput();
        }
    }

    public function index()
    {
        $uniformes = Uniforme::with('fotos')->get();
        return view('uniformes_list', compact('uniformes'));
    }

    public function edit($id)
    {
        $uniforme = Uniforme::with('fotos')->findOrFail($id);
        return view('uniforme_form', compact('uniforme'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'sometimes|string|max:255',
                'descripcion' => 'sometimes|string',
                'categoria' => 'sometimes|in:Industriales,Médicos,Escolares,Corporativos',
                'tipo' => 'sometimes|string|max:255',
                'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Soporte para múltiples fotos
            ]);

            $uniforme = Uniforme::findOrFail($id);

            if ($request->has('nombre')) $uniforme->nombre = $request->input('nombre');
            if ($request->has('descripcion')) $uniforme->descripcion = $request->input('descripcion');
            if ($request->has('categoria')) $uniforme->categoria = $request->input('categoria');
            if ($request->has('tipo')) $uniforme->tipo = $request->input('tipo');
            $uniforme->save();

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) { // Procesa cada nueva foto subida
                    $path = $foto->store('uploads', 'public');
                    $uniforme->fotos()->create(['foto_path' => $path]);
                }
            }

            return redirect()->route('uniformes.index')->with('success', 'Uniforme actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el uniforme: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el uniforme: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $uniforme = Uniforme::findOrFail($id);
            foreach ($uniforme->fotos as $foto) {
                Storage::disk('public')->delete($foto->foto_path);
                $foto->delete();
            }
            $uniforme->delete();
            return redirect()->route('uniformes.index')->with('success', 'Uniforme eliminado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar el uniforme: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el uniforme: ' . $e->getMessage());
        }
    }

    public function destroyPhoto($fotoId)
    {
        try {
            $foto = UniformeFoto::findOrFail($fotoId);
            Storage::disk('public')->delete($foto->foto_path);
            $foto->delete();
            return redirect()->back()->with('success', 'Foto eliminada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar la foto: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar la foto: ' . $e->getMessage());
        }
    }
}