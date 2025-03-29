<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\EventAccessCodesController;
use App\Http\Controllers\EventAttendeesController;
use App\Http\Controllers\EventCheckInController;
use App\Http\Controllers\EventCheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventCustomizeController;
use App\Http\Controllers\EventDashboardController;
use App\Http\Controllers\EventOrdersController;
use App\Http\Controllers\EventPromoteController;
use App\Http\Controllers\EventSurveyController;
use App\Http\Controllers\EventTicketsController;
use App\Http\Controllers\EventViewController;
use App\Http\Controllers\EventViewEmbeddedController;
use App\Http\Controllers\EventWidgetsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\ManageAccountController;
use App\Http\Controllers\OrganiserController;
use App\Http\Controllers\OrganiserCustomizeController;
use App\Http\Controllers\OrganiserDashboardController;
use App\Http\Controllers\OrganiserEventsController;
use App\Http\Controllers\OrganiserViewController;
use App\Http\Controllers\RemindersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\UserLogoutController;
use App\Http\Controllers\UserSignupController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\GlobalEventController;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\TeatroController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\PaymentController;
use App\Models\User;

Route::get('/healthcheck', [PaymentController::class, 'healthCheck']);

Route::post('/procesarpago', [PaymentController::class, 'procesarpago']);

Route::get(
    'personas/{event_id}/check_in',
    [PersonasController::class, 'showCheckIn']
)->name('personas.showCheckIn');

Route::post(
    'personas/{event_id}/check_in/search',
    [PersonasController::class, 'postCheckInSearch']
)->name('personas.postCheckInSearch');

Route::post(
    'personas/{event_id}/check_in/',
    [PersonasController::class, 'postCheckInAttendee']
)->name('personas.postCheckInAttendee');

Route::post(
    'personas/{event_id}/qrcode_check_in',
    [PersonasController::class, 'postCheckInAttendeeQr']
)->name('personas.postQRCodeCheckInAttendee');

Route::get(
    'personas/{event_id}/qrcode_ccheck/{id}',
    [PersonasController::class, 'postCheckInAttendeeQr2']
)->name('personas.postQRCodeCheckInAttendee2');

Route::get(
    'personas/{event_id}/qrcode_ccheck2/{id}',
    [PersonasController::class, 'postCheckInAttendeeQr3']
)->name('personas.postQRCodeCheckInAttendee3');

Route::post(
    'personas/{event_id}/confirm_order_tickets/{order_id}',
    [PersonasController::class, 'confirmOrderTicketsQr']
)->name('personas.confirmCheckInOrderTickets');



// Rutas públicas para clientes
/*Route::get('iniciar-sesion', [ClienteController::class, 'showLoginForm'])->name('personas.login.form');
Route::post('iniciar-sesion', [ClienteController::class, 'login'])->name('personas.login');*/
Route::get('iniciar-sesion', [ClienteController::class, 'showLoginForm'])->name('personas.login.form')->middleware('guest:clientes');
Route::post('iniciar-sesion', [ClienteController::class, 'login'])->name('personas.login')->middleware('guest:clientes');

//Route::get('inicio', function () {return view('personas.index');})->name('personas.index');
Route::get('/', [PersonasController::class, 'index'])->name('personas.index');
Route::get('/evento-obras/{id}', [PersonasController::class, 'eventoObras'])->name('personas.eventoobras');


//Route::get('cambiar-pass', function () {return view('personas.cambiar-pass');})->name('personas.cambiarpass');
Route::get('cambiar-pass', [PersonasController::class, 'mostrarFormularioPass'])->name('personas.cambiarpass');
Route::get('recuperar-pass', [PersonasController::class, 'RecuperarPass'])->name('personas.recuperarp');
Route::post('recuperar-pass', [PersonasController::class, 'sendOtp'])->name('personas.claveotp');
Route::post('recuperar-pass2', [PersonasController::class, 'cambiarClaveconOtp'])->name('personas.cambiarclaveotp');

Route::get('preguntas-frecuentes', [PersonasController::class, 'preguntasFrecuentes'])->name('personas.preguntas-frecuentes');
Route::get('sobre-nosotros', [PersonasController::class, 'sobreNosotros'])->name('personas.sobre-nosotros');
Route::get('terminos-condiciones', [PersonasController::class, 'terminosCondiciones'])->name('personas.terminos-condiciones');
Route::get('contacto', [PersonasController::class, 'contactoForm'])->name('personas.contacto');
Route::get('formulario-arrepentimiento', [PersonasController::class, 'arrepentimientoForm'])->name('personas.formularioarrep');

