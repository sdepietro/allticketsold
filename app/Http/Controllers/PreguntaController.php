<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organiser;
use App\Models\Pregunta;

class PreguntaController extends Controller
{
    public function index($organiser_id)
    {
		
		$organiser = Organiser::scope()->findOrFail($organiser_id);
        $preguntas = Pregunta::orderBy('created_at', 'desc')->get();
		$data = [
	    'organiser'       => $organiser,
             'preguntas' => $preguntas,
            'organiser_id' => $organiser_id,
			// Pasar el parametro a la vista
        ];
        return view('ManageOrganiser.preguntas.index', $data);
    }

    public function create($organiser_id)
    {
		$organiser = Organiser::scope()->findOrFail($organiser_id);
		$data = [
	    'organiser'       => $organiser,
            'organiser_id' => $organiser_id, // Pasar el parametro a la vista
        ];
        return view('ManageOrganiser.preguntas.create', $data);
    }

    public function store(Request $request, $organiser_id)
    {
        $request->validate([
            'pregunta' => 'required|string|max:255',
            'respuesta' => 'required|string',
            'activado' => 'nullable|boolean',
        ]);

        Pregunta::create($request->all());

        return redirect()->route('preguntas.index', $organiser_id)->with('success', 'Pregunta creada con éxito.');
    }

    public function show($organiser_id,$id)
    {
        $pregunta = Pregunta::findOrFail($id);
		$organiser = Organiser::scope()->findOrFail($organiser_id);
		$data = [
	    'organiser'       => $organiser,
            'pregunta' => $pregunta,
            'organiser_id' => $organiser_id, // Pasar el parametro a la vista
        ];
        return view('ManageOrganiser.preguntas.show', $data);
    }

    public function edit($organiser_id,$id)
    {
        $pregunta = Pregunta::findOrFail($id);
		$organiser = Organiser::scope()->findOrFail($organiser_id);
		$data = [
	    'organiser'       => $organiser,
             'pregunta' => $pregunta,
            'organiser_id' => $organiser_id,
			// Pasar el parametro a la vista
        ];
        return view('ManageOrganiser.preguntas.edit', $data);
    }

    public function update(Request $request, $organiser_id, $id)
    {
        $request->validate([
            'pregunta' => 'required|string|max:255',
            'respuesta' => 'required|string',
            'activado' => 'nullable|boolean',
        ]);

        $pregunta = Pregunta::findOrFail($id);
        $pregunta->update($request->all());
		
		

        return redirect()->route('preguntas.index', $organiser_id)->with('success', 'Pregunta actualizada con éxito.');
    }

    public function destroy($organiser_id,$id)
    {
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->delete();

        return redirect()->route('preguntas.index', $organiser_id)->with('success', 'Pregunta eliminada con éxito.');
    }
}
