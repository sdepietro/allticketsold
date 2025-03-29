<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organiser;
use Illuminate\Http\Request;

class OrganiserEventsController extends MyBaseController
{
    /**
     * Show the organiser events page
     *
     * @param Request $request
     * @param $organiser_id
     * @return mixed
     */



     public function showEventsFunciones(Request $request, $organiser_id,$id_globalEvent)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);
        $allowed_sorts = ['created_at', 'start_date', 'end_date', 'title'];
        $searchQuery = $request->get('q');
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'start_date');
        if ($organiser_id == 1) {
            $events = $searchQuery
                ? Event::with(['organiser', 'currency'])
                ->where('global_event_id',$id_globalEvent)
                ->where('title', 'like', '%' . $searchQuery . '%')
                ->where(function ($query) {
                    $query->where('location_address_line_1', '<>', '1')
                        ->where('location_address_line_1', '!=', '1111')
                        ->orWhereNull('location_address_line_1');
                })

                ->orderBy($sort_by, 'desc')
                ->paginate(12)
                : Event::with(['organiser', 'currency'])
                ->where('global_event_id',$id_globalEvent)
                ->where(function ($query) {
                    $query->where('location_address_line_1', '<>', '1')
                        ->where('location_address_line_1', '!=', '1111')
                        ->orWhereNull('location_address_line_1');
                })

                ->orderBy($sort_by, 'desc')
                ->paginate(12);


            $events2 = $searchQuery
                ? Event::with(['organiser', 'currency'])
                ->where('global_event_id',$id_globalEvent)
                ->where('title', 'like', '%' . $searchQuery . '%')
                ->orderBy($sort_by, 'desc')
                ->where(function ($query) {
                    $query->where('location_address_line_1', '=', '1')
                        ->where('location_address_line_1', '!=', '1111');
                })

                ->paginate(12)
                : Event::with(['organiser', 'currency'])
                ->where('global_event_id',$id_globalEvent)
                ->where(function ($query) {
                    $query->where('location_address_line_1', '=', '1')
                        ->where('location_address_line_1', '!=', '1111');
                })

                ->orderBy($sort_by, 'desc')
                ->paginate(12);
        } else {
            $events = $searchQuery
                ? Event::scope()
                ->where('global_event_id',$id_globalEvent)
                ->with(['organiser', 'currency'])
                ->where('title', 'like', '%' . $searchQuery . '%')
                ->where(function ($query) {
                    $query->where('location_address_line_1', '<>', '1')
                        ->where('location_address_line_1', '!=', '1111')
                        ->orWhereNull('location_address_line_1');
                })
                ->where('organiser_id', '=', $organiser_id)
                ->orderBy($sort_by, 'desc')
                ->paginate(12)
                : Event::scope()
                ->where('global_event_id',$id_globalEvent)
                ->with(['organiser', 'currency'])
                ->where(function ($query) {
                    $query->where('location_address_line_1', '<>', '1')
                        ->where('location_address_line_1', '!=', '1111')
                        ->orWhereNull('location_address_line_1');
                })
                ->where('organiser_id', '=', $organiser_id)
                ->orderBy($sort_by, 'desc')
                ->paginate(12);

            $events2 = $searchQuery
                ? Event::scope()
                ->where('global_event_id',$id_globalEvent)
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
                ->where('global_event_id',$id_globalEvent)
                ->with(['organiser', 'currency'])
                ->where(function ($query) {
                    $query->where('location_address_line_1', '=', '1')
                        ->where('location_address_line_1', '!=', '1111');
                })
                ->where('organiser_id', '=', $organiser_id)
                ->orderBy($sort_by, 'desc')
                ->paginate(12);
        }





        $data = [
            'id_gevent'=>$id_globalEvent,
            'events'    => $events,
            'events2'    => $events2,
            'organiser' => $organiser,
            'search'    => [
                'q'        => $searchQuery ? $searchQuery : '',
                'sort_by'  => $request->get('sort_by') ? $request->get('sort_by') : '',
                'showPast' => $request->get('past'),
            ],
        ];

        return view('ManageOrganiser.Events', $data);
    }





    public function showEvents(Request $request, $organiser_id)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);
        $allowed_sorts = ['created_at', 'start_date', 'end_date', 'title'];
        $searchQuery = $request->get('q');
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'start_date');
        if ($organiser_id == 1) {
            $events = $searchQuery
                ? Event::with(['organiser', 'currency'])
                ->where('title', 'like', '%' . $searchQuery . '%')
                ->where(function ($query) {
                    $query->where('location_address_line_1', '<>', '1')
                        ->where('location_address_line_1', '!=', '1111')
                        ->orWhereNull('location_address_line_1');
                })

                ->orderBy($sort_by, 'desc')
                ->paginate(12)
                : Event::with(['organiser', 'currency'])
                ->where(function ($query) {
                    $query->where('location_address_line_1', '<>', '1')
                        ->where('location_address_line_1', '!=', '1111')
                        ->orWhereNull('location_address_line_1');
                })

                ->orderBy($sort_by, 'desc')
                ->paginate(12);


            $events2 = $searchQuery
                ? Event::with(['organiser', 'currency'])
                ->where('title', 'like', '%' . $searchQuery . '%')
                ->orderBy($sort_by, 'desc')
                ->where(function ($query) {
                    $query->where('location_address_line_1', '=', '1')
                        ->where('location_address_line_1', '!=', '1111');
                })

                ->paginate(12)
                : Event::with(['organiser', 'currency'])
                ->where(function ($query) {
                    $query->where('location_address_line_1', '=', '1')
                        ->where('location_address_line_1', '!=', '1111');
                })

                ->orderBy($sort_by, 'desc')
                ->paginate(12);
        } else {
            $events = $searchQuery
                ? Event::scope()
                ->with(['organiser', 'currency'])
                ->where('title', 'like', '%' . $searchQuery . '%')
                ->where(function ($query) {
                    $query->where('location_address_line_1', '<>', '1')
                        ->where('location_address_line_1', '!=', '1111')
                        ->orWhereNull('location_address_line_1');
                })
                ->where('organiser_id', '=', $organiser_id)
                ->orderBy($sort_by, 'desc')
                ->paginate(12)
                : Event::scope()
                ->with(['organiser', 'currency'])
                ->where(function ($query) {
                    $query->where('location_address_line_1', '<>', '1')
                        ->where('location_address_line_1', '!=', '1111')
                        ->orWhereNull('location_address_line_1');
                })
                ->where('organiser_id', '=', $organiser_id)
                ->orderBy($sort_by, 'desc')
                ->paginate(12);

            $events2 = $searchQuery
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
                ->paginate(12);
        }





        $data = [
            'events'    => $events,
            'events2'    => $events2,
            'organiser' => $organiser,
            'search'    => [
                'q'        => $searchQuery ? $searchQuery : '',
                'sort_by'  => $request->get('sort_by') ? $request->get('sort_by') : '',
                'showPast' => $request->get('past'),
            ],
        ];

        return view('ManageOrganiser.Events', $data);
    }



    public function showArchivados(Request $request, $organiser_id)
    {
        $organiser = Organiser::scope()->findOrfail($organiser_id);

        $allowed_sorts = ['created_at', 'start_date', 'end_date', 'title'];

        $searchQuery = $request->get('q');
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'start_date');

        $events = $searchQuery
            ? Event::scope()->with(['organiser', 'currency'])->where('title', 'like', '%' . $searchQuery . '%')->orderBy(
                $sort_by,
                'desc'
            )->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->orWhereNull('location_address_line_1');
            })->where('location_address_line_1', '!=', '1111')->where('organiser_id', '=', $organiser_id)->paginate(12)
            : Event::scope()->with(['organiser', 'currency'])->where(function ($query) {
                $query->where('location_address_line_1', '<>', '1')->where('location_address_line_1', '!=', '1111')
                    ->orWhereNull('location_address_line_1');
            })->where('organiser_id', '=', $organiser_id)->orderBy($sort_by, 'desc')->paginate(12);

        $events2 = $searchQuery
            ? Event::scope()->with(['organiser', 'currency'])->where('title', 'like', '%' . $searchQuery . '%')->orderBy(
                $sort_by,
                'desc'
            )->where('location_address_line_1', '!=', '1111')->where('location_address_line_1', '=', '1')->where('organiser_id', '=', $organiser_id)->paginate(12)
            : Event::scope()->with(['organiser', 'currency'])->where('location_address_line_1', '!=', '1111')->where('location_address_line_1', '=', '1')->where('organiser_id', '=', $organiser_id)->orderBy($sort_by, 'desc')->paginate(12);


        $data = [
            'events'    => $events,
            'events2'    => $events2,
            'organiser' => $organiser,
            'search'    => [
                'q'        => $searchQuery ? $searchQuery : '',
                'sort_by'  => $request->get('sort_by') ? $request->get('sort_by') : '',
                'showPast' => $request->get('past'),
            ],
        ];

        return view('ManageOrganiser.archivadas', $data);
    }


    public function destroy($organiser_id, $id)
    {

        $event = Event::where('organiser_id', $organiser_id)->findOrFail($id);
        $event->delete();

        $organiser = Organiser::scope()->findOrfail($organiser_id);


        $data = [
            'events'    => $event,
            'organiser' => $organiser,
            'organiser_id' => $organiser->id,
        ];

        return redirect()->route('showOrganiserEvents', $data)
            ->with('success', 'Funcion eliminado con éxito.');
    }
}