// Ruta para procesar la solicitud de actualización de la contraseña
Route::post('actualizarPass/{id}', [PersonasController::class, 'actualizarPass'])->name('personas.actualizarpass');
Route::get('misCompras', [PersonasController::class, 'misCompras'])->name('personas.miscompras');
Route::get('ver-entradas/{id}', [PersonasController::class, 'verEntradas'])->name('personas.verentradas');
Route::post('editar-entrada/{event_id}/{attendee_id}', [PersonasController::class, 'EditarEntrada'])->name('personas.editarentrada');
//Route::get('misCompras', function () {return view('personas.misCompras');})->name('personas.miscompras');
//Route::get('obras', function () {return view('personas.obras');})->name('personas.obras');
Route::get('obras/{id}', [PersonasController::class, 'obras'])->name('personas.obras');
Route::get('obras-with-descripcion/{id}', [PersonasController::class, 'obrasWithDescripcion'])->name('personas.obrasWitDescripcion');

Route::get('obras/{id}/{idvendedor}', [PersonasController::class, 'obras3'])->name('personas.obras3');
/*Route::get('obras/{id}/{idvendedor}', [PersonasController::class, 'obras3'])
    ->name('personas.obras3')
    ->middleware('auth:clientes');*/

Route::get('compra-realizada/{id}', [PersonasController::class, 'compraRealizada'])->name('personas.compra-realizada');
Route::get('comprafallida', [PersonasController::class, 'compraFallida'])->name('personas.comprafallida');
Route::get('/buscar', [PersonasController::class, 'buscar'])->name('personas.buscar');

Route::get('categoria/{id}', [PersonasController::class, 'categoria'])->name('personas.categoria');

Route::get('controlingresoYysapBai/{id}', [PersonasController::class, 'controlingreso'])->name('personas.controlingreso');

Route::get('detalles-compra/{id}', [PersonasController::class, 'detalles'])->name('personas.detalles');

Route::get('/descargar-tickets/{referencia}', [PersonasController::class, 'descargarTickets'])->name('personas.descargar_tickets');

Route::post(
    'contact_organiser',
    [PersonasController::class, 'postContactOrganiser']
)->name('personas.postContactOrganiser');
Route::post(
    'contact_organiser2',
    [PersonasController::class, 'postContactOrganiser2']
)->name('personas.postContactOrganiser2');

Route::post(
    'obras/{event_id}/validate',
    [PersonasController::class, 'postValidateTickets']
)->name('personas.postValidateOrder2');

Route::post(
    '{event_id}/checkout/validate',
    [PersonasController::class, 'postValidateOrder']
)->name('personas.postValidateOrder');


Route::get(
    '{event_id}/checkout/create',
    [PersonasController::class, 'showEventCheckout']
)->name('personas.checkout');

Route::get('registro', [ClienteController::class, 'showRegistrationForm'])->name('personas.register.form');
Route::post('registro', [ClienteController::class, 'register'])->name('personas.register');

// Ruta para el dashboard (requerido para usuarios autenticados)
Route::get('panel', [PersonasController::class, 'dashboard'])->name('personas.dashboard')->middleware('auth:clientes');
//Route::get('editarPerfil', function () {return view('personas.editarPerfil');})->name('personas.editarperfil');
Route::get('editarPerfil', [PersonasController::class, 'editarPerfil'])->name('personas.editarperfil');
Route::post('actualizarPerfil/{id}', [PersonasController::class, 'actualizarPerfil'])->name('personas.actualizarPerfil');
// Ruta para cerrar sesión
Route::post('cerrar-sesion', [ClienteController::class, 'logout'])->name('personas.logout');

