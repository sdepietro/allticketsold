<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Image;
use Validator;
use App\Models\Event;
use App\Models\Organiser;
use App\Models\EventImage;
use App\Models\Categoria;
use App\Models\GlobalEvent;
use App\Models\Teatro2;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event as GCEvent;
use Carbon\Carbon;

class EventController extends MyBaseController
{
    /**
     * Show the 'Create Event' Modal
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showCreateEvent(Request $request)
    {
        $categoria = Categoria::all();
        $teatros = Teatro2::all();
        $global_events = GlobalEvent::where('estado', 'Activo')->get();
        $data = [
            'modal_id'     => $request->get('modal_id'),
            'organisers'   => Organiser::scope()->pluck('name', 'id'),
            'categorias' => $categoria,
            'teatros' => $teatros,
            'organiser_id' => $request->get('organiser_id') ? $request->get('organiser_id') : false,
            'global_events' => $global_events,
        ];

        return view('ManageOrganiser.Modals.CreateEvent', $data);
    }

    /**
     * Create an event
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateEvent(Request $request)
    {
        Log::info('Uploaded files:', $request->files->all());

        $event = Event::createNew();
        $global_event = GlobalEvent::find($request->global_event_id);
        $teatro = Teatro2::find($global_event->teatro_id);


        $request->merge(['start_date' =>Carbon::parse($request->get('country_short'))->format('Y-m-d H:i') ]);
        $request->merge(['end_date' => Carbon::parse($request->get('country_short'))->format('Y-m-d H:i')]);
        $request->merge(['description' =>  $request->get('title')]);
        $request->merge(['location_venue_name' =>  $teatro->nombre]);
        $request->merge(['venue_name_full' =>  $teatro->nombre]);


        if (!$event->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $event->errors(),
            ]);
        }

        if (!isset($request->global_event_id)) {
            return response()->json([
                'status'   => 'error',
                'messages' => 'No de encuentra el evento asociado a la funcion',
            ]);
        }




        $event->title = $request->get('title');
        $event->description = $request->get('title');
        $event->global_event_id = $request->get('global_event_id');
        $event->description = prepare_markdown($request->get('description'));
        $event->start_date = $request->get('start_date');
        $event->end_date = $request->get('end_date');
        // $event->location_country_code = $request->get('country_short');

        $formattedDate = Carbon::parse($request->get('country_short'))->format('d/m/Y H:i');
        $event->location_country_code = $formattedDate;


        if ($request->hasFile('imagengrande')) {
            $file2 = $request->file('imagengrande');
            Log::info('Received file IG: ' . $file2->getClientOriginalName() . ' (' . $file2->getSize() . ' bytes)');
            $event->imagengrande = $request->file('imagengrande')->store('images');
        }
        if ($request->hasFile('imagenminiatura')) {
            $file3 = $request->file('imagenminiatura');
            Log::info('Received file IM: ' . $file3->getClientOriginalName() . ' (' . $file3->getSize() . ' bytes)');
            $event->imagenminiatura = $request->file('imagenminiatura')->store('images');
        }
        /*
         * Venue location info (Usually auto-filled from google maps)
         */

        $is_auto_address = (trim($request->get('place_id')) !== '');

        if ($is_auto_address) { /* Google auto filled */
            $event->venue_name = $request->get('name');
            $event->venue_name_full = $request->get('venue_name_full');
            $event->location_lat = $request->get('lat');
            $event->location_long = $request->get('lng');
            $event->location_address = $request->get('formatted_address');
            $event->location_country = $request->get('country');
            //$event->location_country_code = $request->get('country_short');
            $event->location_state = $request->get('administrative_area_level_1');
            $event->location_address_line_1 = $request->get('route');
            $event->location_address_line_2 = $request->get('locality');
            $event->location_post_code = $request->get('postal_code');
            $event->location_street_number = $request->get('street_number');
            $event->location_google_place_id = $request->get('place_id');
            $event->location_is_manual = 0;
        } else { /* Manually entered */

            $event->venue_name = $global_event->teatro->id;
            $event->location_address_line_1 = $request->get('location_address_line_1');
            $event->location_address_line_2 = $global_event->categorias->last()->id;
            $event->location_state = $request->get('location_state');
            $event->location_post_code = $request->get('location_post_code');
            $event->location_is_manual = 1;
        }

        $event->end_date = $request->get('end_date');

        $event->currency_id = Auth::user()->account->currency_id;
        //$event->timezone_id = Auth::user()->account->timezone_id;
        /*
         * Set a default background for the event
         */
        $event->bg_type = 'image';
        $event->bg_image_path = config('attendize.event_default_bg_image');


