<?php

namespace App\Http\Controllers;

use App\Models\Organiser;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Attendize\Utils;
use App\Models\Account;
use App\Models\User;
use App\Models\Event;
use App\Models\PaymentGateway;
use App\Models\AccountPaymentGateway;
use App\Models\Vendedor;
use App\Models\Asignacion;
use Hash;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Mail;
use Services\Captcha\Factory;
use Illuminate\Support\Facades\Lang;

class OrganiserDashboardController extends MyBaseController
{
    /**
     * Show the organiser dashboard
     *
     * @param $organiser_id
     * @return mixed
     */
    public function showDashboard($organiser_id)
    {
        $organiser = Organiser::scope()->findOrFail($organiser_id);
        $upcoming_events = $organiser->events()
		->where('end_date', '>=', Carbon::now())
		->where(function($query) {
        		$query->where('location_address_line_1', '')
              		->orWhereNull('location_address_line_1')
              		->orWhere('location_address_line_1', '0');
   		 })
		->get();
        $calendar_events = [];

        /* Prepare JSON array for events for use in the dashboard calendar */

foreach ($organiser->events as $event) {
    // Verifica las condiciones para location_address_line_1
    if (
        ($event->location_address_line_1 === '' ||
         is_null($event->location_address_line_1) ||
         $event->location_address_line_1 === '0')
    ) {
        $calendar_events[] = [
            'title' => $event->title,
            'start' => $event->start_date->toIso8601String(),
            'end'   => $event->end_date->toIso8601String(),
            'url'   => route('showEventDashboard', [
                'event_id' => $event->id
            ]),
            'color' => '#4E558F'
        ];
    }
}
        /*foreach ($organiser->events as $event) {
            $calendar_events[] = [
                'title' => $event->title,
                'start' => $event->start_date->toIso8601String(),
                'end'   => $event->end_date->toIso8601String(),
                'url'   => route('showEventDashboard', [
                    'event_id' => $event->id
                ]),
                'color' => '#4E558F'
            ];
        }*/

        $data = [
            'organiser'       => $organiser,
            'upcoming_events' => $upcoming_events,
            'calendar_events' => json_encode($calendar_events),
        ];

        return view('ManageOrganiser.Dashboard', $data);
    }
	
	
	public function showOPerfiles($organiser_id)
    {
	$organizadores = User::where('id', '!=', 1)
                     ->orderBy('id', 'desc')
                     ->get();
	//$organizadores = User::all();
    $organiser = Organiser::scope()->findOrFail($organiser_id);
	
	
        $data = [
            'organiser'       => $organiser,
			'clientes' => 	$organizadores,
			'organiser_id'       => $organiser_id,
        ];
        return view('ManageOrganiser.productoras.show', $data);
    }
	
	
	public function showOObras($organiser_id)
    {
	//$obras = Event::where('location_address_line_1', '!=', 1)->get();
	$obras = Event::where('location_address_line_1', '!=', 1)
              ->orWhereNull('location_address_line_1')
              ->orWhere('location_address_line_1', '')
              ->get();
	$obras->load('organiser');  // Cargar la relación de organizador
	
	/*$evento = Event::find(14);
	$evento->location_address_line_1 = 1;  // Actualizamos el campo
    $evento->save();*/
	/*$evento = Event::find(15);
	$evento->is_live = 0;  // Actualizamos el campo
    $evento->save();*/

	// Modificar la colección de obras para agregar el nombre del organizador
	$obras->transform(function($obra) {
		$obra->organiser_name = $obra->organiser ? $obra->organiser->name : null;
		return $obra;
	});
    $organiser = Organiser::scope()->findOrFail($organiser_id);
	$organizador = Organiser::all();
	
        $data = [
            'organiser'       => $organiser,
			'organizador'       => $organizador,
			'clientes' => 	$obras,
			'organiser_id'       => $organiser_id,
        ];
        return view('ManageOrganiser.productoras.obras', $data);
    }
	
