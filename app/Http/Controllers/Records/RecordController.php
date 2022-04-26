<?php

namespace App\Http\Controllers\Records;

use App\Models\Holiday;
use App\Models\Record;
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


class RecordController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use RecordListTrait;



    /* FUNCTIONS TO LAUNCH VIEWS WITH THEIR INFO */


    ////// FOR DASHBOARD VIEW //////

    /**
     * Function to get UpperMenu Hours for logged user
     * 
     * gets the hours worked (call to getWorked)
     * 
     * gets percentages of hours worked based on hours obtained (getPercentages)
     * Percentage of time working today,
     * Percentage of time working this week,
     * 
     * gets the state of the last record (call to getLastRecordState)
     * 
     * gets events of user logged 
     * 
     * sends this info to launch Dashboard View
     */
    public function getDashboardInfo(){
        
        $user = Auth::user();

        // UpperMenu Hours
        $hours = $this->getWorked();
                
        //Percentages 
        $percentages = $this->getPercentages($hours); 
        
        // State of the last record
        $laststate = $this->getLastRecordState();

        // Events of user
        $events = $this->getEvents();

        
        return view('dashboard', [
            'user' => $user,
            'hours' => $hours,
            'percentages' => $percentages,
            'laststate' => $laststate,
            'events' => $events,
        ]);

    }

    /* For TIMER VIEW */

    /**
     * Function to get records for Timer View
     * 
     * gets user logged
     * 
     * gets records of user logged grouped by date (call to getRecordsList)
     * 
     * gets hours worked (call to getWorked)
     * 
     * gets time stages (bars with percentages of work, break and not worked) (call to getTimeStages)
     * 
     * gets daily records (the records to show in timer list) (call to getDailyRecords)
     * 
     * gets state of last record (call to getLastRecordState)
     * 
     * sends this info to launch Timer View
     * 
     */
    public function getTimerInfo(){

        $user = Auth::user();

        $view_records = $this->getRecordsList();
        
        $hours = $this->getWorked();

        $time_stages = $this->getTimeStages();
        
        $daily_records = $this->getDailyRecords();

        $laststate = $this->getLastRecordState();
        
        return view('timer', [
            'user' => $user,
            'view_records' => $view_records,
            'hours' => $hours,
            'time_stages'=>$time_stages,
            'daily_records'=>$daily_records,
            'laststate'=>$laststate,
        ]);
    }




    /* FUNCTIONS TO GET INFO FROM DB */

    ///// FOR DASHBOARD AND TIMER VIEW /////

    /* FOR UPPERMENU COMPONENT */

    /**
     * Function to get UpperMenu Hours
     * 
     * gets hours worked today, hours worked this week and 
     * hours worked this month
     * 
     * return: array with today, this week and this month hours
     * 
     */
    public function getWorked(){


        $today = new DateTime(); 

        $hours = []; 

        $todayhours = $this->getTodayInfo($today);
        
        $weekhours = $this->getWeekInfo($today);

        $monthhours = $this->getMonthInfo($today);
        

        array_push($hours, ['today'=>$todayhours, 'week'=>$weekhours, 'month'=>$monthhours]);
        
        return $hours;
 
    }

    /**
     * Function to get Planned, Worked and Extra Hours
     * for Today's date
     * 
     * gets records grouped by date
     * if its not empty looks for todays date and extracts the hours 
     * working. Computes the extra hours and sets hours planned
     * else returns worked and extra as zero hours 
     * 
     * if user has not worked today but records array is not empty does the same
     * 
     * return: array of hours, worked, extra and planned
     * 
     */
    public function getTodayInfo(DateTime $today){

        $groupbydate = $this->getRecordsList();

        $date = $today->format('Y-m-d');

        $hours = []; 

        $totalhours = null; 

        $planned = strtotime("08:00:00");

        $worked = null; 

        $midnight = strtotime("00:00:00");
        $workedToday = 0; 


        if($groupbydate!=[]){
            foreach($groupbydate as $key=>$day){
                foreach($day as $hour){
                    if($hour == $date){
                        $workedToday = 1;
                        foreach($day['hours'] as $record){
                            if($record['working']=='true'){
                                $total = $record['total'];
                                $seconds = strtotime($total) - $midnight;
                                $totalhours = $totalhours + $seconds;
                            }
                        }
                        $worked = date("G:i:s", $midnight + $totalhours);
                        
                        $extra = (strtotime($worked)) - ($planned);
                        if($extra <0){
                            $extra = date("G:i:s", $midnight);
                        }else{
                            $extra = date("G:i:s", $extra);
                        } 
                        
                        $planned = date("G:i:s", $planned);
                        array_push($hours, ['planned'=>$planned, 'worked'=>$worked, 'extra'=>$extra]);
                    }
                }
            }
        }else{
            $planned = date("G:i:s", strtotime("08:00:00"));
            $extra = date("G:i:s", strtotime("00:00:00"));
            array_push($hours, ['planned'=>$planned, 'worked'=>$extra, 'extra'=>$extra]);
        }
        
        if($workedToday == 0){
            $planned = date("G:i:s", strtotime("08:00:00"));
            $extra = date("G:i:s", strtotime("00:00:00"));
            array_push($hours, ['planned'=>$planned, 'worked'=>$extra, 'extra'=>$extra]);
        }

        return $hours; 

    }


    /**
     * Function to get Planned, Worked and Extra Hours
     * for the actual week
     * 
     * gets the number of weekday and gets the today info for the days
     * of today till first day of week
     * 
     * then sums the hours to get the planned, worked and extra hours of the week
     * 
     * return: array of hours, worked, extra and planned
     * 
     */
    public function getWeekInfo(DateTime $today){

        $weekday = $today->format('w');
        $actual_day = $weekday;
        $weekhours = []; 
        $hours = [];

        for ($weekday ; $weekday >= 0; $weekday--) {
            array_push($weekhours, ['dayhours'=>$this->getTodayInfo($today)]);
            $today = $today->format('Y-m-d');
            $day_before = date( 'Y-m-d', strtotime( $today . ' -1 day' ) );
            $today = new DateTime($day_before);
        }


        $planned = 0; 
        $worked = 0; 
        $extra = 0; 

        $midnight = strtotime("0:00:00");


        foreach($weekhours as $element){
            foreach($element['dayhours'] as $day){    
                $planned = $this->addSeconds($day['planned']);   
                $worked = $worked + $this->addSeconds($day['worked']); 
                $extra = $extra + $this->addSeconds($day['extra']); 
            }
        }

        $planned = $planned*$actual_day;
        $planned = $this->secondsToDate($planned); 
        $worked = $this->secondsToDate($worked); 
        $extra = $this->secondsToDate($extra); 

        array_push($hours, ['planned'=>$planned, 'worked'=>$worked, 'extra'=>$extra]);

        return $hours;
    }

    /**
     * Function to get Planned, Worked and Extra Hours
     * for the actual month
     * 
     * gets the number of monthday and gets the today info for the days
     * of today till first day of month
     * 
     * then sums the hours to get the planned, worked and extra hours of the month
     * 
     * return: array of hours, worked, extra and planned
     * 
     */
    public function getMonthInfo(DateTime $today){

        $daynumber = $today->format('d');
        $actual_day = $daynumber;
        
        $monthhours = []; 
        $hours = [];

        $today = $today->format('Y-m-d');
        $today = new DateTime($today);

        for ($daynumber ; $daynumber > 0; $daynumber--) {
            array_push($monthhours, ['dayhours'=>$this->getTodayInfo($today)]);
            $today = $today->format('Y-m-d');
            $day_before = date('Y-m-d', strtotime( $today . ' -1 day' ) );
            $today = new DateTime($day_before);
        }

        $planned = 0; 
        $worked = 0; 
        $extra = 0; 

        $midnight = strtotime("0:00:00");


        foreach($monthhours as $element){
            foreach($element['dayhours'] as $day){
                $planned = $this->addSeconds($day['planned']);            
                $worked = $worked + $this->addSeconds($day['worked']); 
                $extra = $extra + $this->addSeconds($day['extra']); 
            }
        }

        $planned = $actual_day*$planned;
        $planned = $this->secondsToDate($planned); 
        $worked = $this->secondsToDate($worked); 
        $extra = $this->secondsToDate($extra); 

        array_push($hours, ['planned'=>$planned, 'worked'=>$worked, 'extra'=>$extra]);

        return $hours;
    }


    /**
     * Function to add hours, since DateTime adds 
     * also years and days 
     */
    public function addSeconds(string $time){
        $date = new DateTime($time);
        $hours = $date->format('G');
        $hours = intval($hours);
        $minutes = $date->format('i');
        $minutes = intval($minutes);
        $seconds = $date->format('s');
        $seconds = intval($seconds);

        $seconds = $hours*3600 + $minutes*60 + $seconds; 
        return $seconds; 
    }

    /**
     * Function to format seconds into G:i:s
     */
    public function secondsToDate(int $seconds){
        $secs = $seconds%60;
        $minutes = floor($seconds/60);
        $mins = $minutes%60;
        $hours = floor($minutes/60); 

        $h = ($hours>=10) ? strval($hours) : "0".strval($hours);
        $m = ($mins>=10) ? strval($mins) : "0".strval($mins);
        $s = ($secs>=10) ? strval($secs) : "0".strval($secs);
        $date = strval($h). ":".strval($m).":".strval($s);
        return $date; 
    }


    /**
     * Function to get last record state
     * 
     * returns stop if there are no records and the type of the last record if not
     * 
     */

     public function getLastRecordState(){


        $user = Auth::user();
        
        if(Record::where('user_id', $user->id)->get()->isEmpty()){
            return 'stop';
        }else{
            $records = Record::where('user_id', $user->id)->orderBy('id')->get();
            return $records[count($records)-1]->record_type;
        }
     }
   

    /**
     * Function to get time tracker value 
     * 
     * gets records of logged user
     * 
     * if records is not empty gets the date of today in seconds and the last record in seconds
     * computes the difference between them and returns them with format G:i:s (call to secondsToDate)
     * 
     * else returns 00:00:00
     * 
     */

    public function getTimerValue(){

        $user = Auth::user(); 

        $records =  Record::where('user_id', $user->id)->orderBy('id')->get();

        if($records->count()!=0){
            // Get actual time to see the difference with last record
            $today = new DateTime();
            $todayDate = $today->format('Y-m-d H:i:s');
            $todayDate = $todayDate.'+02';
            $todaySeconds = $this->addSeconds($todayDate);


            $lastRecord =  $records[count($records)-1];
            $lastRecordSeconds = $this->addSeconds($lastRecord->date);


            $todayTime =  strtotime($todayDate);
            $lastTime = strtotime($lastRecord->date);

            $difference =  abs($todayTime - $lastTime);

            return $this->secondsToDate($difference);

        }else{
            return "00:00:00";
        }

    }


    ///// FOR DASHBOARD VIEW /////


    /**
     * Function to get events of logged user
     * 
     * return: array of events
     * 
     */
    public function getEvents(){

        $user = Auth::user(); 

        $holidays = Holiday::where('user_id', $user->id)->orderBy('id')->get();
        
        return $holidays;

    }


    /**
     * Function to get percentage of time working 
     * 
     * from hours array gets total of seconds planned and total of seconds worked
     * if planned is not zero returns worked/planned percentage else returns zero
     */
    public function getPercent(array $hours){
        $split_p = explode(":",$hours['planned']);
        $h_p = intval($split_p[0]);
        $m_p = intval($split_p[1]);
        $s_p = intval($split_p[2]);

        $total_planned = $h_p*3600 + $m_p*60 + $s_p;

        $split_w = explode(":",$hours['worked']);
        $h_w = intval($split_w[0]);
        $m_w = intval($split_w[1]);
        $s_w = intval($split_w[2]);

        $total_worked = $h_w*3600 + $m_w*60 + $s_w;

        if($total_planned != 0){
            $percentage = 100*($total_worked/$total_planned);
        }else{
            $percentage = 0; 
        }

        return $percentage;
    }


    /**
     * Function to get percentages of time working today and
     * this week
     * 
     * from hours array if hours of today are not empty computes the percentage else returns zero (call to getPercent)
     * from hours array if hours of this week are not empty computes the percentage else returns zero (call to getPercent)
     * 
     * return: array of percentages for today and this week
     */
    public function getPercentages(array $hours){

        $percentages = []; 

        if($hours!=[]){
            if($hours[0]['today']!=[]){
                $today_percentage = $this->getPercent($hours[0]['today'][0]);
            }else{
                $today_percentage = 0; 
            }
            if($hours[0]['week']!=[]){
                $week_percentage = $this->getPercent($hours[0]['week'][0]);
            }else{
                $week_percentage = 0;
            }
        }
        
    
        array_push($percentages, ['today'=>$today_percentage, 'week'=>$week_percentage]);    
       
        return $percentages; 

    }
    

    ///// FOR TIMER VIEW /////

 
    /**
     * Function to get percentage of time working,
     * breaks and not working for timeline in timer
     * list view
     * 
     * gets records of logged user and converts all to seconds, then sums the hours working and 
     * the hours not working (in a break)
     * 
     * if records empty returns 100 of not working, else returns the percentages working, in a break and not working
     * 
     * return: JSON with worked, break and not worked percentages
     * 
     */

     public function getTimeStages(){

        $time_stages = [];

        $break = 0; 
        $working = 0; 
        $not_working = 0;

        $total_day = 24*3600;
        $days = 0;

        $records = $this->getRecordsList();

        if($records!=[]){
            foreach($records as $key=>$date){
                $days = $days + 1;
                foreach($date as $hour){
                    foreach($date['hours'] as $records){
                        if($records['working']){
                            $working = $working + $this->addSeconds($records['total']);
                        }
                        if(!$records['working']){
                            $break = $break + $this->addSeconds($records['total']);
                        }
                    }
                }
            }
        
            // if records is not empty, there must be at least one day so total_day*days cannot be zero
            // if working and break are zero then not working cannot be zero, if not working is zero it must
            // be because working or break have a value, so total cannot be zero
            $not_working = $total_day*$days - $working - $break;
            $total = $working + $not_working + $break;
    
            $working = 100*($working/$total);
            $break = 100*($break/$total);
            $not_working = 100*($not_working/$total);
    
        }else{
            $not_working = 100.0;
            $working = 0.0;
            $break = 0.0;
        }

       
        $time_stages = array(
            array('name' => 'Worked', 'id' => 'worked', 'duration' => $working),
            array('name' => 'Break', 'id' => 'break', 'duration' => $break),
            array('name' => 'Not Worked', 'id' => 'notworked', 'duration' => $not_working)
        );

        $json = json_encode($time_stages);

        return $time_stages;
     }


    /**
     * Function to get records for timer daily view
     * 
     * gets records of logged user and groups them by date
     * 
     * saves them to an array for everyday
     * 
     * return: array of arrays of records of each day
     * 
    */

    public function getDailyRecords(){
        $user = Auth::user();

        $records = Record::where('user_id', $user->id)->orderBy('id')->get();

        $groupbydate = $records->groupBy(function($item, $key){
            return substr($item['date'], 0, 10);
        });

        $recs = [];
        foreach($groupbydate as $key=>$day){
            $dayrecords = [];
            foreach($day as $hour){
                $hour->date = substr($hour->date, 0, -3);
                unset($hour->id);
                unset($hour->created_at);
                unset($hour->updated_at);
                unset($hour->deleted_at);

                $rec = new Record;
                
                $rec->user_id = $hour->user_id;
                $rec->device_id = $hour->device_id;
                $rec->timestamp = $hour->date;
                $rec->type = $hour->record_type;
    
                array_push($dayrecords, $rec);
            }

            array_push($recs, [$key => $dayrecords]);
        }

        return $recs;
    }
    
    
    /** 
     * Function to get info from getStatusAndRecords 
     */
    public function getRecords(){

        $data = $this->getStatusAndRecords();

        return response()->json($data);

    }


    /** 
     * Function to get time tracker value and state of last record
     * 
     */
    private function getStatusAndRecords(){

        $time = $this->getTimerValue();
        $state = $this->getLastRecordState();

        $default = (object) [
            'status' => $state,
            "time" => $time,
        ];

        return session('demo-status-record', $default);
    }


    /* FUNCTIONS TO RELOAD INFO IN TIMER AND DASHBOARD VIEW */


    /**
    * Returns Dashboard view
    */
    public function viewDashboard(){
        return view('dashboard');
    }

    /**
    * Returns timer view
    */
     public function viewTimer(){
        return view('timer');
    }



    /* FUNCTIONS TO POST INFO TO DB */

    /**
     * Function to post value to time tracker when principal button clicked
     */
    public function buttonClicked(Request $request){

        $this->validate($request, [
            'action' => 'string|required|in:start,pause,stop,unpause,change'
        ]);

        $data = $this->getStatusAndRecords();


        if($request->action === 'change'){
            if($data->status !== 'stop'){
                $data->time = $this->getTimerValue();
            }else{
                $data->time = "00:00:00";
            }
        }

        if($request->action === 'start'){
            
            if($data->status !== 'stop'){
                abort(400, "Cannot start if status != stop");
            }

            $data->status = 'start';
            $data->time = '00:00:00';

        } else if ($request->action === 'unpause'){
            if($data->status !== 'pause'){
                abort(400, "Cannot unpause if status != pause");
            }

            $data->status = 'unpause';
            $data->time = '00:00:00';


        } else if ($request->action === 'stop'){
            
            if($data->status !== 'start' && $data->status !== 'unpause'){
                abort(400, "Cannot stop if status != start or unpause");
            }

            $data->status = 'stop';
            $data->time = "00:00:00";

        } else if($request->action === 'pause'){

            if($data->status !== 'start' && $data->status !== 'unpause'){
                abort(400, "Cannot pause if status != start or unpause");
            }

            $data->status = 'pause';
            $data->time = '00:00:00';

        }

        //Save in session
        session(['demo-status-record' => $data]);

    }


    /** 
     * Function to post new record of logged user
     * 
     * gets the list of records updated and the list of daily records updated
     * 
     */
    public function postRecord(Request $request){
        $user = Auth::user(); 

        $record = new Record; 
        $record->record_type = $request->recordState; 
        $record->user_id = $user->id;
        $record->device_id = 1; //TODO
        $record->date = Carbon::now(); 

        $record->save(); 

        $records = $this->getRecordsList();
        $daily_records = $this->getDailyRecords();

        return response()->json([

            "msg" => "Creado record con id '$record->id'",
            "recordData" => [
                "hour" => $record->date,
                "state" => $record->record_type,
            ],
            "Datos de vue" => $request->nombre,
            "records" => $records,
            "daily_records" => $daily_records,
        ]);

    }

    /**
     * Function to modify the start hour of a record
     * checks if modification is posible (if is before the end time and not after the hour set before)
     * else doesnt modify
     */
    public function postModifyStartRecord(Request $request){
        $start_id = $request->registro['start_id'];
        $start_record = Record::where('id', $start_id)->first();
        $actual_hour = substr($start_record->date,0,-3);
        $actual_hour = date('G:i:s', strtotime($actual_hour));
        $new_beginning = date('G:i:s', strtotime($request->registro['beginning']));
        $end = date('G:i:s', strtotime($request->registro['end']));
        if($new_beginning<$end){
            if($new_beginning>$actual_hour){
                JH::log("GUARDAR REGISTRO");
                $new_value = str_replace($actual_hour, $new_beginning, $start_record->date);
                $start_record->date = $new_value;
                $start_record->save();
            }else{
                JH::log("NO GUARDAR");
            }
        }else{
            JH::log("NO GUARDAR");
        }
    }

    /**
     * Function to modify the end hour of a record
     * checks if modification is posible (if is after the start time and not before the hour set before)
     */
    public function postModifyEndRecord(Request $request){
        $end_id = $request->registro['end_id'];
        $end_record = Record::where('id', $end_id)->first();
        $actual_hour = substr($end_record->date,0,-3);
        $actual_hour = date('G:i:s', strtotime($actual_hour));
        $new_end = date('G:i:s', strtotime($request->registro['end']));
        $beginning = date('G:i:s', strtotime($request->registro['beginning']));
        if($new_end>$beginning){
            if($new_end<$actual_hour){
                JH::log("GUARDAR REGISTRO");
                $new_value = str_replace($actual_hour, $new_end, $end_record->date);
                $end_record->date = $new_value;
                $end_record->save();
            }else{
                JH::log("NO GUARDAR");
            }
        }else{
            JH::log("NO GUARDAR");
        }
    }


    /**
     * Function to delete a record 
     * if it is a work record surrounded by not working records then it can be deleted
     * else if surrounded by break records they must be deleted first
     * 
     * if it is a break record it can be deleted
     */
    public function postDeleteRecord(Request $request){

        $end_id = $request->registro['end_id']; 
        $start_id = $request->registro['start_id'];

        $start_record = Record::where('id',$start_id)->first();
        $end_record = Record::where('id', $end_id)->first();

        if($request->registro['working']== true){
            JH::log("BORRAR REGISTRO DE TRABAJO");
            if($start_record->record_type=='start'){
                if($end_record->record_type=='stop'){
                    $start_record->delete();
                    $end_record->delete();
                    return response()->json([
                        "msg" => "Record deleted"
                    ]);

                }else{
                    return response()->json([
                        "msg" => "You can only delete lonely working records!"
                    ]);
                }
            }
            
        }else{
            JH::log("BORRAR REGISTRO DE DESCANSO");
            $start_record->delete();
            $end_record->delete();
            return response()->json([
                "msg" => "Record deleted"
            ]);
        }

        return response()->json([
            "msg" => "You can only delete lonely working records!"
        ]);
        

    }

    
}
