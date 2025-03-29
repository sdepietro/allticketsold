<?php

namespace App\Http\Controllers;
use App\Models\Teatro2;
use App\Models\Organiser;

use Illuminate\Http\Request;

class TeatroController extends Controller
{
   public function index($organiser_id)
    {
        $organiser = Organiser::findOrFail($organiser_id);
        $teatros = Teatro2::all();
        $data = [
            'organiser'   => $organiser,
            'teatros'     => $teatros,
            'organiser_id' => $organiser_id,
        ];
        return view('ManageOrganiser.teatros.index', $data);
    }

    public function create($organiser_id)
    {
        $organiser = Organiser::findOrFail($organiser_id);
        return view('ManageOrganiser.teatros.create', compact('organiser_id', 'organiser'));
    }

    public function store(Request $request, $organiser_id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'coordenadas' => 'required|string|max:255',
            'imagen' => 'nullable|image',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('images', 'public');
        }

         Teatro2::create($data);

        return redirect()->route('teatros.index', ['organiser_id' => $organiser_id])
                         ->with('success', 'Teatro creado con éxito.');
    }

    public function edit($organiser_id, $id)
    {
        $organiser = Organiser::findOrFail($organiser_id);
        $teatro = Teatro2::findOrFail($id);
		$organiser = Organiser::scope()->findOrFail($organiser_id);
        $data = [
            'teatro' => $teatro,
            'organiser' => $organiser,
            'organiser_id' => $organiser_id,
        ];
        return view('ManageOrganiser.teatros.edit', $data);
    }

    public function update(Request $request, $organiser_id, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'coordenadas' => 'required|string|max:255',
            'imagen' => 'nullable|image',
        ]);

        $teatro = Teatro2::findOrFail($id);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('images', 'public');
        }

        $teatro->update($data);

        return redirect()->route('teatros.index', ['organiser_id' => $organiser_id])
                         ->with('success', 'Teatro actualizado con éxito.');
    }

    public function destroy($organiser_id, $id)
    {
        $teatro = Teatro2::findOrFail($id);
        $teatro->delete();

        return redirect()->route('teatros.index', ['organiser_id' => $organiser_id])
                         ->with('success', 'Teatro eliminado con éxito.');
    }
}