	public function filterByOrganiser($organiserId)
	{
		// Filtrar las obras por organizador
		$obras = Event::where('organiser_id', $organiserId)
              ->where(function($query) {
                  $query->where('location_address_line_1', '!=', 1)
                        ->orWhereNull('location_address_line_1')
                        ->orWhere('location_address_line_1', '');
              })
              ->get();

		// Cargar los organizadores para cada obra
		$obras->load('organiser');

		// Modificar cada obra para agregar el nombre del organizador
		$obras->transform(function($obra) {
			$obra->organiser_name = $obra->organiser ? $obra->organiser->name : null;
			return $obra;
		});

		// Retornar las obras en formato JSON
		return response()->json([
			'clientes' => $obras
		]);
	}
	
	
	public function showSignupPerf($organiser_id)
    {
    $organiser = Organiser::scope()->findOrFail($organiser_id);
	$organizadores = Organiser::all();
	
	$organizadores = Organiser::orderBy('id', 'desc')       // Primero por ID en orden descendente
                       ->orderBy('created_at', 'desc') // Luego por fecha en orden descendente
                       ->get();
        $data = [
            'organiser'       => $organiser,
			'clientes' => 	$organizadores,
			'organiser_id'       => $organiser_id,
        ];
        return view('ManageOrganiser.productoras.crear', $data);
    }
	
	
	public function Crearperfil(Request $request,$organiser_id)
    {
        $is_attendize = Utils::isAttendizeCloud();
        $this->validate($request, [
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|confirmed',
            'first_name'   => 'required',
            'last_name'   => 'required'
        ]);



        $account_data = $request->only(['email', 'first_name', 'last_name']);
        $account_data['currency_id'] = config('attendize.default_currency');
        $account_data['timezone_id'] = config('attendize.default_timezone');
        $account = Account::create($account_data);

        $user = new User();
        $user_data = $request->only(['email', 'first_name', 'last_name']);
        $user_data['password'] = Hash::make($request->get('password'));
        $user_data['account_id'] = $account->id;
        $user_data['is_parent'] = 1;
        $user_data['is_registered'] = 1;
        $user = User::create($user_data);
		
		$payment_gateway_data = [
            'payment_gateway_id' => PaymentGateway::getDefaultPaymentGatewayId(),
            'account_id' => $account->id,
            'config' => '{"apiKey":"","publishableKey":""}',
        ];
        $paymentGateway = AccountPaymentGateway::create($payment_gateway_data);
		
		
		
		$organiser = new Organiser();

		$organiser->account_id  = $account->id;;
        $organiser->name = $request->get('first_name');
        $organiser->about = "";
        $organiser->email = $request->get('email');
        $organiser->facebook = "";
        $organiser->twitter = "";
        $organiser->confirmation_key = Str::random(15);

        $organiser->tax_name = "";
        $organiser->tax_value = 0;
        $organiser->tax_id = "";
        $organiser->charge_tax = 0;

        $organiser->save();
		

        session()->flash('message', 'Productora creada exitosamente! Ya puedes iniciar sesión.');
		
		//$organizadores = User::where('id', '!=', 1)->get();
		$organizadores = User::where('id', '!=', 1)
                     ->orderBy('id', 'desc')
                     ->get();
		
		$organiser = Organiser::scope()->findOrFail($organiser_id);
		
		
	
		
		$data = [
            'organiser'       => $organiser,
			'clientes' => 	$organizadores,
			'organiser_id'       => $organiser_id,
        ];

        return view('ManageOrganiser.productoras.show', $data);
    }
	
	public function editar($organiser_id, $id)
		{
			// Buscar al organizador
			$organiser = Organiser::findOrFail($organiser_id);
			
			// Buscar al cliente (usuario)
			$cliente = User::findOrFail($id);
			
			$data = [
            'organiser'       => $organiser,
			'cliente' => 	$cliente,
			'organiser_id'       => $organiser_id,
			];

			// Devolver la vista con los datos
			return view('ManageOrganiser.productoras.edit', $data);
		}
		
		
		
		
		
		public function actualizar(Request $request, $organiser_id, $id)
			{
				// Validación de los datos
				$this->validate($request, [
					'email'        => 'required|email|unique:users,email,' . $id,
					'password'     => 'nullable|min:8|confirmed',
					'first_name'   => 'required',
					'last_name'    => 'required'
				]);
				
				$organiser = Organiser::findOrFail($organiser_id);

				// Buscar al cliente (usuario) por su ID
				$cliente = User::findOrFail($id);

				// Actualizar los datos del usuario
				$cliente->first_name = $request->first_name;
				$cliente->last_name = $request->last_name;
				$cliente->email = $request->email;

				// Verificar si se ha proporcionado una nueva contraseña
				if ($request->filled('password')) {
					// Si el campo de la contraseña no está vacío, actualizamos la contraseña
					$cliente->password = Hash::make($request->password);
				}

				// Guardar los cambios
				$cliente->save();

				// Mostrar mensaje de éxito
				session()->flash('message', 'Perfil actualizado correctamente');

				// Redirigir al listado de organizadores o al perfil
				return redirect()->route('perfil.edit', ['organiser_id' => $organiser_id, 'id' => $cliente->id]);
			}
			