        if ($request->get('organiser_name')) {
            $organiser = Organiser::createNew(false, false, true);

            $rules = [
                'organiser_name'  => ['required'],
                'organiser_email' => ['required', 'email'],
            ];
            $messages = [
                'organiser_name.required' => trans("Controllers.no_organiser_name_error"),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validator->messages()->toArray(),
                ]);
            }

            $organiser->name = $request->get('organiser_name');
            $organiser->about = prepare_markdown($request->get('organiser_about'));
            $organiser->email = $request->get('organiser_email');
            $organiser->facebook = $request->get('organiser_facebook');
            $organiser->twitter = $request->get('organiser_twitter');
            $organiser->save();
            $event->organiser_id = $organiser->id;
        } elseif ($request->get('organiser_id')) {
            $event->organiser_id = $request->get('organiser_id');
        } else { /* Somethings gone horribly wrong */
            return response()->json([
                'status'   => 'error',
                'messages' => trans("Controllers.organiser_other_error"),
            ]);
        }

        /*
         * Set the event defaults.
         * @todo these could do mass assigned
         */
        $defaults = $event->organiser->event_defaults;
        if ($defaults) {
            $event->organiser_fee_fixed = $defaults->organiser_fee_fixed;
            $event->organiser_fee_percentage = $defaults->organiser_fee_percentage;
            $event->pre_order_display_message = $defaults->pre_order_display_message;
            $event->post_order_display_message = $defaults->post_order_display_message;
            $event->offline_payment_instructions = prepare_markdown($defaults->offline_payment_instructions);
            $event->enable_offline_payments = $defaults->enable_offline_payments;
            $event->social_show_facebook = $defaults->social_show_facebook;
            $event->social_show_linkedin = $defaults->social_show_linkedin;
            $event->social_show_twitter = $defaults->social_show_twitter;
            $event->social_show_email = $defaults->social_show_email;
            $event->social_show_whatsapp = $defaults->social_show_whatsapp;
            $event->is_1d_barcode_enabled = $defaults->is_1d_barcode_enabled;
            $event->ticket_border_color = $defaults->ticket_border_color;
            $event->ticket_bg_color = $defaults->ticket_bg_color;
            $event->ticket_text_color = $defaults->ticket_text_color;
            $event->ticket_sub_text_color = $defaults->ticket_sub_text_color;
        }




        try {
            if (Auth::user()->id == 1 || Auth::user()->id == 2) {
                $event->is_live = 1;
            }

            $event->save();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'   => 'error',
                'messages' => trans("Controllers.event_create_exception"),
            ]);
        }

        if ($request->hasFile('event_image')) {
            $file = $request->file('event_image');
            Log::info('Received file Event: ' . $file->getClientOriginalName() . ' (' . $file->getSize() . ' bytes)');
            $path = public_path() . '/' . config('attendize.event_images_path');
            $filename = 'event_image-' . md5(time() . $event->id) . '.' . strtolower($request->file('event_image')->getClientOriginalExtension());

            $file_full_path = $path . '/' . $filename;

            $request->file('event_image')->move($path, $filename);

            $img = Image::make($file_full_path);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($file_full_path);

            /* Upload to s3 */
            \Storage::put(config('attendize.event_images_path') . '/' . $filename, file_get_contents($file_full_path));


            $eventImage = EventImage::createNew();
            $eventImage->image_path = config('attendize.event_images_path') . '/' . $filename;
            $eventImage->event_id = $event->id;
            $eventImage->save();
        }

        return response()->json([
            'status'      => 'success',
            'id'          => $event->id,
            'redirectUrl' => route('showEventTickets', [
                'event_id'  => $event->id,
                'first_run' => 'yup',
            ]),
        ]);
    }

    /**
     * Edit an event
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEditEvent(Request $request, $event_id)
    {
        $event = Event::scope()->findOrFail($event_id);
        $global_event = GlobalEvent::find( $event->global_event_id);
        $teatro = Teatro2::find($global_event->teatro_id);

        $request->merge(['start_date' =>Carbon::parse(($global_event->start_date))->format('Y-m-d H:i') ]);
        $request->merge(['end_date' => Carbon::parse(($global_event->end_date))->format('Y-m-d H:i')]);
        $request->merge(['description' => $global_event->title]);
        $request->merge(['location_venue_name' =>  $teatro->nombre]);
        $request->merge(['venue_name_full' =>  $teatro->nombre]);

        if (!$event->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $event->errors(),
            ]);
        }
        if (!isset($request->global_event_id)) {
            return response()->json([
                'status'   => 'error',
                'messages' => 'No de encuentra el evento asociado a la funcion',
            ]);
        }

        $event->global_event_id = $request->get('global_event_id');

        $event->is_live = $request->get('is_live');
        $event->currency_id = $request->get('currency_id');
        $event->title = $request->get('title');
        $event->description = prepare_markdown($request->get('description'));
        $event->start_date = $request->get('start_date');
        $event->google_tag_manager_code = $request->get('google_tag_manager_code');
        $event->location_country = $request->get('country');
        $formattedDate = Carbon::parse($request->get('country_short'))->format('d/m/Y H:i');
        $event->location_country_code = $formattedDate;

        if ($request->hasFile('imagengrande')) {
            $event->imagengrande = $request->file('imagengrande')->store('images');
        }
        if ($request->hasFile('imagenminiatura')) {
            $event->imagenminiatura = $request->file('imagenminiatura')->store('images');
        }

        /*
         * If the google place ID is the same as before then don't update the venue
         */
        if (($request->get('place_id') !== $event->location_google_place_id) || $event->location_google_place_id == '') {
            $is_auto_address = (trim($request->get('place_id')) !== '');

            if ($is_auto_address) { /* Google auto filled */
                $event->venue_name = $request->get('name');
                $event->venue_name_full = $request->get('venue_name_full');
                $event->location_lat = $request->get('lat');
                $event->location_long = $request->get('lng');
                $event->location_address = $request->get('formatted_address');
                $event->location_state = $request->get('administrative_area_level_1');
                $event->location_address_line_1 = $request->get('route');

                $event->location_post_code = $request->get('postal_code');
                $event->location_street_number = $request->get('street_number');
                $event->location_google_place_id = $request->get('place_id');
                $event->location_is_manual = 0;
            } else { /* Manually entered */
                $event->venue_name = $global_event->teatro->id;
                $event->location_address_line_1 = $request->get('location_address_line_1');
                $event->location_address_line_2 = $global_event->categorias->last()->id;
                $event->location_state = $request->get('location_state');

                $event->location_post_code = $request->get('location_post_code');
                $event->location_is_manual = 1;
                $event->location_google_place_id = '';
                $event->venue_name_full = '';
                $event->location_lat = '';
                $event->location_long = '';
                $event->location_address = '';

                $event->location_street_number = '';
            }
        }

        $event->end_date = $request->get('end_date');
        $event->event_image_position = $request->get('event_image_position');

        if ($request->get('remove_current_image') == '1') {
            EventImage::where('event_id', '=', $event->id)->delete();
        }

        $event->save();

        if ($request->hasFile('event_image')) {
            $path = public_path() . '/' . config('attendize.event_images_path');
            $filename = 'event_image-' . md5(time() . $event->id) . '.' . strtolower($request->file('event_image')->getClientOriginalExtension());

            $file_full_path = $path . '/' . $filename;

            $request->file('event_image')->move($path, $filename);

            $img = Image::make($file_full_path);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($file_full_path);

            \Storage::put(config('attendize.event_images_path') . '/' . $filename, file_get_contents($file_full_path));

            EventImage::where('event_id', '=', $event->id)->delete();

            $eventImage = EventImage::createNew();
            $eventImage->image_path = config('attendize.event_images_path') . '/' . $filename;
            $eventImage->event_id = $event->id;
            $eventImage->save();
        }

        return response()->json([
            'status'      => 'success',
            'id'          => $event->id,

            'message'     => trans("Controllers.event_successfully_updated"),
            'redirectUrl' => '',
        ]);
    }

    /**
     * Upload event image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUploadEventImage(Request $request)
    {
        if ($request->hasFile('event_image')) {
            $the_file = \File::get($request->file('event_image')->getRealPath());
            $file_name = 'event_details_image-' . md5(microtime()) . '.' . strtolower($request->file('event_image')->getClientOriginalExtension());

            $relative_path_to_file = config('attendize.event_images_path') . '/' . $file_name;
            $full_path_to_file = public_path() . '/' . $relative_path_to_file;

            $img = Image::make($the_file);

            $img->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($full_path_to_file);
            if (\Storage::put($file_name, $the_file)) {
                return response()->json([
                    'link' => '/' . $relative_path_to_file,
                ]);
            }

            return response()->json([
                'error' => trans("Controllers.image_upload_error"),
            ]);
        }
    }

    /**
     * Puplish event and redirect
     * @param  Integer|false $event_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postMakeEventLive($event_id = false)
    {
        $event = Event::scope()->findOrFail($event_id);
        $event->is_live = 1;
        $event->save();
        \Session::flash('message', trans('Event.go_live'));

        return redirect()->action(
            'EventDashboardController@showDashboard',
            ['event_id' => $event_id]
        );
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully',
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete event',
            ]);
        }
    }

    public function archivar($organiser_id, $id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->location_address_line_1 = '1'; // Actualizar el campo a 1
            $event->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Funcion archivado exitosamente',
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'  => 'error',
                'message' => 'Error al archivar el evento',
            ]);
        }
    }

    public function desarchivar($organiser_id, $id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->location_address_line_1 = '0'; // Actualizar el campo a 1
            $event->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Funcion desarchivado exitosamente',
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'  => 'error',
                'message' => 'Error al desarchivar el evento',
            ]);
        }
    }
}
