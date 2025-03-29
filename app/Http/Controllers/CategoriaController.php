<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organiser;
use App\Models\Categoria; // Asumiendo que tienes un modelo Categoria
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    public function index($organiser_id)
    {
	$organiser = Organiser::scope()->findOrFail($organiser_id);
        // Obtener todas las categorías asociadas con el organizador::orderBy('posicion', 'asc')->get()
        $categorias = Categoria::orderBy('posicion', 'asc')->get();
	$data = [
	    'organiser'       => $organiser,
            'categorias' => $categorias,
            'organiser_id' => $organiser_id, // Pasar el parametro a la vista
        ];
        return view('ManageOrganiser.categorias.index', $data);
    }

    public function create($organiser_id)
    {
	$organiser = Organiser::scope()->findOrFail($organiser_id);
        // Preparar los datos para la vista
        $data = [
	    'organiser'       => $organiser,
            'organiser_id' => $organiser_id,
        ];
        return view('ManageOrganiser.categorias.create', $data);
    }

    public function store(Request $request, $organiser_id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'posicion' => 'nullable|integer',
			'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'activado' => 'nullable|string|max:255',
        ]);
		
		$imagenPath = null;
    if ($request->hasFile('imagen')) {
        $image = $request->file('imagen');
        $imagenPath = $image->store('images', 'public'); // Guarda la imagen en el directorio public/images
    }

        Categoria::create([
            'organiser_id' => $organiser_id,
            'descripcion' => $request->descripcion,
            'posicion' => $request->posicion,
			'imagen' => $imagenPath,
			'activado' => $request->activado,
        ]);

        return redirect()->route('categorias.index', $organiser_id)
                         ->with('success', 'Categoría creada con éxito.');
    }

    public function show($organiser_id, $id)
    {
	$organiser = Organiser::scope()->findOrFail($organiser_id);
        $categoria = Categoria::where('organiser_id', $organiser_id)->findOrFail($id);
	$data = [
	    'organiser'       => $organiser,
            'categorias' => $categoria,
            'organiser_id' => $organiser_id, // Pasar el parametro a la vista
        ];
        return view('ManageOrganiser.categorias.show', $data);
    }

    public function edit($organiser_id, $id)
    {
	$organiser = Organiser::scope()->findOrFail($organiser_id);
        $categoria = Categoria::where('organiser_id', $organiser_id)->findOrFail($id);
	$data = [
	    'organiser'       => $organiser,
            'categorias' => $categoria,
            'organiser_id' => $organiser_id,
			// Pasar el parametro a la vista
        ];
        return view('ManageOrganiser.categorias.edit', $data);
    }

    public function update(Request $request, $organiser_id, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
			'posicion' => 'nullable|integer',
			'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'activado' => 'nullable|string|max:255',
            // Agrega otras validaciones según sea necesario
        ]);

        $categoria = Categoria::where('organiser_id', $organiser_id)->findOrFail($id);
		
		$imagenPath = $categoria->imagen; // Mantén el antiguo si no se sube una nueva
    if ($request->hasFile('imagen')) {
        if ($imagenPath) {
            // Elimina la imagen antigua si existe
            Storage::disk('public')->delete($imagenPath);
        }
        $image = $request->file('imagen');
        $imagenPath = $image->store('images', 'public'); // Guarda la nueva imagen
    }
	
		
        $categoria->update([
            'descripcion' => $request->descripcion,
			'posicion' => $request->posicion,
			'imagen' => $imagenPath,
			'activado' => $request->activado,
            // Otros campos según sea necesario
        ]);

        return redirect()->route('categorias.index', $organiser_id)
                         ->with('success', 'Categoría actualizada con éxito.');
    }

    public function destroy($organiser_id, $id)
    {
        $categoria = Categoria::where('organiser_id', $organiser_id)->findOrFail($id);
        $categoria->delete();

        return redirect()->route('categorias.index', $organiser_id)
                         ->with('success', 'Categoría eliminada con éxito.');
    }
}
