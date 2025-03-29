<?php

namespace App\Http\Controllers\API;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class AttendeesApiController extends ApiBaseController
{



    public function checkCode(Request $request)
    {
        try{
        $event = Event::where('id',intval($request->codeId))->where('organiser_id',intval($request->organiser_id))->get();
        if(count($event) > 0){
            $temp = $event->last();
            return response([
                'msg' => 'Ingresando...',
                'event_name' => $temp->title
            ], 200);
        }
       else {
            return response([
                'msg' => 'No se encuentra el evento deseado'
            ], 404);
        }
    } catch (Exception $e) {
        return response(['msg' => 'Ha ocurrido un error: ' . $e->getMessage()], 500);
    }

    }


    public function searchAttendee(Request $request)
    {
try{
        $attendee = Attendee::where('event_id', $request->event_id)->where('private_reference_number', $request->reference)->get();
        if (count($attendee) > 0) {
            $temp = $attendee->last();
            $ticket = Ticket::find($temp->ticket_id);
            $event = Event::find($temp->event_id);


            if ($temp->has_arrived == 1) {
                return response([
                    'msg' => 'Utilizada',
                    'first_name' => $temp->first_name,
                    'last_name' => $temp->last_name,
                    'email' => $temp->email,
                    'arrival_time'=>Carbon::parse($temp->arrival_time)->format('d/m/Y H:i'),
                    'tipo' => $ticket->title,
                    'date' => Carbon::parse($event->start_date,)->format('d/m/Y H:i'),
                    'obra' => $event->title,
                    'cod' => $temp->private_reference_number

                ], 200);
            } else {
                $temp->has_arrived = 1;
                $temp-> arrival_time = now();
                $temp->save();
                return response([
                    'msg' => 'Correcta',
                    'first_name' => $temp->first_name,
                    'last_name' => $temp->last_name,
                    'email' => $temp->email,
                    'arrival_time'=>'Nunca',
                    'tipo' => $ticket->title,
                    'date' => Carbon::parse($event->start_date,)->format('d/m/Y H:i'),
                    'obra' => $event->title,
                    'cod' => $temp->private_reference_number
                ], 200);
            }
        } else {
            return response([
                'msg' => 'No se encuentra esta entrada'
            ], 404);
        }


    } catch (Exception $e) {
        return response(['msg' => 'Ha ocurrido un error: ' . $e->getMessage()], 500);
    }

    }



    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return Attendee::scope($this->account_id)->paginate($request->get('per_page', 25));
    }





    /**
     * @param Request $request
     * @param $attendee_id
     * @return mixed
     */
    public function show(Request $request, $attendee_id)
    {
        if ($attendee_id) {
            return Attendee::scope($this->account_id)->find($attendee_id);
        }

        return response('Attendee Not Found', 404);
    }

    public function store(Request $request) {}

    public function update(Request $request) {}

    public function destroy(Request $request) {}
}
