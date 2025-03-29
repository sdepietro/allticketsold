<?php

namespace App\Http\Controllers;
use App\Models\Banner;
use App\Models\Organiser;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;

class BannerController extends Controller
{
   public function index($organiser_id)
    {
        $banners = Banner::all();
	$organiser = Organiser::scope()->findOrFail($organiser_id);
	$data = [
	    'organiser'       => $organiser,
            'banners' => $banners,
            'organiser_id' => $organiser_id, // Pasar el parametro a la vista
        ];
        return view('ManageOrganiser.banners.index', $data);
    }

    public function create($organiser_id)
    {
	@ini_set('upload_max_filesize', '64M');
    	@ini_set('post_max_size', '64M');
    	@ini_set('max_execution_time', '300');
	$organiser = Organiser::scope()->findOrFail($organiser_id);
	$data = [
	    'organiser'       => $organiser,
            'organiser_id' => $organiser_id, // Pasar el parametro a la vista
        ];
        return view('ManageOrganiser.banners.create', $data);
    }

   /* public function store(Request $request, $organiser_id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $imagePath = $request->file('imagen')->store('images', 'public');

        Banner::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $imagePath,
        ]);

        return redirect()->route('banners.index', $organiser_id)->with('success', 'Banner creado exitosamente.');
    }*/

public function store(Request $request, $organiser_id)
{
    try {
        // Validar los datos del request
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Verificar si el archivo se ha subido correctamente
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();

            // Debugging information
            \Log::info('Mime Type: ' . $mimeType);
            \Log::info('File Extension: ' . $extension);
            \Log::info('File Size: ' . $size);

            $imagePath = $file->store('images', 'public');

            Banner::create([
                'titulo' => $request->input('titulo'),
                'descripcion' => $request->input('descripcion'),
                'imagen' => $imagePath,
            ]);

            return redirect()->route('banners.index', $organiser_id)
                             ->with('success', 'Banner creado exitosamente.');
        } else {
            return redirect()->back()
                             ->withErrors(['imagen' => 'No se ha enviado ninguna imagen.'])
                             ->withInput();
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
                         ->withErrors($e->validator)
                         ->withInput();
    } catch (\Exception $e) {
        return redirect()->back()
                         ->withErrors(['error' => 'Hubo un problema al procesar tu solicitud. Por favor, intenta de nuevo.'])
                         ->withInput();
    }
}
	
	public function update(Request $request, $organiser_id, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $banner = Banner::findOrFail($id);

        // Update the banner fields
        $banner->titulo = $request->titulo;
        $banner->descripcion = $request->descripcion;

        // Check if a new image was uploaded
        if ($request->hasFile('imagen')) {
            // Delete old image if exists
            if ($banner->imagen) {
                \Storage::disk('public')->delete($banner->imagen);
            }

            $imagePath = $request->file('imagen')->store('images', 'public');
            $banner->imagen = $imagePath;
        }

        $banner->save();

        return redirect()->route('banners.index', $organiser_id)->with('success', 'Banner actualizado exitosamente.');
    }
	
	public function edit($organiser_id, $id)
    {
        $banner = Banner::findOrFail($id);
        $organiser = Organiser::scope()->findOrFail($organiser_id);
        $data = [
            'banner' => $banner,
            'organiser' => $organiser,
            'organiser_id' => $organiser_id,
        ];
        return view('ManageOrganiser.banners.edit', $data);
    }

    public function destroy($organiser_id, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('banners.index', $organiser_id)->with('success', 'Banner eliminado exitosamente.');
    }
}