			public function eliminar($organiser_id, $id)
			{
				// Buscar al cliente (usuario) por su ID
				$cliente = User::findOrFail($id);

				// Eliminar el cliente
				$cliente->delete();

				// Mostrar mensaje de éxito
				session()->flash('message', 'Perfil eliminado correctamente');

				// Redirigir al listado de organizadores
				return redirect()->route('showOrganiserPerfiles', ['organiser_id' => $organiser_id]);
			}
			
			public function aprobar($organiser_id, $id_evento)
			{
				// Buscar el evento con el id proporcionado
				$evento = Event::find($id_evento);

				// Verificar si el evento existe
				if ($evento) {
					// Actualizar el campo is_live a 1
					$evento->is_live = 1;
					$evento->save();  // Guardar los cambios

					// Mostrar un mensaje de éxito
					return redirect()->route('showOrganiserObras', ['organiser_id' => $organiser_id])
									 ->with('message', 'Obra aprobada exitosamente.');
				} else {
					// Si no se encuentra el evento, mostrar mensaje de error
					return redirect()->route('showOrganiserObras', ['organiser_id' => $organiser_id])
									 ->with('error', 'La Obra no fue encontrado.');
				}
			}
			
public function showORpublicas($organiser_id)
    {
	$organizadores = Vendedor::where('otros1', $organiser_id)->get();
	//$organizadores = User::all();
    $organiser = Organiser::scope()->findOrFail($organiser_id);
	
	
        $data = [
            'organiser'       => $organiser,
			'clientes' => 	$organizadores,
			'organiser_id'       => $organiser_id,
        ];
        return view('ManageOrganiser.productoras.rpublicas', $data);
    }
	
	public function createVend($organiser_id)
    {
		$organiser = Organiser::scope()->findOrFail($organiser_id);
		$data = [
            'organiser'       => $organiser,
			'organiser_id'       => $organiser_id,
        ];

        return view('ManageOrganiser.productoras.crearvendedor', $data);
    }
	
