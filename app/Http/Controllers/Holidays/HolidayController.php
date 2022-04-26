<?php

namespace App\Http\Controllers\Holidays;

use App\Models\Holiday;
use App\Models\User; 

use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Helpers\JH;
use App\Http\Controllers\RecordListTrait;


class HolidayController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use RecordListTrait;



    /* FUNCTIONS TO POST INFO TO DB */

    ////// FOR ADD NEW EVENT VIEW //////

    /**
     * creates new event and saves it to holdiay table
     */
    public function postEvent(Request $request){

        $new_event = new Holiday; 
        $user = Auth::user();

        $new_event->user_id = $user->id;
        $new_event->staring_date = $request->staring_date; 
        $new_event->ending_date = $request->ending_date; 
        $new_event->event_name = $request->event_name; 
        $new_event->event_type = $request->event_type; 
        $new_event->state = 'pending';
        $new_event->save();

        return response()->json([
            "msg" => "Creado event con id '$new_event->id'",
            "eventData" => [
                "name" => $new_event->event_name,
                "start" => $new_event->staring_date,
                "end" => $new_event->ending_date,
                "type" => $new_event->event_type,
                "state" => $new_event->state,
            ]
        ]);
    }
    
}
