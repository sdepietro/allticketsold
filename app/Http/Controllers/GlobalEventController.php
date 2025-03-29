<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Event;
use App\Models\GlobalEvent;
use App\Models\Organiser;
use App\Models\Teatro2;
use Auth;
use Illuminate\Http\Request;
use Image;

use Log;
use Validator;

class GlobalEventController extends Controller
{

    public function showGlobalEvents(Request $request, $organiser_id)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);
        //$organiser_id=11;

        $allowed_sorts = ['created_at',  'title'];

        $searchQuery = $request->get('q');
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'created_at');


        if ($organiser_id == 1) {
            $events = $searchQuery
                ? GlobalEvent::with(['organiser'])
                ->where('title', 'like', '%' . $searchQuery . '%')
                ->orderBy($sort_by, 'desc')
                ->paginate(12)
                : GlobalEvent::with(['organiser'])
                ->where('estado', 'Activo')
                ->orderBy($sort_by, 'desc')
                ->paginate(12);
        } else {
            $events = $searchQuery
                ? GlobalEvent::with(['organiser'])
                ->where('title', 'like', '%' . $searchQuery . '%')
                ->where('organiser_id', '=', $organiser_id)
                ->orderBy($sort_by, 'desc')
                ->paginate(12)
                : GlobalEvent::with(['organiser'])
                ->with(['organiser'])
                ->where('organiser_id', '=', $organiser_id)
                ->where('estado', 'Activo')
                ->orderBy($sort_by, 'desc')
                ->paginate(12);

            $events2 = $searchQuery
                ? GlobalEvent::with(['organiser'])
                ->where('title', 'like', '%' . $searchQuery . '%')
                ->orderBy($sort_by, 'desc')
                ->where('estado', 'Activo')
                ->where('organiser_id', '=', $organiser_id)
                ->paginate(12)
                : GlobalEvent::with(['organiser'])
                ->where('estado', 'Activo')
                ->where('organiser_id', '=', $organiser_id)
                ->orderBy($sort_by, 'desc')
                ->paginate(12);
        }

        /*$events2 = $searchQuery
            ? Event::scope()->with(['organiser', 'currency'])->where('title', 'like', '%' . $searchQuery . '%')->orderBy($sort_by,
                'desc')->where('location_address_line_1', '!=', '1111')->where('location_address_line_1', '=', '1')->where('organiser_id', '=', $organiser_id)->paginate(12)
            : Event::scope()->with(['organiser', 'currency'])->where('location_address_line_1', '!=', '1111')->where('location_address_line_1', '=', '1')->where('organiser_id', '=', $organiser_id)->orderBy($sort_by, 'desc')->paginate(12);
*/
        /*$events2 = $searchQuery
    ? Event::scope()
        ->with(['organiser', 'currency'])
        ->where('title', 'like', '%' . $searchQuery . '%')
        ->orderBy($sort_by, 'desc')
        ->where(function ($query) {
            $query->where('location_address_line_1', '=', '1')
                  ->where('location_address_line_1', '!=', '1111');
        })
        ->where('organiser_id', '=', $organiser_id)
        ->paginate(12)
    : Event::scope()
        ->with(['organiser', 'currency'])
        ->where(function ($query) {
            $query->where('location_address_line_1', '=', '1')
                  ->where('location_address_line_1', '!=', '1111');
        })
        ->where('organiser_id', '=', $organiser_id)
        ->orderBy($sort_by, 'desc')
        ->paginate(12);*/



        $data = [
            'events'    => $events,
            //  'events2'    => $events2,
            'organiser' => $organiser,
            'search'    => [
                'q'        => $searchQuery ? $searchQuery : '',
                'sort_by'  => $request->get('sort_by') ? $request->get('sort_by') : '',
                'showPast' => $request->get('past'),
            ],
        ];

        return view('ManageOrganiser.GlobalEvents', $data);
    }


    public function showCreateGlobalEvent(Request $request)
    {
        $teatros = Teatro2::all();
        $categoria = Categoria::all();
        $teatros = Teatro2::all();
        $data = [
            'modal_id'     => $request->get('modal_id'),
            'organisers'   => Organiser::scope()->pluck('name', 'id'),
            'categorias' => $categoria,
            'teatros' => $teatros,
            'organiser_id' => $request->get('organiser_id') ? $request->get('organiser_id') : false,
        ];

        return view('ManageOrganiser.Modals.CreateGlobalEvent', $data);
    }


    public function showCustomize($event_id = '', $tab = '')
    {
        $event = GlobalEvent::find($event_id);
        $organiser_id = $event->organiser_id;
        $organiser = $event->organiser;
        $categorias = Categoria::all();
        $teatros = Teatro2::all();
        return view('ManageEvent.CustomizeGlobalEvent', get_defined_vars());
    }

    public function postCreateGlobalEvent(Request $request)
    {
        Log::info('Uploaded files:', $request->files->all());
        $event = new GlobalEvent();
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

        $event->title = $request->get('title');
        $event->description = prepare_markdown($request->get('description'));
        $event->teatro_id = $request->teatro_id;
        $event->estado = $request->estado;
        //try {
        $event->save();
        //   } catch (\Exception $e) {
        // //  Log::error($e);
        //  return response()->json([
        //     'status'   => 'error',
        //    'messages' => trans("Controllers.event_create_exception"),
        // ]);
        // }

        // if ($request->hasFile('img_mini')) {
        //     $request->file('img_mini')->storeAs('public/global_event/' . $event->id  . '/', $request->file('img_mini')->getClientOriginalName());
        //     $event->img_mini = url('/') . '/storage/global_event/' . $event->id  . '/' . ($request->file('img_mini')->getClientOriginalName());
        //     $event->save();
        // }

        if ($request->hasFile('img_main')) {
            $request->file('img_main')->storeAs('public/global_event/' . $event->id  . '/', $request->file('img_main')->getClientOriginalName());
            $event->img_main = url('/') . '/storage/global_event/' . $event->id  . '/' . ($request->file('img_main')->getClientOriginalName());
            $event->save();
        }

        if ($request->hasFile('img_sinopsi')) {
            $request->file('img_sinopsi')->storeAs('public/global_event/' . $event->id  . '/', $request->file('img_sinopsi')->getClientOriginalName());
            $event->img_sinopsi = url('/') . '/storage/global_event/' . $event->id  . '/' . ($request->file('img_sinopsi')->getClientOriginalName());
            $event->save();
        }

        if ($request->hasFile('img_mini')) {
            $file = $request->file('img_mini');
            Log::info('Received file Event: ' . $file->getClientOriginalName() . ' (' . $file->getSize() . ' bytes)');
            $path = public_path() . '/' . config('attendize.event_images_path');
            $filename = 'img_mini-' . md5(time() . $event->id) . '.' . strtolower($request->file('img_mini')->getClientOriginalExtension());
            $file_full_path = $path . '/' . $filename;
            $request->file('img_mini')->move($path, $filename);
            $img = Image::make($file_full_path);
            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($file_full_path);
            $event->img_mini = config('attendize.event_images_path') . '/' . $filename;
        }

        $event->destacado = $request->destacado;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;

        $event->save();

        $event->categorias()->attach($request->categorias);
        return response()->json([
            'status'      => 'success',
            'id'          => $event->id,
            'redirectUrl' => route('showOrganiserGlobalEvents', [
                'organiser_id'  => $request->get('organiser_id')

            ]),
        ]);
    }



    public function    archivar($organiser_id, $id)
    {
        $event = GlobalEvent::find($id);
        $event->estado = "Archivado";
        $event->save();
        return response()->json([
            'status'  => 'success',
            'message' => 'Evento archivado exitosamente',
        ]);
    }

    public function postEditGlobalEvent(Request $request, $event_id)
    {
        $event = GlobalEvent::find($event_id);
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


        $event->teatro_id = $request->teatro_id;
        $event->title = $request->get('title');
        $event->description = prepare_markdown($request->get('description'));

        //try {
        $event->save();
        //   } catch (\Exception $e) {
        // //  Log::error($e);
        //  return response()->json([
        //     'status'   => 'error',
        //    'messages' => trans("Controllers.event_create_exception"),
        // ]);
        // }

        // if ($request->hasFile('img_mini')) {
        //     $request->file('img_mini')->storeAs('public/global_event/' . $event->id  . '/', $request->file('img_mini')->getClientOriginalName());
        //     $event->img_mini = url('/') . '/storage/global_event/' . $event->id  . '/' . ($request->file('img_mini')->getClientOriginalName());
        //     $event->save();
        // }

        if ($request->hasFile('img_main')) {
            $request->file('img_main')->storeAs('public/global_event/' . $event->id  . '/', $request->file('img_main')->getClientOriginalName());
            $event->img_main = url('/') . '/storage/global_event/' . $event->id  . '/' . ($request->file('img_main')->getClientOriginalName());
            $event->save();
        }





        if ($request->hasFile('img_mini')) {
            $file = $request->file('img_mini');
            Log::info('Received file Event: ' . $file->getClientOriginalName() . ' (' . $file->getSize() . ' bytes)');
            $path = public_path() . '/' . config('attendize.event_images_path');
            $filename = 'img_mini-' . md5(time() . $event->id) . '.' . strtolower($request->file('img_mini')->getClientOriginalExtension());

            $file_full_path = $path . '/' . $filename;

            $request->file('img_mini')->move($path, $filename);

            $img = Image::make($file_full_path);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });



            $img->save($file_full_path);

            $event->img_mini = config('attendize.event_images_path') . '/' . $filename;
        }

        $event->destacado = $request->destacado;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->save();

        $event->categorias()->sync($request->categorias);
        return response()->json([
            'status'      => 'success',
            'id'          => $event->id,
            'redirectUrl' => route('showOrganiserGlobalEvents', [
                'organiser_id'  => $request->get('organiser_id')

            ]),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
