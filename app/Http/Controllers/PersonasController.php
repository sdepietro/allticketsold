<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Event;
use App\Models\Organiser;
use App\Models\Banner;
use App\Models\Cliente;
use App\Models\Teatro2;
use App\Models\Ticket;
use App\Models\Pregunta;
use App\Mail\ClienteCreated;
use File;
use Image;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Decidir\Connector;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Attendize\PaymentUtils;
use App\Jobs\SendOrderNotificationJob;
use App\Jobs\SendOrderConfirmationJob;
use App\Jobs\SendOrderAttendeeTicketJob;
use App\Models\Account;
use App\Models\AccountPaymentGateway;
use App\Models\Affiliate;
use App\Models\Attendee;
use App\Models\EventStats;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentGateway;
use App\Models\QuestionAnswer;
use App\Models\ReservedTickets;

use App\Services\Order as OrderService;
use Services\PaymentGateway\Factory as PaymentGatewayFactory;
use Config;
use Cookie;
use DB;
use Log;
use Mail;
use Omnipay;
use PDF;
use JavaScript;
use PhpSpec\Exception\Exception;
use ZipArchive;
use Services\Captcha\Factory;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use App\Mail\OtpMail;
use App\Models\GlobalEvent;

class PersonasController extends Controller
{
    protected $captchaService;
    public function __construct()
    {
        $captchaConfig = config('attendize.captcha');
        if ($captchaConfig["captcha_is_on"]) {
            $this->captchaService = Factory::create($captchaConfig);
        }
    }