Route::get('storage/images/{filename}', function ($filename) {
    $path = storage_path('app/images/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return Response::make($file, 200)->header("Content-Type", $type);
});

Route::group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        /*
     * -------------------------
     * Installer
     * -------------------------
     */
        Route::get(
            'install0000Hiajayqhskaowuhds',
            [InstallerController::class, 'showInstaller']
        )->name('showInstaller');



        /* Route::post('install0000Hiajayqhskaowuhds',
        [InstallerController::class, 'postInstaller']
    )->name('postInstaller');

    Route::get('upgrade0000Hiajayqhskaowuhds',
        [InstallerController::class, 'showUpgrader']
    )->name('showUpgrader');

    Route::post('upgrade0000Hiajayqhskaowuhds',
        [InstallerController::class, 'postUpgrader']
    )->name('postUpgrader');*/

        /*
     * Logout
     */
        Route::any(
            'logout',
            [UserLogoutController::class, 'doLogout']
        )->name('logout');

        Route::group(['middleware' => ['installed']], function () {

            /*
         * Login
         */
            Route::get(
                'admin12102024/login',
                [UserLoginController::class, 'showLogin']
            )->name('login')->middleware('throttle:10,1');

            Route::post(
                'admin12102024/login',
                [UserLoginController::class, 'postLogin']
            );

            /*
         * Forgot password
         */
            Route::get(
                'admin12102024/login/forgot-password',
                [RemindersController::class, 'getRemind']
            )->name('forgotPassword');

            Route::post(
                'admin12102024/login/forgot-password',
                [RemindersController::class, 'postRemind']
            )->name('postForgotPassword')->middleware('throttle:3,1');

            /*
         * Reset Password
         */
            Route::get(
                'admin12102024/login/reset-password/{token}',
                [RemindersController::class, 'getReset']
            )->name('password.reset');

            Route::post(
                'admin12102024/login/reset-password',
                [RemindersController::class, 'postReset']
            )->name('postResetPassword')->middleware('throttle:3,1');


            Route::get('change', function () {
                $user = User::find(1);
                $user->password = bcrypt('123');

                $user->save();
            });

            /*
         * Registration / Account creation
         */
            Route::get(
                'admin12102024/signup',
                [UserSignupController::class, 'showSignup']
            )->name('showSignup');

            Route::post(
                'admin12102024/signup',
                [UserSignupController::class, 'postSignup']
            )->middleware('throttle:3,1');

            /*
         * Confirm Email
         */
            Route::get(
                'admin12102024/signup/confirm_email/{confirmation_code}',
                [UserSignupController::class, 'confirmEmail']
            )->name('confirmEmail')->middleware('throttle:3,1');
        });

        /*
     * Public organiser page routes
     */
        Route::group(['prefix' => 'o'], function () {

            Route::get(
                '/{organiser_id}/{organier_slug?}',
                [OrganiserViewController::class, 'showOrganiserHome']
            )->name('showOrganiserHome');
        });

        /*
     * Public event page routes
     */
        Route::group(['prefix' => 'e'], function () {

            /*
         * Embedded events
         */
            Route::get(
                '/{event_id}/embed',
                [EventViewEmbeddedController::class, 'showEmbeddedEvent']
            )->name('showEmbeddedEventPage');

            Route::get(
                '/{event_id}/calendar.ics',
                [EventViewController::class, 'showCalendarIcs']
            )->name('downloadCalendarIcs');

            Route::get(
                '/{event_id}/{event_slug?}',
                [EventViewController::class, 'showEventHome']
            )->name('showEventPage');

            Route::post(
                '/{event_id}/contact_organiser',
                [EventViewController::class, 'postContactOrganiser']
            )->name('postContactOrganiser');

            Route::post(
                '/{event_id}/show_hidden',
                [EventViewController::class, 'postShowHiddenTickets']
            )->name('postShowHiddenTickets');

            /*
         * Used for previewing designs in the backend. Doesn't log page views etc.
         */
            Route::get(
                '/{event_id}/preview',
                [EventViewController::class, 'showEventHomePreview']
            )->name('showEventPagePreview');

            Route::post(
                '{event_id}/checkout/',
                [EventCheckoutController::class, 'postValidateTickets']
            )->name('postValidateTickets');

            Route::post(
                '{event_id}/checkout/validate',
                [EventCheckoutController::class, 'postValidateOrder']
            )->name('postValidateOrder');

            Route::get(
                '{event_id}/checkout/payment',
                [EventCheckoutController::class, 'showEventPayment']
            )->name('showEventPayment');

            Route::get(
                '{event_id}/checkout/create',
                [EventCheckoutController::class, 'showEventCheckout']
            )->name('showEventCheckout');

            Route::get(
                '{event_id}/checkout/success',
                [EventCheckoutController::class, 'showEventCheckoutPaymentReturn']
            )->name('showEventCheckoutPaymentReturn');

            Route::post(
                '{event_id}/checkout/create',
                [EventCheckoutController::class, 'postCreateOrder']
            )->name('postCreateOrder');
        });

        /*
     * Public view order routes
     */
        Route::get(
            'order/{order_reference}',
            [EventCheckoutController::class, 'showOrderDetails']
        )->name('showOrderDetails');

        Route::get(
            'order/{order_reference}/tickets',
            [EventCheckoutController::class, 'showOrderTickets']
        )->name('showOrderTickets');

        Route::get(
            'order/{order_reference}/tickets/{ticket_reference}',
            [EventCheckoutController::class, 'showOrderTickets2']
        )->name('showOrderTickets2');

        /*
     * Backend routes
     */
        Route::group(['middleware' => ['auth', 'first.run']], function () {

            /*
         * Edit User
         */
            Route::group(['prefix' => 'user'], function () {

                Route::get(
                    'admin12102024/',
                    [UserController::class, 'showEditUser']
                )->name('showEditUser');

                Route::post(
                    'admin12102024/',
                    [UserController::class, 'postEditUser']
                )->name('postEditUser');
            });

            /*
         * Manage account
         */
            Route::group(['prefix' => 'account'], function () {

                Route::get(
                    'admin12102024/',
                    [ManageAccountController::class, 'showEditAccount']
                )->name('showEditAccount');

                Route::post(
                    'admin12102024/',
                    [ManageAccountController::class, 'postEditAccount']
                )->name('postEditAccount');

                Route::post(
                    '/edit_payment',
                    [ManageAccountController::class, 'postEditAccountPayment']
                )->name('postEditAccountPayment');

                Route::post(
                    'invite_user',
                    [ManageAccountController::class, 'postInviteUser']
                )->name('postInviteUser');
            });

            Route::get(
                'select_organiser',
                [OrganiserController::class, 'showSelectOrganiser']
            )->name('showSelectOrganiser');

            /*
         * Organiser routes
         */
            Route::group(['prefix' => 'admin12102024/'], function () {

                Route::get(
                    '{organiser_id}/dashboard',
                    [OrganiserDashboardController::class, 'showDashboard']
                )->name('showOrganiserDashboard');

                Route::get(
                    '{organiser_id}/perfiles',
                    [OrganiserDashboardController::class, 'showOPerfiles']
                )->name('showOrganiserPerfiles');
                Route::get(
                    '{organiser_id}/proobras',
                    [OrganiserDashboardController::class, 'showOObras']
                )->name('showOrganiserObras');

                Route::get('/perfil/{organiser_id}/evento/{id_evento}/aprobar', [OrganiserDashboardController::class, 'aprobar'])
                    ->name('perfil.aprobar');

                Route::get(
                    '/filter/obras/{organiserId}',
                    [OrganiserDashboardController::class, 'filterByOrganiser']
                )->name('filter.obras');


                Route::get(
                    '{organiser_id}/aprobaciones',
                    [OrganiserDashboardController::class, 'showOAprobaciones']
                )->name('showOrganiserAprobaciones');

                Route::get(
                    '{organiser_id}/ajustes',
                    [OrganiserDashboardController::class, 'showOAjustes']
                )->name('showOrganiserAjustes');

                Route::get(
                    '{organiser_id}/relaciones-publicas',
                    [OrganiserDashboardController::class, 'showORpublicas']
                )->name('showOrganiserRpublicas');

                Route::get('{organiser_id}/vendedor/crear', [OrganiserDashboardController::class, 'createVend'])->name('vendedor.create');
                Route::post('{organiser_id}/vendedor', [OrganiserDashboardController::class, 'storeVend'])->name('vendedor.store');
                Route::get('{organiser_id}/vendedor/{id}/edit', [OrganiserDashboardController::class, 'editarVend'])->name('vendedor.edit');
                Route::put('{organiser_id}/vendedor/{id}/update', [OrganiserDashboardController::class, 'actualizarVend'])->name('vendedor.update');
                Route::delete('/vendedor/{organiser_id}/eliminar/{id}', [OrganiserDashboardController::class, 'eliminarVend'])->name('vendedor.eliminar');

                Route::get('{organiser_id}/vendedor/{id}/obras', [OrganiserDashboardController::class, 'obrasVend'])->name('vendedor.obras');

                Route::get('{organiser_id}/vendedor/{id}/obras/{obra_id}/asignar', [OrganiserDashboardController::class, 'asignarobrasVend'])->name('vendedor.asignar');

                Route::delete('{organiser_id}/vendedor/{id}/eliminar/{aid}', [OrganiserDashboardController::class, 'eliminarobrasVend'])->name('asignar.eliminar');

                Route::get(
                    '{organiser_id}/crearperf',
                    [OrganiserDashboardController::class, 'showSignupPerf']
                )->name('crearperfil');

                Route::post(
                    '{organiser_id}/crearperf',
                    [OrganiserDashboardController::class, 'Crearperfil']
                )->name('perfil.store');

                Route::get('{organiser_id}/perfil/{id}/edit', [OrganiserDashboardController::class, 'editar'])->name('perfil.edit');
                Route::put('{organiser_id}/perfil/{id}/update', [OrganiserDashboardController::class, 'actualizar'])->name('perfil.update');
                Route::delete('/perfil/{organiser_id}/eliminar/{id}', [OrganiserDashboardController::class, 'eliminar'])->name('perfil.eliminar');


                Route::get('{organiser_id}/clientes', [ClienteController::class, 'index'])->name('clientes.index');
                Route::get('{organiser_id}/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
                Route::post('{organiser_id}/clientes', [ClienteController::class, 'store'])->name('clientes.store');
                Route::get('{organiser_id}/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
                Route::get('{organiser_id}/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
                Route::put('{organiser_id}/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
                Route::delete('{organiser_id}/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
                Route::get('{organiser_id}/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
                Route::get('{organiser_id}/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
                Route::post('{organiser_id}/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
                Route::get('{organiser_id}/categorias/{id}', [CategoriaController::class, 'show'])->name('categorias.show');
                Route::get('{organiser_id}/categorias/{id}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
                Route::put('{organiser_id}/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
                Route::delete('{organiser_id}/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

                Route::get('{organiser_id}/teatros', [TeatroController::class, 'index'])->name('teatros.index');
                Route::get('{organiser_id}/teatros/create', [TeatroController::class, 'create'])->name('teatros.create');
                Route::post('{organiser_id}/teatros', [TeatroController::class, 'store'])->name('teatros.store');
                Route::get('{organiser_id}/teatros/{id}/edit', [TeatroController::class, 'edit'])->name('teatros.edit');
                Route::put('{organiser_id}/teatros/{id}', [TeatroController::class, 'update'])->name('teatros.update');
                Route::delete('{organiser_id}/teatros/{id}', [TeatroController::class, 'destroy'])->name('teatros.destroy');


                Route::get('{organiser_id}/preguntas', [PreguntaController::class, 'index'])->name('preguntas.index');
                Route::get('{organiser_id}/preguntas/create', [PreguntaController::class, 'create'])->name('preguntas.create');
                Route::post('{organiser_id}/preguntas', [PreguntaController::class, 'store'])->name('preguntas.store');
                Route::get('{organiser_id}/preguntas/{id}', [PreguntaController::class, 'show'])->name('preguntas.show');
                Route::get('{organiser_id}/preguntas/{id}/edit', [PreguntaController::class, 'edit'])->name('preguntas.edit');
                Route::put('{organiser_id}/preguntas/{id}', [PreguntaController::class, 'update'])->name('preguntas.update');
                Route::delete('{organiser_id}/preguntas/{id}', [PreguntaController::class, 'destroy'])->name('preguntas.destroy');

                Route::get('{organiser_id}/banners', 'BannerController@index')->name('banners.index');
                Route::get('{organiser_id}/banners/create', 'BannerController@create')->name('banners.create');
                Route::post('{organiser_id}/banners', 'BannerController@store')->name('banners.store');
                Route::delete('{organiser_id}/banners/{id}', 'BannerController@destroy')->name('banners.destroy');
                // Ruta para editar un banner existente
                Route::get('{organiser_id}/banners/{id}/edit', 'BannerController@edit')->name('banners.edit');

                // Ruta para actualizar un banner existente
                Route::put('{organiser_id}/banners/{id}', 'BannerController@update')->name('banners.update');

                Route::get(
                    '{organiser_id}/events',
                    [OrganiserEventsController::class, 'showEvents']
                )->name('showOrganiserEvents');
                Route::get(
                    '{organiser_id}/eventsfunncion/{id_globalEvent}',
                    [OrganiserEventsController::class, 'showEventsFunciones']
                )->name('showOrganiserEventsFunciones');

                Route::get(
                    '{organiser_id}/global_events',
                    [GlobalEventController::class, 'showGlobalEvents']
                )->name('showOrganiserGlobalEvents');

                Route::get(
                    '{organiser_id}/archivadas',
                    [OrganiserEventsController::class, 'showArchivados']
                )->name('showOrganiserArchivados');

                Route::delete('{organiser_id}/events/{id}', [OrganiserEventsController::class, 'destroy'])->name('events.destroy');
                Route::post('{organiser_id}/events/archivar/{id}', [EventController::class, 'archivar'])->name('events.archivar');
                Route::post('{organiser_id}/globalevents/archivar/{id}', [GlobalEventController::class, 'archivar'])->name('globalevents.archivar');
                Route::post('{organiser_id}/events/desarchivar/{id}', [EventController::class, 'desarchivar'])->name('events.desarchivar');




                Route::get(
                    '{organiser_id}/customize',
                    [OrganiserCustomizeController::class, 'showCustomize']
                )->name('showOrganiserCustomize');

                Route::post(
                    '{organiser_id}/customize',
                    [OrganiserCustomizeController::class, 'postEditOrganiser']
                )->name('postEditOrganiser');

                Route::get(
                    'create',
                    [OrganiserController::class, 'showCreateOrganiser']
                )->name('showCreateOrganiser');

                Route::post(
                    'create',
                    [OrganiserController::class, 'postCreateOrganiser']
                )->name('postCreateOrganiser');

                Route::post(
                    '{organiser_id}/page_design',
                    [OrganiserCustomizeController::class, 'postEditOrganiserPageDesign']
                )->name('postEditOrganiserPageDesign');
            });

            /*
         * Events dashboard
         */
            Route::group(['prefix' => 'events'], function () {

                /*
             * ----------
             * Create Event
             * ----------
             */
                Route::get(
                    '/create',
                    [EventController::class, 'showCreateEvent']
                )->name('showCreateEvent');



                Route::post(
                    '/create',
                    [EventController::class, 'postCreateEvent']
                )->name('postCreateEvent');
            });

            Route::group(['prefix' => 'globalevents'], function () {

                /*
             * ----------
             * Create GLOBAL Event
             * ----------
             */
                Route::get(
                    '/create',
                    [GlobalEventController::class, 'showCreateGlobalEvent']
                )->name('showCreateGlobalEvent');



                Route::post(
                    '/create',
                    [GlobalEventController::class, 'postCreateGlobalEvent']
                )->name('postCreateGlobalEvent');
            });

            /*
         * Upload event images
         */
            Route::post(
                '/upload_image',
                [EventController::class, 'postUploadEventImage']
            )->name('postUploadEventImage');


            Route::group(['prefix' => 'admin12102024/control-evento'], function () {
                Route::get(
                    '{event_id}/customize',
                    [GlobalEventController::class, 'showCustomize']
                )->name('showGlobalEventCustomize');

                Route::post(
                    '{event_id}/customize',
                    [GlobalEventController::class, 'postEditGlobalEvent']
                )->name('postEditGlobalEvent');

            });
            /*
         * Event management routes
         */
            Route::group(['prefix' => 'admin12102024/control-obra'], function () {

                /*
             * Dashboard
             */
                Route::get(
                    '{event_id}/dashboard/',
                    [EventDashboardController::class, 'showDashboard']
                )->name('showEventDashboard');

                Route::get(
                    '{event_id}/',
                    [EventDashboardController::class, 'redirectToDashboard']
                );

                Route::post(
                    '{event_id}/go_live',
                    [EventController::class, 'postMakeEventLive']
                )->name('MakeEventLive');

                /*
             * -------
             * Tickets
             * -------
             */
                Route::get(
                    '{event_id}/tickets/',
                    [EventTicketsController::class, 'showTickets']
                )->name('showEventTickets');

                Route::get(
                    '{event_id}/tickets/edit/{ticket_id}',
                    [EventTicketsController::class, 'showEditTicket']
                )->name('showEditTicket');

                Route::post(
                    '{event_id}/tickets/edit/{ticket_id}',
                    [EventTicketsController::class, 'postEditTicket']
                )->name('postEditTicket');

                Route::get(
                    '{event_id}/tickets/create',
                    [EventTicketsController::class, 'showCreateTicket']
                )->name('showCreateTicket');

                Route::post(
                    '{event_id}/tickets/create',
                    [EventTicketsController::class, 'postCreateTicket']
                )->name('postCreateTicket');

                Route::post(
                    '{event_id}/tickets/delete',
                    [EventTicketsController::class, 'postDeleteTicket']
                )->name('postDeleteTicket');

                Route::post(
                    '{event_id}/tickets/pause',
                    [EventTicketsController::class, 'postPauseTicket']
                )->name('postPauseTicket');

                Route::post(
                    '{event_id}/tickets/order',
                    [EventTicketsController::class, 'postUpdateTicketsOrder']
                )->name('postUpdateTicketsOrder');

                /*
             * -------
             * Attendees
             * -------
             */
                Route::get(
                    '{event_id}/attendees/',
                    [EventAttendeesController::class, 'showAttendees']
                )->name('showEventAttendees');

                Route::get(
                    '{event_id}/attendees/message',
                    [EventAttendeesController::class, 'showMessageAttendees']
                )->name('showMessageAttendees');

                Route::post(
                    '{event_id}/attendees/message',
                    [EventAttendeesController::class, 'postMessageAttendees']
                )->name('postMessageAttendees');

                Route::get(
                    '{attendee_id}/attendees/single_message',
                    [EventAttendeesController::class, 'showMessageAttendee']
                )->name('showMessageAttendee');

                Route::post(
                    '{attendee_id}/attendees/single_message',
                    [EventAttendeesController::class, 'postMessageAttendee']
                )->name('postMessageAttendee');

                Route::get(
                    '{attendee_id}/attendees/resend_ticket',
                    [EventAttendeesController::class, 'showResendTicketToAttendee']
                )->name('showResendTicketToAttendee');

                Route::post(
                    '{attendee_id}/attendees/resend_ticket',
                    [EventAttendeesController::class, 'postResendTicketToAttendee']
                )->name('postResendTicketToAttendee');

                Route::get(
                    '{event_id}/attendees/invite',
                    [EventAttendeesController::class, 'showInviteAttendee']
                )->name('showInviteAttendee');

                Route::post(
                    '{event_id}/attendees/invite',
                    [EventAttendeesController::class, 'postInviteAttendee']
                )->name('postInviteAttendee');

                Route::get(
                    '{event_id}/attendees/import',
                    [EventAttendeesController::class, 'showImportAttendee']
                )->name('showImportAttendee');

                Route::post(
                    '{event_id}/attendees/import',
                    [EventAttendeesController::class, 'postImportAttendee']
                )->name('postImportAttendee');

                Route::get(
                    '{event_id}/attendees/print',
                    [EventAttendeesController::class, 'showPrintAttendees']
                )->name('showPrintAttendees');

                Route::get(
                    '{event_id}/attendees/{attendee_id}/export_ticket',
                    [EventAttendeesController::class, 'showExportTicket']
                )->name('showExportTicket');

                Route::get(
                    '{event_id}/attendees/{attendee_id}/ticket',
                    [EventAttendeesController::class, 'showAttendeeTicket']
                )->name('showAttendeeTicket');

                Route::get(
                    '{event_id}/attendees/export/{export_as?}',
                    [EventAttendeesController::class, 'showExportAttendees']
                )->name('showExportAttendees');

                Route::get(
                    '{event_id}/attendees/{attendee_id}/edit',
                    [EventAttendeesController::class, 'showEditAttendee']
                )->name('showEditAttendee');

                Route::post(
                    '{event_id}/attendees/{attendee_id}/edit',
                    [EventAttendeesController::class, 'postEditAttendee']
                )->name('postEditAttendee');

                Route::get(
                    '{event_id}/attendees/{attendee_id}/cancel',
                    [EventAttendeesController::class, 'showCancelAttendee']
                )->name('showCancelAttendee');

                Route::post(
                    '{event_id}/attendees/{attendee_id}/cancel',
                    [EventAttendeesController::class, 'postCancelAttendee']
                )->name('postCancelAttendee');

                /*
             * -------
             * Orders
             * -------
             */
                Route::get(
                    '{event_id}/orders/',
                    [EventOrdersController::class, 'showOrders']
                )->name('showEventOrders');

                Route::get(
                    'order/{order_id}',
                    [EventOrdersController::class, 'manageOrder']
                )->name('showManageOrder');

                Route::post(
                    'order/{order_id}/resend',
                    [EventOrdersController::class, 'resendOrder']
                )->name('resendOrder');

                Route::get(
                    'order/{order_id}/show/edit',
                    [EventOrdersController::class, 'showEditOrder']
                )->name('showEditOrder');

                Route::post(
                    'order/{order_id}/edit',
                    [EventOrdersController::class, 'postEditOrder']
                )->name('postOrderEdit');

                Route::get(
                    'order/{order_id}/cancel',
                    [EventOrdersController::class, 'showCancelOrder']
                )->name('showCancelOrder');

                Route::post(
                    'order/{order_id}/cancel',
                    [EventOrdersController::class, 'postCancelOrder']
                )->name('postCancelOrder');

                Route::post(
                    'order/{order_id}/mark_payment_received',
                    [EventOrdersController::class, 'postMarkPaymentReceived']
                )->name('postMarkPaymentReceived');

                Route::get(
                    '{event_id}/orders/export/{export_as?}',
                    [EventOrdersController::class, 'showExportOrders']
                )->name('showExportOrders');

                Route::get(
                    '{event_id}/orders/message/{order_id}',
                    [EventOrdersController::class, 'showMessageOrder']
                )->name('showMessageOrder');

                Route::post(
                    '{event_id}/orders/message/{order_id}',
                    [EventOrdersController::class, 'postMessageOrder']
                )->name('postMessageOrder');

                /*
             * -------
             * Edit Event
             * -------
             */
                Route::post(
                    '{event_id}/customize',
                    [EventController::class, 'postEditEvent']
                )->name('postEditEvent');

                /*
             * -------
             * Customize Design etc.
             * -------
             */
                Route::get(
                    '{event_id}/customize',
                    [EventCustomizeController::class, 'showCustomize']
                )->name('showEventCustomize');

                Route::get(
                    '{event_id}/customize/{tab?}',
                    [EventCustomizeController::class, 'showCustomize']
                )->name('showEventCustomizeTab');

                Route::post(
                    '{event_id}/customize/order_page',
                    [EventCustomizeController::class, 'postEditEventOrderPage']
                )->name('postEditEventOrderPage');

                Route::post(
                    '{event_id}/customize/design',
                    [EventCustomizeController::class, 'postEditEventDesign']
                )->name('postEditEventDesign');

                Route::post(
                    '{event_id}/customize/ticket_design',
                    [EventCustomizeController::class, 'postEditEventTicketDesign']
                )->name('postEditEventTicketDesign');

                Route::post(
                    '{event_id}/customize/social',
                    [EventCustomizeController::class, 'postEditEventSocial']
                )->name('postEditEventSocial');

                Route::post(
                    '{event_id}/customize/fees',
                    [EventCustomizeController::class, 'postEditEventFees']
                )->name('postEditEventFees');

                /*
             * -------
             * Event Widget page
             * -------
             */
                Route::get(
                    '{event_id}/widgets',
                    [EventWidgetsController::class, 'showEventWidgets']
                )->name('showEventWidgets');

                /*
             * -------
             * Event Access Codes page
             * -------
             */
                Route::get(
                    '{event_id}/access_codes',
                    [EventAccessCodesController::class, 'show']
                )->name('showEventAccessCodes');

                Route::get(
                    '{event_id}/access_codes/create',
                    [EventAccessCodesController::class, 'showCreate']
                )->name('showCreateEventAccessCode');

                Route::post(
                    '{event_id}/access_codes/create',
                    [EventAccessCodesController::class, 'postCreate']
                )->name('postCreateEventAccessCode');

                Route::post(
                    '{event_id}/access_codes/{access_code_id}/delete',
                    [EventAccessCodesController::class, 'postDelete']
                )->name('postDeleteEventAccessCode');

                /*
             * -------
             * Event Survey page
             * -------
             */
                Route::get(
                    '{event_id}/surveys',
                    [EventSurveyController::class, 'showEventSurveys']
                )->name('showEventSurveys');

                Route::get(
                    '{event_id}/question/create',
                    [EventSurveyController::class, 'showCreateEventQuestion']
                )->name('showCreateEventQuestion');

                Route::post(
                    '{event_id}/question/create',
                    [EventSurveyController::class, 'postCreateEventQuestion']
                )->name('postCreateEventQuestion');

                Route::get(
                    '{event_id}/question/{question_id}',
                    [EventSurveyController::class, 'showEditEventQuestion']
                )->name('showEditEventQuestion');

                Route::post(
                    '{event_id}/question/{question_id}',
                    [EventSurveyController::class, 'postEditEventQuestion']
                )->name('postEditEventQuestion');

                Route::post(
                    '{event_id}/question/delete/{question_id}',
                    [EventSurveyController::class, 'postDeleteEventQuestion']
                )->name('postDeleteEventQuestion');

                Route::get(
                    '{event_id}/question/{question_id}/answers',
                    [EventSurveyController::class, 'showEventQuestionAnswers']
                )->name('showEventQuestionAnswers');

                Route::post(
                    '{event_id}/questions/update_order',
                    [EventSurveyController::class, 'postUpdateQuestionsOrder']
                )->name('postUpdateQuestionsOrder');

                Route::get(
                    '{event_id}/answers/export/{export_as?}',
                    [EventSurveyController::class, 'showExportAnswers']
                )->name('showExportAnswers');

                Route::post(
                    '{event_id}/question/{question_id}/enable',
                    [EventSurveyController::class, 'postEnableQuestion']
                )->name('postEnableQuestion');


                /*
             * -------
             * Check In App
             * -------
             */


                Route::get(
                    '{event_id}/check_in',
                    [EventCheckInController::class, 'showCheckIn']
                )->name('showCheckIn');

                Route::post(
                    '{event_id}/check_in/search',
                    [EventCheckInController::class, 'postCheckInSearch']
                )->name('postCheckInSearch');

                Route::post(
                    '{event_id}/check_in/',
                    [EventCheckInController::class, 'postCheckInAttendee']
                )->name('postCheckInAttendee');

                Route::post(
                    '{event_id}/qrcode_check_in',
                    [EventCheckInController::class, 'postCheckInAttendeeQr']
                )->name('postQRCodeCheckInAttendee');

                Route::post(
                    '{event_id}/confirm_order_tickets/{order_id}',
                    [EventCheckInController::class, 'confirmOrderTicketsQr']
                )->name('confirmCheckInOrderTickets');


                /*
             * -------
             * Promote
             * -------
             */
                Route::get(
                    '{event_id}/promote',
                    [EventPromoteController::class, 'showPromote']
                )->name('showEventPromote');
            });
        });





        /*
    Route::get('/',
        [IndexController::class, 'showIndex']
    )->name('index');
});*/
        Route::get(
            'admin12102024/',
            [IndexController::class, 'showIndex']
        )->name('index');
    }
);
