<?php

namespace App\Http\Controllers;
use App\Models\Organiser;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ClienteController extends Controller
{
    /**
     * Show the list of clientes
     *
     * @param int $organiser_id
     * @return \Illuminate\Http\Response
     */
    public function index($organiser_id)
    {
	$organiser = Organiser::scope()->findOrFail($organiser_id);
        // Obtener todos los clientes
        $clientes = Cliente::all();
	
	$clientes = Cliente::orderBy('id', 'desc')       // Primero por ID en orden descendente
                       ->orderBy('created_at', 'desc') // Luego por fecha en orden descendente
                       ->get();

        // Preparar los datos para la vista
        $data = [
	    'organiser'       => $organiser,
            'clientes' => $clientes,
            'organiser_id' => $organiser_id, // Pasar el parametro a la vista
        ];

        // Devolver la vista con los datos desde la ubicación correcta
        return view('ManageOrganiser.clientes.index', $data);
    }

    /**
     * Show the details of a specific cliente
     *
     * @param int $organiser_id
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($organiser_id, $id)
    {
        // Encontrar un cliente por ID
	$organiser = Organiser::scope()->findOrFail($organiser_id);
        $cliente = Cliente::findOrFail($id);

        // Preparar los datos para la vista
        $data = [
	    'organiser'       => $organiser,
            'cliente' => $cliente,
            'organiser_id' => $organiser_id, // Pasar el parametro a la vista
        ];

        // Devolver la vista con los datos desde la ubicación correcta
        return view('ManageOrganiser.clientes.show', $data);
    }

    /**
     * Show the form for creating a new cliente
     *
     * @param int $organiser_id
     * @return \Illuminate\Http\Response
     */
    public function create($organiser_id)
    {
	$organiser = Organiser::scope()->findOrFail($organiser_id);
        // Preparar los datos para la vista
        $data = [
	    'organiser'       => $organiser,
            'organiser_id' => $organiser_id,
        ];

        // Devolver la vista con los datos desde la ubicación correcta
        return view('ManageOrganiser.clientes.create', $data);
    }

    /**
     * Store a newly created cliente in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param int $organiser_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $organiser_id)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'nombres'    => 'required|string|max:255',
            'telefono'   => 'required|string|max:15',
            'email'      => 'required|email|unique:clientes,email',
            'contraseña' => 'required|string|min:8',
	     'dni'   => 'required|string|max:255',
        ]);

	$validated['contraseña'] = Hash::make($validated['contraseña']);

        // Crear el nuevo cliente
        Cliente::create($validated);

        // Redirigir a la lista de clientes con un mensaje de éxito
        return redirect()->route('clientes.index', ['organiser_id' => $organiser_id])
                         ->with('success', 'Cliente creado con éxito.');
    }

    /**
     * Show the form for editing a specific cliente
     *
     * @param int $organiser_id
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($organiser_id, $id)
    {
	$organiser = Organiser::scope()->findOrFail($organiser_id);
        // Encontrar el cliente por ID
        $cliente = Cliente::findOrFail($id);

        // Preparar los datos para la vista
        $data = [
	    'organiser'       => $organiser,
            'cliente' => $cliente,
            'organiser_id' => $organiser_id, // Pasar el parametro a la vista
        ];

        // Devolver la vista con los datos desde la ubicación correcta
        return view('ManageOrganiser.clientes.edit', $data);
    }

    /**
     * Update the specified cliente in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param int $organiser_id
     * @param int $id
     * @return \Illuminate\Http\Response
     */
  /*  public function update(Request $request, $organiser_id, $id)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'nombres'    => 'required|string|max:255',
            'telefono'   => 'required|string|max:15',
            'email'      => 'required|email|unique:clientes,email,' . $id,
            'contraseña' => 'nullable|string|min:8',
	    'dni'   => 'required|string|max:255',
        ]);

        // Encontrar el cliente por ID
        $cliente = Cliente::findOrFail($id);

        // Actualizar el cliente
        $cliente->update($validated);

        // Redirigir a la lista de clientes con un mensaje de éxito
        return redirect()->route('clientes.index', ['organiser_id' => $organiser_id])
                         ->with('success', 'Cliente actualizado con éxito.');
    }*/

public function update(Request $request, $organiser_id, $id)
{
    $validated = $request->validate([
        'nombres'    => 'required|string|max:255',
        'telefono'   => 'required|string|max:15',
        'email'      => 'required|email|unique:clientes,email,' . $id,
        'contraseña' => 'nullable|string|min:8',
	'dni'   => 'required|string|max:255',
    ]);

    $cliente = Cliente::findOrFail($id);

    if ($request->filled('contraseña')) {
        // Solo hashear la contraseña si se ha proporcionado
        $validated['contraseña'] = Hash::make($validated['contraseña']);
    } else {
        // Eliminar la clave de contraseña si no se ha proporcionado
        unset($validated['contraseña']);
    }

    $cliente->update($validated);

    return redirect()->route('clientes.index', ['organiser_id' => $organiser_id])
                     ->with('success', 'Cliente actualizado con éxito.');
}

    /**
     * Remove the specified cliente from storage
     *
     * @param int $organiser_id
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($organiser_id, $id)
    {
        // Encontrar el cliente por ID
        $cliente = Cliente::findOrFail($id);

        // Eliminar el cliente
        $cliente->delete();

        // Redirigir a la lista de clientes con un mensaje de éxito
        return redirect()->route('clientes.index', ['organiser_id' => $organiser_id])
                         ->with('success', 'Cliente eliminado con éxito.');
    }


public function showRegistrationForm()
    {
	$organiser = Organiser::scope()->find('1');
	$eventos = Event::all()->groupBy('location_address_line_2'); 
	$categorias = Categoria::where('activado', '1')
                           ->orderBy('posicion', 'asc')
                           ->get(); 
	$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    	});
	$data = [
	    'categorias' => $categorias,
            'evento' => $eventos,
			'organiser' => $organiser,
        ];

	
        return view('personas.register', $data);
    }

public function register(Request $request)
{
    $validated = $request->validate([
        'nombres'    => 'required|string|max:255',
        'email'      => 'required|email|unique:clientes,email',
        'contraseña' => 'required|string|min:8|confirmed',
        'telefono'   => 'required|string|max:20',
	'dni'   => 'required|string|max:255',
    ]);

    // Hashear la contraseña
    $validated['contraseña'] = Hash::make($validated['contraseña']);

    // Crear el nuevo cliente
    Cliente::create($validated);

    // Iniciar sesión para el guard de clientes
    Auth::guard('clientes')->attempt($request->only('email', 'contraseña'));

    Session::flash('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');

	$eventos = Event::all()->groupBy('location_address_line_2'); 
	$categorias = Categoria::where('activado', '1')
                           ->orderBy('posicion', 'asc')
                           ->get(); 
	$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    	});
	$data = [
	    'categorias' => $categorias,
        'evento' => $eventos,
        ];

    return redirect()->route('personas.login', $data);
}

 public function showLoginForm()
    {
	$organiser = Organiser::scope()->find('1');
	$eventos = Event::all()->groupBy('location_address_line_2'); 
	$categorias = Categoria::where('activado', '1')
                           ->orderBy('posicion', 'asc')
                           ->get(); 
	$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    	});
	$data = [
	    'categorias' => $categorias,
            'evento' => $eventos,
			'organiser' => $organiser,
        ];
        return view('personas.login', $data);
    }

public function login(Request $request)
{
     $validated = $request->validate([
        'email'      => 'required|email',
        'contraseña' => 'required|string',
    ]);

    // Buscar el cliente por email
    $cliente = Cliente::where('email', $validated['email'])->first();

    if ($cliente && Hash::check($validated['contraseña'], $cliente->contraseña)) {
        // Iniciar sesión para el guard de clientes
        Auth::guard('clientes')->login($cliente);
		
		// Verificar si la URL personalizada está guardada en la sesión
       // $redirectUrl = session('url_intended_custom', route('personas.dashboard'));

        // Eliminar la variable de sesión después de usarla
        //session()->forget('url_intended_custom');
		// Redirigir a la URL almacenada o a la página de dashboard si no existe
       // return redirect()->to($redirectUrl);
        return redirect()->route('personas.dashboard');
		//$redirectUrl = session('url.intended', route('personas.dashboard')); // Usa 'personas.dashboard' si no hay URL almacenada
        //return redirect()->intended(url()->previous());
	  // return redirect()->intended(route('personas.dashboard'));
    }

    // Si las credenciales no son correctas, redirigir con errores
    return back()->withErrors([
        'email' => 'Las credenciales proporcionadas son incorrectas.',
    ]);
}


public function logout()
    {
       Auth::guard('clientes')->logout();
        return redirect()->route('personas.login.form');
    }

}