	 public function storeVend(Request $request, $organiser_id)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'url' => 'nullable|string|max:255',
            'otros1' => 'nullable|string|max:255',
            'otros2' => 'nullable|string|max:255',
            'otros3' => 'nullable|string|max:255',
            'otros4' => 'nullable|string|max:255',
        ]);
		
		$validated['url'] = $validated['url'] ?? '0';

        // Crear el nuevo vendedor
        Vendedor::create($validated);

        // Redirigir a la lista de vendedores (puedes cambiar a donde redirigir)
        session()->flash('message', 'Vendedor creado exitosamente!');
		
		//$organizadores = User::where('id', '!=', 1)->get();
		$organizadores = Vendedor::where('otros1', $organiser_id)->get();
		
		$organiser = Organiser::scope()->findOrFail($organiser_id);
		
		
	
		
		$data = [
            'organiser'       => $organiser,
			'clientes' => 	$organizadores,
			'organiser_id'       => $organiser_id,
        ];

        return view('ManageOrganiser.productoras.rpublicas', $data);
    }
	
	 public function actualizarVend(Request $request, $organiser_id, $id)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'url' => 'nullable|string|max:255',
            'otros1' => 'nullable|string|max:255',
            'otros2' => 'nullable|string|max:255',
            'otros3' => 'nullable|string|max:255',
            'otros4' => 'nullable|string|max:255',
        ]);
		
		$validated['url'] = $validated['url'] ?? '0';

        // Buscar el vendedor por su id
        $vendedor = Vendedor::findOrFail($id);

        // Actualizar los datos del vendedor
        $vendedor->update($validated);

        // También podemos asociar el organiser_id si es necesario, por ejemplo:
    
        $vendedor->save();
		
		session()->flash('message', 'Vendedor actualizado correctamente');
		
		$organiser = Organiser::findOrFail($organiser_id);

			$data = [
            'organiser'       => $organiser,
			'vendedor' => 	$vendedor,
			'organiser_id'       => $organiser_id,
			];

        // Redirigir a la página de edición con un mensaje de éxito
        return view('ManageOrganiser.productoras.editarvendedor', $data);
    }
	
	 public function editarVend($organiser_id, $id)
    {
        // Buscar el vendedor por su id
        $vendedor = Vendedor::findOrFail($id);

		// Buscar al organizador
		$organiser = Organiser::findOrFail($organiser_id);

			$data = [
            'organiser'       => $organiser,
			'vendedor' => 	$vendedor,
			'organiser_id'       => $organiser_id,
			];

			// Devolver la vista con los datos
			return view('ManageOrganiser.productoras.editarvendedor', $data);
    }
	
	public function eliminarVend($organiser_id, $id)
			{
				// Buscar al cliente (usuario) por su ID
				$vendedor = Vendedor::findOrFail($id);

				// Eliminar el cliente
				$vendedor->delete();

				// Mostrar mensaje de éxito
				session()->flash('message', 'Vendedor eliminado correctamente');

				// Redirigir al listado de organizadores
				return redirect()->route('showOrganiserRpublicas', ['organiser_id' => $organiser_id]);
			}
			
	public function obrasVend($organiser_id, $id)
    {
        // Buscar el vendedor por su id
        $vendedor = Vendedor::findOrFail($id);
		
		$obras = Event::where(function($query) use ($organiser_id) {
                $query->where('location_address_line_1', '!=', 1)
                      ->orWhereNull('location_address_line_1')
                      ->orWhere('location_address_line_1', '');
            })
            ->where('organiser_id', '=', $organiser_id)
            ->get();
		
		$asignaciones = Asignacion::where('idvendedor', $id)->get();
		
		foreach ($obras as $obra) {
			// Buscar si existe una asignación para esta obra
			$asignacion = $asignaciones->firstWhere('idobra', $obra->id);
			
			// Si se encuentra una asignación para esta obra, verificar el estado
			if ($asignacion) {
				$obra->asignacion = 1;
				$obra->url = $asignacion->otro1;
			} else {
				$obra->asignacion = 0; // Si no tiene asignación, asignar 0
				$obra->url = null;
			}
		}

		// Buscar al organizador
		$organiser = Organiser::findOrFail($organiser_id);

			$data = [
            'organiser'       => $organiser,
			'clientes' => 	$obras,
			'vendedor' => 	$vendedor,
			'organiser_id'       => $organiser_id,
			];

			// Devolver la vista con los datos
			return view('ManageOrganiser.productoras.obrasvendedor', $data);
    }
	
	
	public function asignarobrasVend($organiser_id, $id, $obra_id)
		{
			// Verificar que los datos son correctos
			$vendedor = Vendedor::findOrFail($id);  // Buscar al vendedor por su ID
			$obra = Event::findOrFail($obra_id);     // Buscar la obra por su ID
			$organiser = Organiser::findOrFail($organiser_id);  // Buscar al organizador por su ID
			
			$urlCompleta = url()->current(); // Esto te da la URL completa, por ejemplo: 'https://www.example.com/some/path'

			// Usamos parse_url() para extraer el dominio
			$dominio = parse_url($urlCompleta, PHP_URL_HOST); 

			// Crear una nueva asignación
			$asignacion = new Asignacion();
			$asignacion->idobra = $obra->id;
			$slug = strtolower(str_replace(' ', '-', $obra->title));
			$asignacion->idvendedor = $vendedor->id;
			$asignacion->otro1 = "https://".$dominio."/obras/".$slug."/".$vendedor->id; // Guardar la URL actual
			$asignacion->otro2 = $obra->nombre;     // Guardar el nombre de la obra
			$asignacion->save();                    // Guardar la asignación
			
			// Redirigir o devolver algún tipo de mensaje
			return redirect()->route('vendedor.obras', ['organiser_id' => $organiser_id, 'id' => $id])
                     ->with('success', 'Asignación realizada con éxito');
		}
		
		public function eliminarobrasVend($organiser_id, $id, $obra_id)
			{
				// Buscar al cliente (usuario) por su ID
				//$asignacion = Asignacion::where('id', $asignacion_id)->first();
				$asignacion = Asignacion::where('idvendedor', $id)
                            ->where('idobra', $obra_id)
                            ->first();
							
				if ($asignacion) {
				$asignacion->delete();
				}

				// Eliminar el cliente
				//$vendedor->delete();

				// Mostrar mensaje de éxito
				session()->flash('message', 'Asignación eliminado correctamente');

				// Redirigir al listado de organizadores
				
				return redirect()->route('vendedor.obras', ['organiser_id' => $organiser_id, 'id' => $id]);
			}
	
	
}
