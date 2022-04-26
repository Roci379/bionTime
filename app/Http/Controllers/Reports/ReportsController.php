<?php

namespace App\Http\Controllers\Reports;

use App\Models\Record;
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


class ReportsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use RecordListTrait;



    ////// FOR REPORTS VIEW //////

    /**
     * Function to get users who are not admin info
     * 
     * gets users (call to getUsers) who are not admin
     * 
     * gets records of users (call to getAllRecords)
     * 
     * return: array of arrays of info of users who are not admins (id, name and records)
     * 
     * sends this info to launch Reports View
     * 
     */
     public function getUserReportsInfo(){

        $users = $this->getUsers();

        $users_info = []; 

        foreach($users as $user){
            $iden = $user->id;
            $name = $user->first_name." ". $user->last_name; 
            $records = $this->getAllRecords($iden);
            $info = []; 
            array_push($info, ['id'=> $iden, 'name' =>$name, 'records' =>$records]);
            array_push($users_info, $info);
        }
        

        $user = Auth::user(); 

        return view('reports', [
            'user' => $user,
            'info' => $users_info,
        ]);
     
    }
    

    /* FUNCTIONS TO GET INFO FROM DB */

    ////// FOR REPORTS VIEW //////

    /**
     * return: users who are not admins ordered by id
     */
    public function getUsers(){

        $users = User::where('admin', false)->orderBy('id')->get();
        return $users;
    }


    /**
     * Function to get the list of records to show 
     * in timer list view of the user send as argument
     * 
     * if this user hast no records then returns "No records"
     * 
     * else returns an array of dates with associated records
     */

    public function getAllRecords(int $user_id){
    
        $records = Record::where('user_id', $user_id)->orderBy('id')->get();

        if($records->count()==0){
            return "No records";
        }else{

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
                            if(is_null($end_time)) continue 2;

                            //$diff = $end_time->diff($start_time);
                            $diff = date_diff($end_time,$start_time);
                            $total = strval($diff->h).":".strval($diff->i).":".strval($diff->s);
                            $total = new DateTime($total);
                            $total = $total->format('H:i:s');
                            array_push($hours, ['beginning'=>$end_time->format('H:i:s T'), 'end'=> $start_time->format('H:i:s T'), 'total'=>$total, 'working'=>false]);
                            $prueba = $start_time;
                            $end_time = null;
                            break;

                        case 'pause':
                        case 'stop':
                            $end_time = new DateTime($hour->date);
                            $diff = $start_time->diff($end_time);
                            $total = strval($diff->h).":".strval($diff->i).":".strval($diff->s);
                            $total = new DateTime($total);
                            $total = $total->format('H:i:s');
                            array_push($hours, ['beginning'=>$start_time->format('H:i:s T'), 'end'=> $end_time->format('H:i:s T'), 'total'=>$total, 'working'=>true]);
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

            return $view_records; 
    
        } 

    }
}