    public function index()
    {


        $global_events = GlobalEvent::where('estado', 'Activo')->get();


        Carbon::setLocale('es');
        $horaActual = Carbon::now();
        // Obtiene todas las categorías
        $organiser = Organiser::scope()->find('1');
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();


        $eventos2 = Event::with('teatro')
            ->where('location_state', 'yes')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')
                    ->where('location_address_line_1', '!=', '1111')
                    ->orWhereNull('location_address_line_1');
            })
            ->where('is_live', 1)    // Filtra los eventos donde location_state es 'yes'
            ->get();
        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos2->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos2->transform(function ($evento2) {
            // Agregar el nombre del organizador
            $evento2->organiser_name = $evento2->organiser ? $evento2->organiser->name : 'Sin organizador';
            return $evento2;
        });

        // Agrupar eventos por 'location_address_line_2'
        $eventos2 = $eventos2->groupBy('location_address_line_2');

        // ->groupBy('location_address_line_2');  // Agrupa los eventos por location_address_line_2
        foreach ($eventos2 as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $global_events = GlobalEvent::where('estado','Activo')->get();
        $global_events_destacado = GlobalEvent::where('estado','Activo')->where('destacado',1)->get();
        $data = [
            'categorias' => $categorias,
            'horaActual' => $horaActual->toDateTimeString(),
            'categorias2' => $categorias2,
          //  'eventos' => $eventos,
            'eventos2' => $eventos2,
            'geventos'=>$global_events,
            'geventos_destacados'=>$global_events_destacado,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Retorna la vista con las categorías
        return view('personas.index', $data);
    }

    public function index2()
    {

        Carbon::setLocale('es');
        $horaActual = Carbon::now();
        // Obtiene todas las categorías
        $organiser = Organiser::scope()->find('1');
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')
                    ->where('location_address_line_1', '!=', '1111')
                    ->orWhereNull('location_address_line_1');
            })
            ->where('is_live', 1)
            ->get();

        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos->transform(function ($evento) {
            // Agregar el nombre del organizador
            $evento->organiser_name = $evento->organiser ? $evento->organiser->name : 'Sin organizador';
            return $evento;
        });

        // Agrupar eventos por 'location_address_line_2'
        $eventos = $eventos->groupBy('location_address_line_2');

        //->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }

        $eventos2 = Event::with('teatro')
            ->where('location_state', 'yes')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')
                    ->where('location_address_line_1', '!=', '1111')
                    ->orWhereNull('location_address_line_1');
            })
            ->where('is_live', 1)    // Filtra los eventos donde location_state es 'yes'
            ->get();
        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos2->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos2->transform(function ($evento2) {
            // Agregar el nombre del organizador
            $evento2->organiser_name = $evento2->organiser ? $evento2->organiser->name : 'Sin organizador';
            return $evento2;
        });

        // Agrupar eventos por 'location_address_line_2'
        $eventos2 = $eventos2->groupBy('location_address_line_2');

        // ->groupBy('location_address_line_2');  // Agrupa los eventos por location_address_line_2
        foreach ($eventos2 as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $data = [
            'categorias' => $categorias,
            'horaActual' => $horaActual->toDateTimeString(),
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'eventos2' => $eventos2,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Retorna la vista con las categorías
        return view('personas.index', $data);
    }

    public function detalles($id)
    {
        Carbon::setLocale('es');
        $organiser = Organiser::scope()->find('1');
        $orders = Order::where('id', $id)->orderBy('created_at', 'asc')->get();

        $event_ids = $orders->pluck('event_id')->unique();
        //$events = Event::whereIn('id', $event_ids)->get()->keyBy('id');
        $events = Event::with('teatro') // Cargamos la relación 'teatro'
            ->whereIn('id', $event_ids)
            ->get()
            ->keyBy('id');

        foreach ($orders as $order) {
            $order->event = $events->get($order->event_id);
        }
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')->where(function ($query) {
            $query->where('location_address_line_1', '<>', '1')
                ->where('location_address_line_1', '!=', '1111')
                ->orWhereNull('location_address_line_1');
        })
            ->where('is_live', 1)
            ->get()
            ->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }

        $eventos2 = Event::with('teatro')
            ->where('location_state', 'yes')->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')
                    ->where('location_address_line_1', '!=', '1111')
                    ->orWhereNull('location_address_line_1');
            })  // Filtra los eventos donde location_state es 'yes'
            ->where('is_live', 1)
            ->get()
            ->groupBy('location_address_line_2');  // Agrupa los eventos por location_address_line_2
        foreach ($eventos2 as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $order2 = Order::scope()->find($id);

        $orderService = new OrderService($order2->amount, $order2->booking_fee, $order2->event);
        $orderService->calculateFinalCosts();


        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'orders'     => $orders,
            'order2' => $order2,
            'orderService' => $orderService,
            'eventos' => $eventos,
            'eventos2' => $eventos2,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Retorna la vista con las categorías
        return view('personas.detalles', $data);
    }

    public function show($id)
    {
        // Obtener el evento por ID
        $event = Event::findOrFail($id);

        // Puedes incluir más datos si es necesario
        // Ejemplo: $categorias = Categoria::all();

        return view('personas.obras', [
            'event' => $event,
            // 'categorias' => $categorias, // Si necesitas pasar otras variables
        ]);
    }

    public function eventoObras($id){
        $global_event = GlobalEvent::findOrFail($id);
        Carbon::setLocale('es');
        $horaActual = Carbon::now();
        // Obtiene todas las categorías
        $organiser = Organiser::scope()->find('1');
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')
                    ->where('location_address_line_1', '!=', '1111')
                    ->orWhereNull('location_address_line_1');
            })
            ->where('is_live', 1)
            ->where('global_event_id', $id)
            ->get();

        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos->transform(function ($evento) {
            // Agregar el nombre del organizador
            $evento->organiser_name = $evento->organiser ? $evento->organiser->name : 'Sin organizador';
            return $evento;
        });

        // Agrupar eventos por 'location_address_line_2'
      //  $eventos = $eventos->groupBy('location_address_line_2');

        //->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento


        $eventos2 = Event::with('teatro')
            ->where('location_state', 'yes')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')
                    ->where('location_address_line_1', '!=', '1111')
                    ->orWhereNull('location_address_line_1');
            })
            ->where('is_live', 1)
            ->where('global_event_id', $id) // Filtra los eventos donde location_state es 'yes'
            ->get();
        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos2->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos2->transform(function ($evento2) {
            // Agregar el nombre del organizador
            $evento2->organiser_name = $evento2->organiser ? $evento2->organiser->name : 'Sin organizador';
            return $evento2;
        });

        // Agrupar eventos por 'location_address_line_2'
        $eventos2 = $eventos2->groupBy('location_address_line_2');

        // ->groupBy('location_address_line_2');  // Agrupa los eventos por location_address_line_2
        foreach ($eventos2 as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $data = [
            'categorias' => $categorias,
            'horaActual' => $horaActual->toDateTimeString(),
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'eventos2' => $eventos2,
            'global_event'=>$global_event,
            'organiser' => $organiser,
        ];



  return view('personas.eventObras', $data);
    }


    public function obras2($id)
    {
        $organiser = Organiser::scope()->find('1');
        $modifiedString = $id;
        $spacesString = str_replace('-', ' ', $modifiedString);
        $id = strtoupper($spacesString);
        $categorias3 = Categoria::all();

        // Obtener el evento por su ID
        $event = Event::where('title', $id)->firstOrFail();

        $eventos = Event::all()->groupBy('location_address_line_2');
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        // Obtener tickets disponibles
        //$tickets = $event->tickets()->paginate();
        $tickets = !empty($q)
            ? $event->tickets()->where('title', 'like', '%' . $q . '%')->paginate()
            : $event->tickets()->paginate();

        $teatro = Teatro2::findOrFail($event->venue_name);

        // Obtener la imagen del evento
        $image_path = $event->images->count() ? $event->images->first()->image_path : null;

        // Obtener la categoría del evento
        $categoriaDelEvento = Categoria::findOrFail($event->location_address_line_2);

        // Formatear fechas
        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);
        $event->formatted_date = $startDate->month == $endDate->month
            ? "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F')
            : "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');

        // Variables para validación de tickets
        $order_expires_time = Carbon::now()->addMinutes(config('attendize.checkout_timeout_after'));
        $ticket_ids = []; // Aquí deberías asignar los IDs de tickets seleccionados
        $total_ticket_quantity = 0;
        $order_total = 0;
        $tickets_data = [];

        // Lógica de validación de tickets
        if (!empty($ticket_ids)) {
            // Limpiar tickets reservados
            ReservedTickets::where('session_id', '=', session()->getId())->delete();

            foreach ($ticket_ids as $ticket_id) {
                // Asigna la cantidad actual de tickets
                $current_ticket_quantity = 1; // Ajusta según tu lógica
                if ($current_ticket_quantity < 1) continue;

                $ticket = Ticket::find($ticket_id);
                $max_per_person = min($ticket->quantity_remaining, $ticket->max_per_person);

                // Validación
                if ($current_ticket_quantity < $ticket->min_per_person || $current_ticket_quantity > $max_per_person) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cantidad de tickets no válida para ' . $ticket->title,
                    ]);
                }

                $order_total += $current_ticket_quantity * $ticket->price;
                $total_ticket_quantity += $current_ticket_quantity;

                $tickets_data[] = [
                    'ticket' => $ticket,
                    'qty' => $current_ticket_quantity,
                    'price' => $current_ticket_quantity * $ticket->price,
                ];

                // Reservar tickets
                $reservedTickets = new ReservedTickets();
                $reservedTickets->ticket_id = $ticket_id;
                $reservedTickets->event_id = $event->id;
                $reservedTickets->quantity_reserved = $current_ticket_quantity;
                $reservedTickets->expires = $order_expires_time;
                $reservedTickets->session_id = session()->getId();
                $reservedTickets->save();
            }
        }

        // Almacenar información del pedido en la sesión
        session()->put('ticket_order_' . $event->id, [
            'event_id' => $event->id,
            'tickets' => $tickets_data,
            'total_ticket_quantity' => $total_ticket_quantity,
            'order_total' => $order_total,
            'expires' => $order_expires_time,
        ]);

        // Convertir tickets a colección
        $tickets_data = collect($tickets_data);

        $data = [
            'categorias' => $categorias,
            'categorias3' => $categorias3,
            'categoria2' => $categoriaDelEvento,
            'evento' => $eventos,
            'teatro' => $teatro,
            'tickets' => $tickets,
            'event' => $event,
            'image_path' => $image_path,
            'organiser' => $organiser,
            'order_total' => $order_total,
            'total_ticket_quantity' => $total_ticket_quantity,
        ];

        return view('personas.obras', $data);
    }


    public function obras($id)
    {
        session()->forget('ticket_order_' . $id);
        $organiser = Organiser::scope()->find('1');
        $modifiedString = $id;
        $spacesString = str_replace('-', ' ', $modifiedString);
        $id = strtoupper($spacesString);
        $categorias3 = Categoria::all();
        // Obtener el evento por su ID
        $eventos = Event::all()->groupBy('location_address_line_2');
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        $event = Event::where('title', $id)->firstOrFail();
        // Cargar la relación del organizador
        $event->load('organiser');

        // Agregar el nombre del organizador al evento
        $event->organiser_name = $event->organiser ? $event->organiser->name : 'Sin organizador';

        $tickets = $event->tickets()->get(); // Usamos get() en lugar de paginate() para obtener todos los tickets

        // Inicializamos un array vacío para almacenar los tickets válidos
        $validTickets = [];

        foreach ($tickets as $ticket) {
            // Verificamos que tanto start_sale_date como end_sale_date no sean null
            if ($ticket->start_sale_date && $ticket->end_sale_date) {
                // Convertir las fechas de inicio y fin de venta a instancias de Carbon
                $startSaleDate = Carbon::parse($ticket->start_sale_date);
                $endSaleDate = Carbon::parse($ticket->end_sale_date);

                // Verificamos si el ticket está dentro del rango de fechas actual
                if ($startSaleDate <= Carbon::now() && $endSaleDate >= Carbon::now()) {
                    // Si está dentro del rango, lo agregamos al array de tickets válidos
                    $validTickets[] = $ticket;
                }
            }
        }
        $tickets = collect($validTickets);

        $teatro = Teatro2::findOrFail($event->venue_name);

        $image_path = null;
        if ($event->images->count()) {
            $image_path = $event->images->first()->image_path;
        }

        $categoriaDelEvento = Categoria::findOrFail($event->location_address_line_2);

        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);

        if ($startDate->month == $endDate->month) {
            $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
        } else {
            $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
        }

        $data = [
            'categorias' => $categorias,
            'categorias3' => $categorias3,
            'categoria2' => $categoriaDelEvento,
            'evento' => $eventos,
            'teatro' => $teatro,
            'tickets' => $tickets,
            'event' => $event,
            'image_path' => $image_path,
            'organiser' => $organiser,
        ];

        return view('personas.obras', $data);
    }

    public function obrasWithDescripcion($id)
    {
        session()->forget('ticket_order_' . $id);
        $organiser = Organiser::scope()->find('1');
        $modifiedString = $id;
        $spacesString = str_replace('-', ' ', $modifiedString);
        $id = strtoupper($spacesString);
        $categorias3 = Categoria::all();
        // Obtener el evento por su ID
        $eventos = Event::all()->groupBy('location_address_line_2');
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        $event = Event::where('title', $id)->firstOrFail();
        // Cargar la relación del organizador
        $event->load('organiser');

        // Agregar el nombre del organizador al evento
        $event->organiser_name = $event->organiser ? $event->organiser->name : 'Sin organizador';

        $tickets = $event->tickets()->get(); // Usamos get() en lugar de paginate() para obtener todos los tickets

        // Inicializamos un array vacío para almacenar los tickets válidos
        $validTickets = [];

        foreach ($tickets as $ticket) {
            // Verificamos que tanto start_sale_date como end_sale_date no sean null
            if ($ticket->start_sale_date && $ticket->end_sale_date) {
                // Convertir las fechas de inicio y fin de venta a instancias de Carbon
                $startSaleDate = Carbon::parse($ticket->start_sale_date);
                $endSaleDate = Carbon::parse($ticket->end_sale_date);

                // Verificamos si el ticket está dentro del rango de fechas actual
                if ($startSaleDate <= Carbon::now() && $endSaleDate >= Carbon::now()) {
                    // Si está dentro del rango, lo agregamos al array de tickets válidos
                    $validTickets[] = $ticket;
                }
            }
        }
        $tickets = collect($validTickets);

        $teatro = Teatro2::findOrFail($event->venue_name);

        $image_path = null;
        if ($event->images->count()) {
            $image_path = $event->images->first()->image_path;
        }

        $categoriaDelEvento = Categoria::findOrFail($event->location_address_line_2);

        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);

        if ($startDate->month == $endDate->month) {
            $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
        } else {
            $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
        }

        $data = [
            'categorias' => $categorias,
            'categorias3' => $categorias3,
            'categoria2' => $categoriaDelEvento,
            'evento' => $eventos,
            'teatro' => $teatro,
            'tickets' => $tickets,
            'event' => $event,
            'image_path' => $image_path,
            'organiser' => $organiser,
        ];

        return view('personas.obrasWithDescripcion', $data);
    }

    public function obras3($id, $idvendedor)
    {
        session()->forget('ticket_order_' . $id);
        $organiser = Organiser::scope()->find('1');
        $modifiedString = $id;
        $spacesString = str_replace('-', ' ', $modifiedString);
        $id = strtoupper($spacesString);
        $categorias3 = Categoria::all();
        // Obtener el evento por su ID
        $eventos = Event::all()->groupBy('location_address_line_2');
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        $event = Event::where('title', $id)->firstOrFail();
        // Cargar la relación del organizador
        $event->load('organiser');

        // Agregar el nombre del organizador al evento
        $event->organiser_name = $event->organiser ? $event->organiser->name : 'Sin organizador';

        $tickets = $event->tickets()->get(); // Usamos get() en lugar de paginate() para obtener todos los tickets

        // Inicializamos un array vacío para almacenar los tickets válidos
        $validTickets = [];

        foreach ($tickets as $ticket) {
            // Verificamos que tanto start_sale_date como end_sale_date no sean null
            if ($ticket->start_sale_date && $ticket->end_sale_date) {
                // Convertir las fechas de inicio y fin de venta a instancias de Carbon
                $startSaleDate = Carbon::parse($ticket->start_sale_date);
                $endSaleDate = Carbon::parse($ticket->end_sale_date);

                // Verificamos si el ticket está dentro del rango de fechas actual
                if ($startSaleDate <= Carbon::now() && $endSaleDate >= Carbon::now()) {
                    // Si está dentro del rango, lo agregamos al array de tickets válidos
                    $validTickets[] = $ticket;
                }
            }
        }
        $tickets = collect($validTickets);

        $teatro = Teatro2::findOrFail($event->venue_name);

        $image_path = null;
        if ($event->images->count()) {
            $image_path = $event->images->first()->image_path;
        }

        $categoriaDelEvento = Categoria::findOrFail($event->location_address_line_2);

        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);

        if ($startDate->month == $endDate->month) {
            $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
        } else {
            $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
        }

        $data = [
            'categorias' => $categorias,
            'categorias3' => $categorias3,
            'categoria2' => $categoriaDelEvento,
            'evento' => $eventos,
            'teatro' => $teatro,
            'tickets' => $tickets,
            'event' => $event,
            'image_path' => $image_path,
            'organiser' => $organiser,
        ];

        return view('personas.obras3', $data);
    }


    public function compraRealizada($id)
    {
        Carbon::setLocale('es');
        $organiser = Organiser::scope()->find('1');
        $orders = Order::where('order_reference', $id)->orderBy('created_at', 'asc')->get();

        $event_ids = $orders->pluck('event_id')->unique();
        //$events = Event::whereIn('id', $event_ids)->get()->keyBy('id');
        $events = Event::with('teatro') // Cargamos la relación 'teatro'
            ->whereIn('id', $event_ids)
            ->get()
            ->keyBy('id');

        foreach ($orders as $order) {
            $order->event = $events->get($order->event_id);
        }
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')->where(function ($query) {
            $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
        })->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }

        $eventos2 = Event::with('teatro')
            ->where('location_state', 'yes')->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })  // Filtra los eventos donde location_state es 'yes'
            ->get()
            ->groupBy('location_address_line_2');  // Agrupa los eventos por location_address_line_2
        foreach ($eventos2 as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        //$order2 = Order::scope()->find($orders->id);
        $order2 = Order::scope()->where('order_reference', $id)->first();

        $orderService = new OrderService($order2->amount, $order2->booking_fee, $order2->event);
        $orderService->calculateFinalCosts();


        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'orders'     => $orders,
            'order2' => $order2,
            'orderService' => $orderService,
            'eventos' => $eventos,
            'eventos2' => $eventos2,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        return view('personas.compra-realizada', $data);
    }


    public function compraFallida()
    {
        Carbon::setLocale('es');
        $organiser = Organiser::scope()->find('1');

        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')->where(function ($query) {
            $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
        })->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }

        $eventos2 = Event::with('teatro')
            ->where('location_state', 'yes')->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })  // Filtra los eventos donde location_state es 'yes'
            ->get()
            ->groupBy('location_address_line_2');  // Agrupa los eventos por location_address_line_2
        foreach ($eventos2 as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,

            'eventos' => $eventos,
            'eventos2' => $eventos2,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        return view('personas.comprafallida', $data);
    }

    public function categoria($id)
    {


        $cat = Categoria::findOrFail($id);



        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::where('id', $id)
            ->orderBy('posicion', 'asc')
            ->get();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();


        $eventos2 = Event::with('teatro')
        ->where('location_state', 'yes')->where(function ($query) {
            $query->where('location_address_line_1', '<>', '1')
                ->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
        })  // Filtra los eventos donde location_state es 'yes'
        ->where('is_live', 1)
        ->get();
    // Cargar los organizadores (ya cargados por la relación 'organiser')
    $eventos2->load('organiser');

    // Modificar cada evento para agregar el nombre del organizador
    $eventos2->transform(function ($evento2) {
        // Agregar el nombre del organizador
        $evento2->organiser_name = $evento2->organiser ? $evento2->organiser->name : 'Sin organizador';
        return $evento2;
    });

    // Agrupar eventos por 'location_address_line_2'
    $eventos2 = $eventos2->groupBy('location_address_line_2');  // Agrupa los eventos por location_address_line_2
    foreach ($eventos2 as $categoriaId => $eventosCategoria) {
        foreach ($eventosCategoria as $event) {
            $startDate = Carbon::parse($event->start_date);
            $endDate = Carbon::parse($event->end_date);

            if ($startDate->month == $endDate->month) {
                $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
            } else {
                $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
            }
        }
    }



        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'geventos'=>$cat->globalEvents,
            'eventos2' => $eventos2,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Pasar el evento a la vista
        return view('personas.categoria', $data);
    }


    public function categoria2($id)
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::where('id', $id)
            ->orderBy('posicion', 'asc')
            ->get();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where('location_address_line_2', $id)->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })
            ->where('is_live', 1)
            ->get();

        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos->transform(function ($evento) {
            // Agregar el nombre del organizador
            $evento->organiser_name = $evento->organiser ? $evento->organiser->name : 'Sin organizador';
            return $evento;
        });

        // Agrupar eventos por 'location_address_line_2'
        $eventos = $eventos->groupBy('location_address_line_2');
        //$eventos = Event::with('teatro')->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }

        $eventos2 = Event::with('teatro')
            ->where('location_state', 'yes')->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')
                    ->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })  // Filtra los eventos donde location_state es 'yes'
            ->where('is_live', 1)
            ->get();
        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos2->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos2->transform(function ($evento2) {
            // Agregar el nombre del organizador
            $evento2->organiser_name = $evento2->organiser ? $evento2->organiser->name : 'Sin organizador';
            return $evento2;
        });

        // Agrupar eventos por 'location_address_line_2'
        $eventos2 = $eventos2->groupBy('location_address_line_2');  // Agrupa los eventos por location_address_line_2
        foreach ($eventos2 as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'eventos2' => $eventos2,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Pasar el evento a la vista
        return view('personas.categoria', $data);
    }



    public function controlingreso($id)
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::all();
        $banners = Banner::all();
        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })
            ->get()
            ->groupBy('location_address_line_2');
        //$eventos = Event::with('teatro')->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }

        $eventos2 = Event::with('teatro')
            ->where('location_state', 'yes')->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })  // Filtra los eventos donde location_state es 'yes'
            ->get()
            ->groupBy('location_address_line_2');  // Agrupa los eventos por location_address_line_2
        foreach ($eventos2 as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'eventos2' => $eventos2,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Pasar el evento a la vista
        return view('personas.controlingreso', $data);
    }

    public function preguntasFrecuentes()
    {
        $organiser = Organiser::scope()->find('1');
        $preguntas = Pregunta::orderBy('created_at', 'desc')->get();

        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })
            ->get()
            ->groupBy('location_address_line_2');
        //$eventos = Event::with('teatro')->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }




        $data = [

            'categorias' => $categorias2,
            'eventos' => $eventos,
            'preguntas' => $preguntas,
            'organiser' => $organiser,
        ];

        // Pasar el evento a la vista
        return view('personas.preguntas-frecuentes', $data);
    }

    public function sobreNosotros()
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })
            ->get()
            ->groupBy('location_address_line_2');
        //$eventos = Event::with('teatro')->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }




        $data = [

            'categorias' => $categorias2,
            'eventos' => $eventos,
            'organiser' => $organiser,
        ];

        // Pasar el evento a la vista
        return view('personas.sobre-nosotros', $data);
    }

    public function terminosCondiciones()
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })
            ->get()
            ->groupBy('location_address_line_2');
        //$eventos = Event::with('teatro')->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }




        $data = [

            'categorias' => $categorias2,
            'eventos' => $eventos,
            'organiser' => $organiser,
        ];

        // Pasar el evento a la vista
        return view('personas.terminos-condiciones', $data);
    }

    public function RecuperarPass()
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })
            ->get()
            ->groupBy('location_address_line_2');
        //$eventos = Event::with('teatro')->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }




        $data = [

            'categorias' => $categorias2,
            'eventos' => $eventos,
            'organiser' => $organiser,
        ];

        // Pasar el evento a la vista
        return view('personas.recuperarp', $data);
    }

    public function sendOtp(Request $request)
    {
        // Validar que el correo esté presente y sea un formato válido
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        // Buscar el cliente por email
        $cliente = Cliente::where('email', $validated['email'])->first();

        // Verificar si se encontró el cliente
        if (!$cliente) {
            // Si no se encuentra, devolver un mensaje de error claro
            return response()->json([
                'success' => false,
                'message' => 'No se encuentra registrado un usuario con ese correo electrónico.'
            ], 404); // Retorna error 404
        }

        // Si el cliente se encuentra, generar un OTP de 6 caracteres
        $otp = Str::random(6);
        $email = $cliente->email;
        // Opcionalmente, puedes guardar el OTP en la sesión o base de datos
        //session(['otp' => $otp]);

        // Enviar el OTP por correo
        try {
            Mail::raw('Tu código de verificación es: ' . $otp, function ($message) use ($cliente) {
                $message->to($cliente->email)->subject('Restablecer Contraseña');
            });
            $cliente->contraseña = $otp;
            $cliente->save();

            // Retornar respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Se ha enviado un código de verificación a tu correo.'
            ]);
        } catch (\Exception $e) {
            // En caso de error al enviar el correo, manejar el error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un problema al enviar el correo.'
            ], 500); // Error interno del servidor
        }
    }

    public function cambiarClaveconOtp(Request $request)
    {
        // Validación de los datos recibidos
        $validated = $request->validate([
            'email' => 'required|email',
            'ctp' => 'required|string', // código temporal (OTP)
            'npass' => 'required|string|min:8', // nueva contraseña
            'rpass' => 'required|string|min:8', // repetir la contraseña
        ]);

        // Verificar que las contraseñas coincidan
        if ($validated['npass'] !== $validated['rpass']) {
            return response()->json(['success' => false, 'message' => 'Las contraseñas no coinciden.'], 400);
        }

        // Buscar al cliente por su correo electrónico
        $cliente = Cliente::where('email', $validated['email'])->first();

        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'Cliente no encontrado.'], 404);
        }

        // Verificar que el código OTP enviado coincida con el almacenado en la base de datos
        if ($cliente->contraseña !== $validated['ctp']) {
            return response()->json(['success' => false, 'message' => 'El código OTP es incorrecto.'], 400);
        }

        $now = Carbon::now(); // Hora actual
        $updatedAt = Carbon::parse($cliente->updated_at); // Fecha de la última actualización

        if ($now->diffInMinutes($updatedAt) > 10) {
            return response()->json(['success' => false, 'message' => 'El código ingresado expiró, por favor resetee nuevamente la contraseña.'], 400);
        }

        // Actualizar la contraseña
        $cliente->contraseña = Hash::make($validated['npass']);
        $cliente->save();

        return response()->json(['success' => true, 'message' => 'Contraseña actualizada exitosamente.'], 200);
    }

    public function contactoForm()
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })
            ->get()
            ->groupBy('location_address_line_2');
        //$eventos = Event::with('teatro')->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }




        $data = [

            'categorias' => $categorias2,
            'eventos' => $eventos,
            'organiser' => $organiser,
        ];

        // Pasar el evento a la vista
        return view('personas.contacto', $data);
    }

    public function arrepentimientoForm()
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')->orWhereNull('location_address_line_1');
            })
            ->get()
            ->groupBy('location_address_line_2');
        //$eventos = Event::with('teatro')->get()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }




        $data = [

            'categorias' => $categorias2,
            'eventos' => $eventos,
            'organiser' => $organiser,
        ];

        // Pasar el evento a la vista
        return view('personas.formularioarrep', $data);
    }

    public function buscar(Request $request)
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');

        $searchTerm = $request->input('search'); // Obtener el texto de búsqueda

        // Obtener eventos que coincidan con el texto de búsqueda
        $eventos = Event::with('teatro')
            ->where('title', 'LIKE', '%' . $searchTerm . '%') // Cambia 'nombre' por el campo que deseas buscar
            ->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')
                    ->where('location_address_line_1', '!=', '1111')
                    ->orWhereNull('location_address_line_1');
            })
            ->where('is_live', 1)
            ->get();

        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos->transform(function ($evento) {
            // Agregar el nombre del organizador
            $evento->organiser_name = $evento->organiser ? $evento->organiser->name : 'Sin organizador';
            return $evento;
        });

        // Agrupar eventos por 'location_address_line_2'
        $eventos = $eventos->groupBy('location_address_line_2');

        // Otros datos para la vista
        $banners = Banner::all();
        $categorias2 = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }

        $data = [
            'eventos' => $eventos,
            'banners' => $banners,
            'categorias' => $categorias2,
            'organiser' => $organiser,
        ];

        return view('personas.buscar', $data);
    }

    public function dashboard()
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        //$eventos = Event::all()->groupBy('location_address_line_2');
        $eventos = Event::with('teatro')->where(function ($query) {
            $query->where('location_address_line_1', '<>', '1')
                ->where('location_address_line_1', '!=', '1111')
                ->orWhereNull('location_address_line_1');
        })
            ->where('is_live', 1)
            ->get();

        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos->transform(function ($evento) {
            // Agregar el nombre del organizador
            $evento->organiser_name = $evento->organiser ? $evento->organiser->name : 'Sin organizador';
            return $evento;
        });

        // Agrupar eventos por 'location_address_line_2'
        $eventos = $eventos->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }
        $eventos2 = Event::with('teatro')
            ->where('location_state', 'yes')->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')
                    ->where('location_address_line_1', '!=', '1111')
                    ->orWhereNull('location_address_line_1');
            })  // Filtra los eventos donde location_state es 'yes'
            ->where('is_live', 1)
            ->get();
        // Cargar los organizadores (ya cargados por la relación 'organiser')
        $eventos2->load('organiser');

        // Modificar cada evento para agregar el nombre del organizador
        $eventos2->transform(function ($evento2) {
            // Agregar el nombre del organizador
            $evento2->organiser_name = $evento2->organiser ? $evento2->organiser->name : 'Sin organizador';
            return $evento2;
        });

        // Agrupar eventos por 'location_address_line_2'
        $eventos2 = $eventos2->groupBy('location_address_line_2'); // Agrupa los eventos por location_address_line_2
        foreach ($eventos2 as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }

        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'eventos2' => $eventos2,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Retorna la vista con las categorías
        return view('personas.dashboard', $data);
    }
    public function editarperfil()
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        $eventos = Event::all()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Retorna la vista con las categorías
        return view('personas.editarPerfil', $data);
    }

    public function actualizarPerfil(Request $request, $id)
    {
        $organiser = Organiser::scope()->find('1');
        $validated = $request->validate([
            'nombres'    => 'required|string|max:255',
            'telefono'   => 'required|string|max:15',
            'email'      => 'required|email|unique:clientes,email,' . $id,
            'email_confirmation' => 'required|same:email',
        ]);
        $categorias2 = Categoria::all();

        $cliente = Cliente::findOrFail($id);

        $cliente->update($validated);

        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        $eventos = Event::all()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'banners' => $banners,
            'organiser' => $organiser,
        ];
        return redirect()->route('personas.editarperfil', $data)->with('success', 'Cliente actualizado con éxito.');
    }

    public function mostrarFormularioPass()
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        $eventos = Event::all()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Retorna la vista con las categorías
        return view('personas.cambiar-pass', $data);
    }

    public function actualizarPass(Request $request, $id)
    {
        $organiser = Organiser::scope()->find('1');
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Obtener el cliente
        $cliente = Cliente::findOrFail($id);

        // Verificar la contraseña actual
        if (!Hash::check($request->input('current_password'), Auth::guard('clientes')->user()->contraseña)) {
            // Redirigir con un mensaje de error si la contraseña actual es incorrecta
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        // Actualizar la contraseña
        $cliente->contraseña = Hash::make($request->input('password'));
        $cliente->save();

        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        $eventos = Event::all()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }


        $data = [
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        return redirect()->route('personas.cambiarpass')->with('success', 'Contraseña actualizada con éxito.');
        //return redirect()->route('personas.cambiar-pass', $data)->with('success', 'Contraseña actualizada con éxito.');
    }

    public function misCompras()
    {

        if (!Auth::guard('clientes')->check()) {
            // Si no está autenticado, redirigir al login
            return redirect()->route('personas.login.form')->with('error', 'Por favor, inicia sesión primero.');
        }
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        $eventos = Event::all()->groupBy('location_address_line_2');
        $event = Event::all()->groupBy('location_address_line_2');

        $searchQuery = Auth::guard('clientes')->user()->id;
        $orders = Order::where('last_name', $searchQuery)->orderBy('created_at', 'desc')->get();
        $event_ids = $orders->pluck('event_id')->unique();
        $events = Event::whereIn('id', $event_ids)->get()->keyBy('id');

        foreach ($orders as $order) {
            $order->event = $events->get($order->event_id);
        }

        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/
        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }



        $data = [
            'categorias' => $categorias,
            'orders'     => $orders,
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'banners' => $banners,
            'organiser' => $organiser,
        ];

        // Retorna la vista con las categorías
        return view('personas.misCompras', $data);
    }

    public function verEntradas($compraid)
    {
        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();

        //$event = Event::all();
        $eventos = Event::all()->groupBy('location_address_line_2');
        $event = Event::all()->groupBy('location_address_line_2');


        $orders = Order::where('order_reference', $compraid)->orderBy('created_at', 'desc')->get();
        $event_ids = $orders->pluck('event_id')->unique();
        $events = Event::whereIn('id', $event_ids)->get()->keyBy('id');

        foreach ($orders as $order) {
            $order->event = $events->get($order->event_id);
        }

        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
        return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
    });*/
        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }
        $order = Order::where('order_reference', $compraid)
            ->orderBy('created_at', 'desc')
            ->first();  // Obtiene el primer resultado

        if ($order) {
            $event_id = $order->event_id;  // Obtiene el 'event_id' del primer resultado
        } else {
            // Manejar el caso en que no se encuentra la orden
            $event_id = null;
        }
        $event = Event::scope()->find($event_id);

        $dateString = $event->location_country_code;  // Por ejemplo: '29/11/2024 21:17'

        // Convertir la fecha usando el formato adecuado
        $eventDate = Carbon::createFromFormat('d/m/Y H:i', $dateString);

        // Restar 24 horas a la fecha del evento
        $eventDateMinus24Hours = $eventDate->subDay();

        // Verificar si la fecha actual es mayor que la fecha del evento menos 24 horas
        if (Carbon::now()->gt($eventDateMinus24Hours)) {
            // Si la fecha actual es mayor que la fecha del evento menos 24 horas, establecer $event como vacío
            //$event = null;
            $attendees = collect();
        } else {

            $attendees = $event->attendees()
                ->join('orders', 'orders.id', '=', 'attendees.order_id')
                ->withoutCancelled()
                ->when($compraid, function ($query, $compraid) {
                    return $query->where('orders.order_reference', $compraid);
                })
                ->select('attendees.*', 'orders.order_reference')
                ->paginate();
        }



        $data = [
            'attendees'  => $attendees,
            'compraid'  => $compraid,
            'categorias' => $categorias,
            'orders'     => $orders,
            'event'      => $event,
            'categorias2' => $categorias2,
            'eventos' => $eventos,
            'organiser' => $organiser,
        ];

        // Retorna la vista con las categorías
        return view('personas.verEntradas', $data);
    }

    public function EditarEntrada(Request $request, $event_id, $attendee_id)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
        ];

        $validator = Validator::make($request->all(), $rules);

        // Si falla la validación, devuelve los errores
        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $attendee = Attendee::scope()->findOrFail($attendee_id);
        $attendee->update($request->all());

        session()->flash('message', trans("Controllers.successfully_updated_attendee"));

        return redirect()->route('personas.verentradas', ['id' => $event_id]);
    }

    public function postValidateTickets(Request $request, $event_id)
    {
        /*
         * Order expires after X min
         */

        $order_expires_time = Carbon::now()->addMinutes(config('attendize.checkout_timeout_after'));

        $event = Event::findOrFail($event_id);

        if (!$request->has('tickets')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No tickets selected',
            ]);
        }

        $ticket_ids = $request->get('tickets');

        /*
         * Remove any tickets the user has reserved
         */
        ReservedTickets::where('session_id', '=', session()->getId())->delete();

        /*
         * Go though the selected tickets and check if they're available
         * , tot up the price and reserve them to prevent over selling.
         */

        $validation_rules = [];
        $validation_messages = [];
        $tickets = [];
        $order_total = 0;
        $total_ticket_quantity = 0;
        $booking_fee = 0;
        $organiser_booking_fee = 0;
        $quantity_available_validation_rules = [];

        foreach ($ticket_ids as $ticket_id) {
            $current_ticket_quantity = (int)$request->get('ticket_' . $ticket_id);

            if ($current_ticket_quantity < 1) {
                continue;
            }

            $total_ticket_quantity = $total_ticket_quantity + $current_ticket_quantity;
            $ticket = Ticket::find($ticket_id);
            $max_per_person = min($ticket->quantity_remaining, $ticket->max_per_person);
            //if ($max_per_person < $total_ticket_quantity){$max_per_person=$total_ticket_quantity;}

            $quantity_available_validation_rules['ticket_' . $ticket_id] = [
                'numeric',
                'min:' . $ticket->min_per_person,
                'max:' . $max_per_person
            ];

            $quantity_available_validation_messages = [
                'ticket_' . $ticket_id . '.max' => 'The maximum number of tickets you can register is ' . $max_per_person,
                'ticket_' . $ticket_id . '.min' => 'You must select at least ' . $ticket->min_per_person . ' tickets.',
            ];

            $validator = Validator::make(
                ['ticket_' . $ticket_id => (int)$request->get('ticket_' . $ticket_id)],
                $quantity_available_validation_rules,
                $quantity_available_validation_messages
            );

            if ($validator->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validator->messages()->toArray(),
                ]);
            }

            $order_total = $order_total + ($current_ticket_quantity * $ticket->price);
            $booking_fee = $booking_fee + ($current_ticket_quantity * $ticket->booking_fee);
            $organiser_booking_fee = $organiser_booking_fee + ($current_ticket_quantity * $ticket->organiser_booking_fee);

            $tickets[] = [
                'ticket'                => $ticket,
                'qty'                   => $current_ticket_quantity,
                'price'                 => ($current_ticket_quantity * $ticket->price),
                'booking_fee'           => ($current_ticket_quantity * $ticket->booking_fee),
                'organiser_booking_fee' => ($current_ticket_quantity * $ticket->organiser_booking_fee),
                'full_price'            => $ticket->price + $ticket->total_booking_fee,
            ];

            /*
             * Reserve the tickets for X amount of minutes
             */
            $reservedTickets = new ReservedTickets();
            $reservedTickets->ticket_id = $ticket_id;
            $reservedTickets->event_id = $event_id;
            $reservedTickets->quantity_reserved = $current_ticket_quantity;
            $reservedTickets->expires = $order_expires_time;
            $reservedTickets->session_id = session()->getId();
            $reservedTickets->save();

            for ($i = 0; $i < $current_ticket_quantity; $i++) {
                /*
                 * Create our validation rules here
                 */
                $validation_rules['ticket_holder_first_name.' . $i . '.' . $ticket_id] = ['required'];
                $validation_rules['ticket_holder_last_name.' . $i . '.' . $ticket_id] = ['required'];
                $validation_rules['ticket_holder_email.' . $i . '.' . $ticket_id] = ['required', 'email'];

                $validation_messages['ticket_holder_first_name.' . $i . '.' . $ticket_id . '.required'] = 'Ticket holder ' . ($i + 1) . '\'s first name is required';
                $validation_messages['ticket_holder_last_name.' . $i . '.' . $ticket_id . '.required'] = 'Ticket holder ' . ($i + 1) . '\'s last name is required';
                $validation_messages['ticket_holder_email.' . $i . '.' . $ticket_id . '.required'] = 'Ticket holder ' . ($i + 1) . '\'s email is required';
                $validation_messages['ticket_holder_email.' . $i . '.' . $ticket_id . '.email'] = 'Ticket holder ' . ($i + 1) . '\'s email appears to be invalid';

                /*
                 * Validation rules for custom questions
                 */
                foreach ($ticket->questions as $question) {
                    if ($question->is_required && $question->is_enabled) {
                        $validation_rules['ticket_holder_questions.' . $ticket_id . '.' . $i . '.' . $question->id] = ['required'];
                        $validation_messages['ticket_holder_questions.' . $ticket_id . '.' . $i . '.' . $question->id . '.required'] = "This question is required";
                    }
                }
            }
        }

        if (empty($tickets)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No tickets selected.',
            ]);
        }

        $activeAccountPaymentGateway = $event->account->getGateway($event->account->payment_gateway_id);
        //if no payment gateway configured and no offline pay, don't go to the next step and show user error
        if (empty($activeAccountPaymentGateway) && !$event->enable_offline_payments) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No payment gateway configured',
            ]);
        }

        $paymentGateway = $activeAccountPaymentGateway ? $activeAccountPaymentGateway->payment_gateway : false;

        /*
         * The 'ticket_order_{event_id}' session stores everything we need to complete the transaction.
         */
        session()->put('ticket_order_' . $event->id, [
            'validation_rules'        => $validation_rules,
            'validation_messages'     => $validation_messages,
            'event_id'                => $event->id,
            'tickets'                 => $tickets,
            'total_ticket_quantity'   => $total_ticket_quantity,
            'order_started'           => time(),
            'expires'                 => $order_expires_time,
            'reserved_tickets_id'     => $reservedTickets->id,
            'order_total'             => $order_total,
            'booking_fee'             => $booking_fee,
            'organiser_booking_fee'   => $organiser_booking_fee,
            'total_booking_fee'       => $booking_fee + $organiser_booking_fee,
            'order_requires_payment'  => PaymentUtils::requiresPayment($order_total),
            'account_id'              => $event->account->id,
            'affiliate_referral'      => Cookie::get('affiliate_' . $event_id),
            'account_payment_gateway' => $activeAccountPaymentGateway,
            'payment_gateway'         => $paymentGateway
        ]);

        /*
         * If we're this far assume everything is OK and redirect them
         * to the the checkout page.
         */
        /*if ($request->ajax()) {
            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('personas.showEventCheckout', [
                        'event_id'    => $event_id,
                    ]) . '#order_form',
            ]);
        }

        /*
         * Maybe display something prettier than this?
         */
        // exit('Please enable Javascript in your browser.');
        //return $this->showEventCheckout($request, $event_id);

        return redirect()->route('personas.checkout', ['request' => $request, 'event_id' => $event_id]);
    }

    public function showEventCheckout(Request $request, $event_id)
    {
        $order_session = session()->get('ticket_order_' . $event_id);

        if (!$order_session || $order_session['expires'] < Carbon::now()) {
            //$route_name = $this->is_embedded ? 'showEmbeddedEventPage' : 'showEventPage';
            return $this->misCompras();
        }

        $secondsToExpire = Carbon::now()->diffInSeconds($order_session['expires']);

        //$event = Event::findorFail($order_session['event_id']);
        $event = Event::where('id', $event_id)->first();

        $orderService = new OrderService($order_session['order_total'], $order_session['total_booking_fee'], $event);
        $feesOrden = $order_session['total_booking_fee'];
        $orderService->calculateFinalCosts();

        $organiser = Organiser::scope()->find('1');
        Carbon::setLocale('es');
        // Obtiene todas las categorías
        $categorias2 = Categoria::all();
        $categorias = Categoria::where('activado', '1')
            ->orderBy('posicion', 'asc')
            ->get();
        $banners = Banner::all();
        //$event = Event::all();
        $eventos = Event::all()->groupBy('location_address_line_2');
        // Filtra las categorías que no tienen eventos
        /*$categoriasConEventos = $categorias->filter(function ($categoria) use ($eventos) {
				return isset($eventos[$categoria->id]) && $eventos[$categoria->id]->count() > 0;
			});*/

        // Formatear fechas para cada evento
        foreach ($eventos as $categoriaId => $eventosCategoria) {
            foreach ($eventosCategoria as $event) {
                $startDate = Carbon::parse($event->start_date);
                $endDate = Carbon::parse($event->end_date);

                if ($startDate->month == $endDate->month) {
                    $event->formatted_date = "Del " . $startDate->format('d') . " al " . $endDate->format('d') . " de " . $startDate->translatedFormat('F');
                } else {
                    $event->formatted_date = "Del " . $startDate->format('d') . " de " . $startDate->translatedFormat('F') . " al " . $endDate->format('d') . " de " . $endDate->translatedFormat('F');
                }
            }
        }

        $event = Event::where('id', $event_id)->first();
        $data = $order_session + [
            'event' => $event,
            'eventoid' => $event_id,
            'categorias' => $categorias,
            'categorias2' => $categorias2,
            'feesOrden' => $feesOrden,
            'eventos' => $eventos,
            'banners' => $banners,
            'organiser' => $organiser,
            'secondsToExpire' => $secondsToExpire,
            'orderService'    => $orderService
        ];



        return view('personas.checkout', $data);
    }

    public function postValidateOrder(Request $request, $event_id)
    {

        //If there's no session kill the request and redirect back to the event homepage.
        if (!session()->get('ticket_order_' . $event_id)) {
            /*return response()->json([
                'status'      => 'error',
                'message'     => 'Your session has expire.',
                'redirectUrl' => route('showEventPage', [
                    'event_id' => $event_id,
                ])
            ]);*/
            return $this->misCompras();
        }

        $keys_data = [
            'public_key' => config('decidir.decidir_public_key'),
            'private_key' => config('decidir.decidir_secret_key'),
        ];
        $ambient = config('decidir.ambient');

        $connector = new Connector($keys_data, $ambient);

        $data2 = array();


        if (empty($request['business_address_line1']) || is_null($request['business_address_line1'])) {
            return response()->redirectToRoute('personas.comprafallida');
        } else {
            $idtransaccion = $request['business_address_line1'];
        }


        try {
            $response2 = $connector->payment()->PaymentInfo($data2, $idtransaccion);

            // Devolver una respuesta adecuada al cliente


        } catch (\Decidir\Exceptions\DecidirException $e) {
            // Capturar la excepción específica de Decidir
            return response()->redirectToRoute('personas.comprafallida');
        } catch (\Exception $e) {
            // Capturar cualquier otra excepción
            return response()->redirectToRoute('personas.comprafallida');
        }




        $request_data = session()->get('ticket_order_' . $event_id . ".request_data");
        $request_data = (!empty($request_data[0])) ? array_merge($request_data[0], $request->all())
            : $request->all();

        session()->remove('ticket_order_' . $event_id . '.request_data');
        session()->push('ticket_order_' . $event_id . '.request_data', $request_data);

        $event = Event::findOrFail($event_id);
        $order = new Order();
        $ticket_order = session()->get('ticket_order_' . $event_id);

        // $validation_rules = $ticket_order['validation_rules'];
        //$validation_messages = $ticket_order['validation_messages'];

        $order->rules = $order->rules;
        $order->messages = $order->messages;

        if ($request->has('is_business') && $request->get('is_business')) {
            // Dynamic validation on the new business fields, only gets validated if business selected
            $businessRules = [
                'business_name' => 'required',
                'business_tax_number' => 'required',
                'business_address_line1' => 'required',
                'business_address_city' => 'required',
                'business_address_code' => 'required',
            ];

            $businessMessages = [
                'business_name.required' => 'Please enter a valid business name',
                'business_tax_number.required' => 'Please enter a valid business tax number',
                'business_address_line1.required' => 'Please enter a valid street address',
                'business_address_city.required' => 'Please enter a valid city',
                'business_address_code.required' => 'Please enter a valid code',
            ];

            $order->rules = $order->rules + $businessRules;
            $order->messages = $order->messages + $businessMessages;
        }

        if (!$order->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $order->errors(),
            ]);
        }

        return $this->completeOrder($event_id, false);
    }

    public function completeOrder($event_id, $return_json = true)
    {
        DB::beginTransaction();

        try {

            $order = new Order();
            $ticket_order = session()->get('ticket_order_' . $event_id);

            $request_data = $ticket_order['request_data'][0];
            $event = Event::findOrFail($ticket_order['event_id']);
            $attendee_increment = 1;
            $ticket_questions = isset($request_data['ticket_holder_questions']) ? $request_data['ticket_holder_questions'] : [];

            /*
             * Create the order
             */
            if (isset($ticket_order['transaction_id'])) {
                $order->transaction_id = $ticket_order['transaction_id'][0];
            }

            if (isset($ticket_order['transaction_data'][0]['payment_intent'])) {
                $order->payment_intent = $ticket_order['transaction_data'][0]['payment_intent'];
            }

            /*if ($ticket_order['order_requires_payment'] && !isset($request_data['pay_offline'])) {
                $order->payment_gateway_id = $ticket_order['payment_gateway']->id;
            }*/
            // Crear el cliente (cliente de prueba con valores generados)
            $nombres = $request_data['order_first_name'];
            $email = $request_data['order_email'];
            $telefono = '0000000000'; // Valor por defecto para teléfono
            $dni = $request_data['order_last_name'];
            $contraseña = Str::random(10); // Generar una contraseña aleatoria de 10 caracteres

            // Validar los datos (de manera similar al código de store)
            $validated = [
                'nombres'    => $nombres,
                'telefono'   => $telefono,
                'email'      => $email,
                'contraseña' => $contraseña,
                'dni'        => $dni,
            ];

            // Hashear la contraseña
            $validated['contraseña'] = Hash::make($validated['contraseña']);

            // Crear el nuevo cliente
            $cliente = Cliente::where('email', $email)->first();

            if (!$cliente) {
                // Si el cliente no existe, crear un nuevo cliente
                $cliente = Cliente::create($validated);
                Mail::to($cliente->email)->send(new ClienteCreated($cliente->email, $contraseña));
            } else {
                // Si el cliente ya existe, actualizar la información si es necesario
                // Aquí puedes decidir qué hacer si el cliente ya existe, por ejemplo, actualizar su teléfono o nombre.
                // En este caso, no actualizamos nada, solo reutilizamos el cliente existente.
            }



            $order->first_name = sanitise($request_data['order_first_name']);
            $order->last_name = $cliente->id;
            $order->email = sanitise($request_data['order_email']);
            $order->order_status_id = isset($request_data['pay_offline']) ? config('attendize.order.awaiting_payment') : config('attendize.order.complete');
            $order->amount = $ticket_order['order_total'];
            $order->booking_fee = $ticket_order['booking_fee'];
            $order->organiser_booking_fee = $ticket_order['organiser_booking_fee'];
            $order->discount = 0.00;
            $order->notes = sanitise($request_data['order_last_name']);
            $order->account_id = $event->account->id;
            $order->event_id = $ticket_order['event_id'];
            $order->is_payment_received = isset($request_data['pay_offline']) ? 0 : 1;

            // Business details is selected, we need to save the business details

            $order->is_business = true;
            $order->business_name = sanitise($request_data['business_name']);
            $order->business_tax_number = sanitise($request_data['business_tax_number']);
            $order->business_address_line_one = sanitise($request_data['business_address_line1']);
            $order->business_address_line_two  = sanitise($request_data['business_address_line2']);
            $order->business_address_state_province  = sanitise($request_data['business_address_state']);
            $order->business_address_city = sanitise($request_data['business_address_city']);
            $order->business_address_code = sanitise($request_data['business_address_code']);


            // Calculating grand total including tax
            $orderService = new OrderService($ticket_order['order_total'], $ticket_order['total_booking_fee'], $event);
            $orderService->calculateFinalCosts();

            $order->taxamt = $orderService->getTaxAmount();
            $order->save();

            /**
             * We need to attach the ticket ID to an order. There is a case where multiple tickets
             * can be bought in the same order.
             */
            collect($ticket_order['tickets'])->map(function ($ticketDetail) use ($order) {
                $order->tickets()->attach($ticketDetail['ticket']['id']);
            });

            /*
             * Update affiliates stats stats

            if ($ticket_order['affiliate_referral']) {
                $affiliate = Affiliate::where('name', '=', $ticket_order['affiliate_referral'])
                    ->where('event_id', '=', $event_id)->first();
                $affiliate->increment('sales_volume', $order->amount + $order->organiser_booking_fee);
                $affiliate->increment('tickets_sold', $ticket_order['total_ticket_quantity']);
            }*/

            /*
             * Update the event stats
             */
            $event_stats = EventStats::updateOrCreate([
                'event_id' => $event_id,
                'date'     => DB::raw('CURRENT_DATE'),
            ]);
            $event_stats->increment('tickets_sold', $ticket_order['total_ticket_quantity']);

            /*if ($ticket_order['order_requires_payment']) {
                $event_stats->increment('sales_volume', $order->amount);
                $event_stats->increment('organiser_fees_volume', $order->organiser_booking_fee);
            }*/
            $event_stats->increment('sales_volume', $order->amount);
            $event_stats->increment('organiser_fees_volume', $order->organiser_booking_fee);
            /*
             * Add the attendees
             */
            foreach ($ticket_order['tickets'] as $attendee_details) {
                /*
                 * Update ticket's quantity sold
                 */
                $ticket = Ticket::findOrFail($attendee_details['ticket']['id']);

                /*
                 * Update some ticket info
                 */
                $ticket->increment('quantity_sold', $attendee_details['qty']);
                $ticket->increment('sales_volume', ($attendee_details['ticket']['price'] * $attendee_details['qty']));
                $ticket->increment(
                    'organiser_fees_volume',
                    ($attendee_details['ticket']['organiser_booking_fee'] * $attendee_details['qty'])
                );

                /*
                 * Insert order items (for use in generating invoices)
                 */
                $orderItem = new OrderItem();
                $orderItem->title = $attendee_details['ticket']['title'];
                $orderItem->quantity = $attendee_details['qty'];
                $orderItem->order_id = $order->id;
                $orderItem->unit_price = $attendee_details['ticket']['price'];
                $orderItem->unit_booking_fee = $attendee_details['ticket']['booking_fee'] + $attendee_details['ticket']['organiser_booking_fee'];
                $orderItem->save();

                /*
                 * Create the attendees
                 */
                for ($i = 0; $i < $attendee_details['qty']; $i++) {

                    $attendee = new Attendee();
                    $attendee->first_name = sanitise($request_data["ticket_holder_first_name"][$i][$attendee_details['ticket']['id']]);
                    $attendee->last_name = sanitise($request_data["ticket_holder_last_name"][$i][$attendee_details['ticket']['id']]);
                    $attendee->email = sanitise($request_data["ticket_holder_email"][$i][$attendee_details['ticket']['id']]);
                    $attendee->event_id = $event_id;
                    $attendee->order_id = $order->id;
                    $attendee->ticket_id = $attendee_details['ticket']['id'];
                    $attendee->account_id = $event->account->id;
                    $attendee->reference_index = $attendee_increment;
                    $attendee->save();


                    /*
                     * Save the attendee's questions
                     */
                    foreach ($attendee_details['ticket']->questions as $question) {
                        $ticket_answer = isset($ticket_questions[$attendee_details['ticket']->id][$i][$question->id])
                            ? $ticket_questions[$attendee_details['ticket']->id][$i][$question->id]
                            : null;

                        if (is_null($ticket_answer)) {
                            continue;
                        }

                        /*
                         * If there are multiple answers to a question then join them with a comma
                         * and treat them as a single answer.
                         */
                        $ticket_answer = is_array($ticket_answer) ? implode(', ', $ticket_answer) : $ticket_answer;

                        if (!empty($ticket_answer)) {
                            QuestionAnswer::create([
                                'answer_text' => $ticket_answer,
                                'attendee_id' => $attendee->id,
                                'event_id'    => $event->id,
                                'account_id'  => $event->account->id,
                                'question_id' => $question->id
                            ]);
                        }
                    }

                    /* Keep track of total number of attendees */
                    $attendee_increment++;
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Whoops! There was a problem processing your order. Please try again.'
            ]);
        }
        //save the order to the database
        DB::commit();
        //forget the order in the session
        session()->forget('ticket_order_' . $event->id);

        /*
         * Remove any tickets the user has reserved after they have been ordered for the user
         */
        ReservedTickets::where('session_id', '=', session()->getId())->delete();

        // Queue up some tasks - Emails to be sent, PDFs etc.
        // Send order notification to organizer
        Log::debug('Queueing Order Notification Job');
        SendOrderNotificationJob::dispatch($order, $orderService);
        // Send order confirmation to ticket buyer
        Log::debug('Queueing Order Tickets Job');
        $order_url = url('/order/' . $attendee->order_id . '/tickets?download=1');
        SendOrderConfirmationJob::dispatch($order, $orderService, $order_url);
        // Send tickets to attendees
        Log::debug('Queueing Attendee Ticket Jobs');
        foreach ($order->attendees as $attendee) {
            $ticket_url = url('/order/' . $attendee->order_id . '/tickets/' . $attendee->private_reference_number . '?download=1');
            SendOrderAttendeeTicketJob::dispatch($attendee, $ticket_url);
            Log::debug('Queueing Attendee Ticket Job Done');
        }



        //return $this->compraRealizada($order->order_reference);
        return response()->redirectToRoute('personas.compra-realizada', ['id' => $order->order_reference]);

        // Queue up some tasks - Emails to be sent, PDFs etc.
        // Send order notification to organizer
        /* Log::debug('Queueing Order Notification Job');
        SendOrderNotificationJob::dispatch($order, $orderService);
        // Send order confirmation to ticket buyer
        Log::debug('Queueing Order Tickets Job');
        SendOrderConfirmationJob::dispatch($order, $orderService);
        // Send tickets to attendees
        Log::debug('Queueing Attendee Ticket Jobs');
        foreach ($order->attendees as $attendee) {
            SendOrderAttendeeTicketJob::dispatch($attendee);
            Log::debug('Queueing Attendee Ticket Job Done');
        }
*/
        /*if ($return_json) {
            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('personas.compra-realizada', [
                    'id' => $order->order_reference,
                ]),
            ]);
        }
		/*
        return response()->redirectToRoute('showOrderDetails', [
            'order_reference' => $order->order_reference,
        ]);*/
    }

    public function showCheckIn($event_id)
    {
        $event = Event::scope()->findOrFail($event_id);

        $data = [
            'event'     => $event,
            'attendees' => $event->attendees
        ];

        JavaScript::put([
            'qrcodeCheckInRoute' => route('personas.postQRCodeCheckInAttendee', ['event_id' => $event->id]),
            'checkInRoute'       => route('personas.postCheckInAttendee', ['event_id' => $event->id]),
            'checkInSearchRoute' => route('personas.postCheckInSearch', ['event_id' => $event->id]),
        ]);

        return view('personas.CheckIn', $data);
    }

    public function showQRCodeModal(Request $request, $event_id)
    {
        return view('personas.Modals.QrcodeCheckIn');
    }

    /**
     * Search attendees
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckInSearch(Request $request, $event_id)
    {
        $searchQuery = $request->get('q');

        $attendees = Attendee::scope()->withoutCancelled()
            ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
            ->join('orders', 'orders.id', '=', 'attendees.order_id')
            ->where(function ($query) use ($event_id) {
                $query->where('attendees.event_id', '=', $event_id);
            })->where(function ($query) use ($searchQuery) {
                $query->orWhere('attendees.first_name', 'like', $searchQuery . '%')
                    ->orWhere(
                        DB::raw("CONCAT_WS(' ', attendees.first_name, attendees.last_name)"),
                        'like',
                        $searchQuery . '%'
                    )
                    //->orWhere('attendees.email', 'like', $searchQuery . '%')
                    ->orWhere('orders.order_reference', 'like', $searchQuery . '%')
                    ->orWhere('attendees.private_reference_number', 'like', $searchQuery . '%')
                    ->orWhere('attendees.last_name', 'like', $searchQuery . '%');
            })
            ->select([
                'attendees.id',
                'attendees.first_name',
                'attendees.last_name',
                'attendees.email',
                'attendees.arrival_time',
                'attendees.reference_index',
                'attendees.has_arrived',
                'tickets.title as ticket',
                'orders.order_reference',
                'orders.is_payment_received'
            ])
            ->orderBy('attendees.first_name', 'ASC')
            ->get();

        return response()->json($attendees);
    }

    /**
     * Check in/out an attendee
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckInAttendee(Request $request)
    {
        $attendee_id = $request->get('attendee_id');
        $checking = $request->get('checking');

        $attendee = Attendee::scope()->find($attendee_id);

        /*
         * Ugh
         */
        if ((($checking == 'in') && ($attendee->has_arrived == 1)) || (($checking == 'out') && ($attendee->has_arrived == 0))) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Attendee Already Checked ' . (($checking == 'in') ? 'In (at ' . $attendee->arrival_time->format('H:i A, F j') . ')' : 'Out') . '!',
                'checked' => $checking,
                'id'      => $attendee->id,
            ]);
        }

        $attendee->has_arrived = ($checking == 'in') ? 1 : 0;
        $attendee->arrival_time = Carbon::now();
        $attendee->save();

        return response()->json([
            'status'  => 'success',
            'checked' => $checking,
            'message' => (($checking == 'in') ? trans("Controllers.attendee_successfully_checked_in") : trans("Controllers.attendee_successfully_checked_out")),
            'id'      => $attendee->id,
        ]);
    }


    /**
     * Check in an attendee
     *
     * @param $event_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckInAttendeeQr($event_id, Request $request)
    {
        $event = Event::scope()->findOrFail($event_id);

        $qrcodeToken = $request->get('attendee_reference');
        $attendee = Attendee::scope()->withoutCancelled()
            ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
            ->where(function ($query) use ($event, $qrcodeToken) {
                $query->where('attendees.event_id', $event->id)
                    ->where('attendees.private_reference_number', $qrcodeToken);
            })->select([
                'attendees.id',
                'attendees.order_id',
                'attendees.first_name',
                'attendees.last_name',
                'attendees.email',
                'attendees.reference_index',
                'attendees.arrival_time',
                'attendees.has_arrived',
                'tickets.title as ticket',
            ])->first();

        if (is_null($attendee)) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.invalid_ticket_error")
            ]);
        }

        $relatedAttendesCount = Attendee::where('id', '!=', $attendee->id)
            ->where([
                'order_id'    => $attendee->order_id,
                'has_arrived' => false
            ])->count();

        if ($attendee->has_arrived) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.attendee_already_checked_in", ["time" => $attendee->arrival_time->format(config("attendize.default_datetime_format"))])
            ]);
        }

        Attendee::find($attendee->id)->update(['has_arrived' => true, 'arrival_time' => Carbon::now()]);

        return response()->json([
            'status'  => 'success',
            'name' => $attendee->first_name . " " . $attendee->last_name,
            'reference' => $attendee->reference,
            'ticket' => $attendee->ticket
        ]);
    }
    public function postCheckInAttendeeQr2($event_id, $qrToken)
    {
        $event = Event::scope()->findOrFail($event_id);

        $qrcodeToken = $qrToken;
        $attendee = Attendee::scope()->withoutCancelled()
            ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
            ->where(function ($query) use ($event, $qrcodeToken) {
                $query->where('attendees.event_id', $event->id)
                    ->where('attendees.private_reference_number', $qrcodeToken);
            })->select([
                'attendees.id',
                'attendees.order_id',
                'attendees.first_name',
                'attendees.last_name',
                'attendees.email',
                'attendees.reference_index',
                'attendees.arrival_time',
                'attendees.has_arrived',
                'tickets.title as ticket',
            ])->first();

        if (is_null($attendee)) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.invalid_ticket_error")
            ]);
        }

        $relatedAttendesCount = Attendee::where('id', '!=', $attendee->id)
            ->where([
                'order_id'    => $attendee->order_id,
                'has_arrived' => false
            ])->count();

        if ($attendee->has_arrived) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.attendee_already_checked_in", ["time" => $attendee->arrival_time->format(config("attendize.default_datetime_format"))])
            ]);
        }

        Attendee::find($attendee->id)->update(['has_arrived' => true, 'arrival_time' => Carbon::now()]);

        return response()->json([
            'status'  => 'success',
            'name' => $attendee->first_name . " " . $attendee->last_name,
            'reference' => $attendee->reference,
            'ticket' => $attendee->ticket
        ]);
    }
    public function postCheckInAttendeeQr3($event_id, $qrToken)
    {
        //$event = Event::scope()->findOrFail($event_id);

        $qrcodeToken = $qrToken;
        $attendee = Attendee::scope()->withoutCancelled()
            ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
            ->where(function ($query) use ($qrcodeToken) {
                $query->where('attendees.private_reference_number', $qrcodeToken);
            })->select([
                'attendees.id',
                'attendees.order_id',
                'attendees.event_id',
                'attendees.first_name',
                'attendees.last_name',
                'attendees.email',
                'attendees.reference_index',
                'attendees.arrival_time',
                'attendees.has_arrived',
                'tickets.title as ticket',
            ])->first();

        if (is_null($attendee)) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.invalid_ticket_error")
            ]);
        }
        $event = Event::scope()->findOrFail($attendee->event_id);
        if (Carbon::now()->isAfter($event->end_date)) {
            return response()->json([
                'status' => 'success',
                'name' => 'El evento ya finalizó.',
                'reference' => $attendee->reference,
                'nombreevento' => '',
                'eventid' => '',
                'email' => '',
                'has_arrived' => '3',
                'ticket' => ''
            ]);
        }
        $relatedAttendesCount = Attendee::where('id', '!=', $attendee->id)
            ->where([
                'order_id'    => $attendee->order_id,
                'has_arrived' => false
            ])->count();

        /*if ($attendee->has_arrived) {
            return response()->json([
                'status'  => 'error',
                'message' => trans("Controllers.attendee_already_checked_in", ["time"=> $attendee->arrival_time->format(config("attendize.default_datetime_format"))])
            ]);
        }*/

        // Attendee::find($attendee->id)->update(['has_arrived' => true, 'arrival_time' => Carbon::now()]);
        $formattedArrivalTime = Carbon::parse($attendee->arrival_time)->format('d/m/Y H:i:s');

        return response()->json([
            'status'  => 'success',
            'name' => $attendee->first_name . " " . $attendee->last_name,
            'reference' => $attendee->reference,
            'nombreevento' => $event->title,
            'eventid' => $attendee->event_id,
            'email' => $attendee->email,
            'arrival_time' => $formattedArrivalTime,
            'has_arrived' => $attendee->has_arrived,
            'ticket' => $attendee->ticket
        ]);
    }

    public function descargarTickets($referencia)
    {
        // Ruta donde están los PDFs
        $ruta = public_path('user_content/pdf_tickets');
        $zipName = "tickets_{$referencia}.zip";
        $zipPath = "$ruta/$zipName";

        // Verificar si hay archivos que coinciden
        $archivos = glob("$ruta/{$referencia}*.pdf");
        if (empty($archivos)) {
            return response()->json(['error' => 'No se encontraron tickets para la referencia proporcionada'], 404);
        }

        // Crear el archivo ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }

        // Agregar archivos PDF al ZIP
        foreach ($archivos as $archivo) {
            $zip->addFile($archivo, basename($archivo));
        }

        // Cerrar el ZIP
        $zip->close();

        // Verifica si el archivo ZIP fue creado
        if (!file_exists($zipPath)) {
            return response()->json(['error' => 'El archivo ZIP no se pudo crear'], 500);
        }

        // Descargar el ZIP
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function postContactOrganiser(Request $request)
    {
        $organiser = Organiser::scope()->find('1');
        // Reglas de validación
        $rules = [
            'name'    => 'required|string|max:255', // Asegura que sea una cadena y establece un máximo
            'email'   => 'required|email|max:255',   // Asegura que sea un email válido
            'message' => 'required|string',           // Asegura que sea una cadena
        ];

        // Validación
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        // Verificación de captcha
        if (isset($this->captchaService) && is_object($this->captchaService)) {
            if (!$this->captchaService->isHuman($request)) {
                return response()->json([
                    'status'   => 'error',
                    'message'  => trans("Controllers.incorrect_captcha"),
                ]);
            }
        }

        // Preparación de datos para el correo
        $data = [
            'sender_name'     => $request->input('name'), // Se recomienda usar input()
            'sender_email'    => $request->input('email'),
            'message_content' => clean($request->input('message')),
            'event'           => '1', // Asegúrate de que esto sea relevante
        ];

        // Envío de correo
        Mail::send(Lang::locale() . '.Emails.messageReceived', $data, function ($message) use ($data, $organiser) {
            $message->to($organiser->email, env('NOMBRE_CONTACTO'))
                ->from($data['sender_email'], $data['sender_name'])
                ->replyTo($data['sender_email'], $data['sender_name'])
                ->subject('HAS RECIBIDO UN MENSAJE DE CONTACTO');
        });

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.message_successfully_sent"),
        ]);
    }

    public function postContactOrganiser2(Request $request)
    {
        $organiser = Organiser::scope()->find('1');
        $rules = [
            'name'                  => 'required',
            'email'                 => 'required|email',
            'message'               => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        if (is_object($this->captchaService)) {
            if (!$this->captchaService->isHuman($request)) {
                return Redirect::back()
                    ->with(['message' => trans("Controllers.incorrect_captcha"), 'failed' => true])
                    ->withInput();
            }
        }

        // $event = Event::findOrFail($event_id);

        $data = [
            'sender_name'     => $request->get('name'),
            'sender_email'    => $request->get('email'),
            'message_content' => clean($request->get('message')),
            'event'           => '1',
        ];
        $event = "1";
        Mail::send(Lang::locale() . '.Emails.messageReceived', $data, function ($message) use ($data, $organiser) {
            $message->to($organiser->email, env('NOMBRE_CONTACTO'))
                ->from($data['sender_email'], $data['sender_name'])
                ->replyTo($data['sender_email'], $data['sender_name'])
                ->subject('HAS RECIBIDO UN MENSAJE DE BOTON DE ARREPENTIMIENTO');
        });

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.message_successfully_sent"),
        ]);
    }
}
