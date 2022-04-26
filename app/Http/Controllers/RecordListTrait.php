<?php

namespace App\Http\Controllers;
use App\Helpers\JH;
use App\Models\Record;
use DateTime;

use Illuminate\Support\Facades\Auth;


trait RecordListTrait {



   /**
     * Function to get the list of records to show 
     * in timer list view
     * 
     * return records grouped by date if list of records is not empty
     * else returns []
     */
    public function getRecordsList(){
        
        $user = Auth::user();

        $records = Record::where('user_id', $user->id)->orderBy('id')->get();

        if($records->count()==0){
            return [];
        }


        $groupbydate = $records->groupBy(function($item, $key){
            return substr($item['date'], 0, 10);
        });


        $view_records = [];   
                
        foreach ($groupbydate as $key=>$day) {

            $start_time = null;
            $end_time = null;
            $hours = [];
            foreach ($day as $hour) {
                $numhours = null;
                $diff = null;

                switch($hour->record_type){

                    case 'start':
                    case 'unpause':
                        $start_time = new DateTime($hour->date);
                        $start_id = $hour->id; 
                        if(is_null($end_time)) continue 2;

                        //$diff = $end_time->diff($start_time);
                        $diff = date_diff($end_time, $start_time);
                        $total = strval($diff->h).":".strval($diff->i).":".strval($diff->s);
                        $total = new DateTime($total);
                        $total = $total->format('H:i:s');
                        array_push($hours, ['beginning'=>$end_time->format('H:i:s'), 'end'=> $start_time->format('H:i:s'), 'start_id'=>$end_id, 'end_id'=>$start_id,'kind'=>$end_type, 'total'=>$total, 'modify'=>true, 'working'=>false]);
                        $prueba = $start_time;
                        $end_time = null;
                        break;

                    case 'pause':
                    case 'stop':
                        $end_time = new DateTime($hour->date);
                        $end_id = $hour->id; 
                        $end_type = $hour->record_type; 
                        $diff = $start_time->diff($end_time);
                        $total = strval($diff->h).":".strval($diff->i).":".strval($diff->s);
                        $total = new DateTime($total);
                        $total = $total->format('H:i:s');
                        array_push($hours, ['beginning'=>$start_time->format('H:i:s'), 'end'=> $end_time->format('H:i:s'), 'start_id' => $start_id, 'end_id' => $end_id, 'kind'=>'working', 'total'=>$total, 'modify'=>true, 'working'=>true]);
                        $start_time = null;
                        break;
                }
            }


            array_push($view_records,
                [
                    'date' => $key,
                    'hours' => $hours
                ]
            );
        }

        return array_reverse($view_records);
    }







}
