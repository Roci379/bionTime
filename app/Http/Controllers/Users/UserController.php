<?php

namespace App\Http\Controllers\Users;

use App\Models\FunctionOfUser;
use App\Models\Functions;
use App\Models\Record;
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
use Illuminate\Support\Facades\Hash;
use App\Helpers\JH;
use App\Http\Controllers\RecordListTrait;


class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use RecordListTrait;


    /* FUNCTIONS TO LAUNCH VIEWS WITH THEIR INFO */

    /**
     * gets user information: 
     * admin, company_id, created_at, email, email_verified_at, event_visibility,
     * first_name, id, last_name, phone, profile_image, two_factor_recovery_codes,
     * two_factor_secret, updated_at, weekly_hours
     * 
     * gets events of logged user (call to getEvents)
     * 
     * If user is admin gets also other user events (call to getOtherUserEvents) else returns []
     * 
     * gets disponibility of events (call to getDisponibility)
     * 
     * sends this info to launch My Calendar View
     * 
     */
    public function getMyCalendarView(){

        $user = Auth::user();
        $events = $this->getEvents();
        $other_user_events = [];
        
        if($user->admin == true){
            $other_user_events = $this->getOtherUserEvents();
        }

        $disponibility = $this->getDisponibility();
        return view('mycalendar', [
            'user' => $user,
            'events' => $events,
            'others' => $other_user_events,
            'disponibility' => $disponibility,
        ]);

    }

    /**
     * gets user information: 
     * admin, company_id, created_at, email, email_verified_at, event_visibility,
     * first_name, id, last_name, phone, profile_image, two_factor_recovery_codes,
     * two_factor_secret, updated_at, weekly_hours
     * 
     * gets events of all users (call to getAllEvents)
     * 
     * sends this info to launch Company Calendar View
     * 
     */
    public function getCompanyCalendarView(){

        $user = Auth::user();
        $events = $this->getAllEvents();
        return view('companycalendar', [
            'user' => $user,
            'events' => $events,
        ]);

    }


    /**
     * gets user information: 
     * admin, company_id, created_at, email, email_verified_at, event_visibility,
     * first_name, id, last_name, phone, profile_image, two_factor_recovery_codes,
     * two_factor_secret, updated_at, weekly_hours
     * 
     * gets other users (not admins) information (call to getInfo)
     * 
     * gets names of the functions (call to getNamesOfRoles)
     * 
     * sends this info to launch Management View
     * 
     */
    public function getManagementInfo(){

        $user = Auth::user();

        $users = $this->getInfo();

        $names = $this->getNamesOfRoles();

        return view('management', [
            'user' => $user,
            'users' => $users,
            'names' => $names,
        ]);

    }


    /**
     * gets user information: 
     * admin, company_id, created_at, email, email_verified_at, event_visibility,
     * first_name, id, last_name, phone, profile_image, two_factor_recovery_codes,
     * two_factor_secret, updated_at, weekly_hours
     * 
     * gets names of all functions (call to getNamesOfRoles)
     * 
     * sends an empty message (which will have contain when info submitted is wrong)
     * 
     * sends this info to launch Add New User View
     * 
     */
    public function getAddNewUserInfo(){

        $user = Auth::user();

        $names = $this->getNamesOfRoles();

        return view('addnewuser', [
            'user' => $user,
            'names' => $names,
            'msg' => "",
        ]);

    }

    /**
     * gets user information: 
     * admin, company_id, created_at, email, email_verified_at, event_visibility,
     * first_name, id, last_name, phone, profile_image, two_factor_recovery_codes,
     * two_factor_secret, updated_at, weekly_hours
     * 
     * gets names of all functions (call to getNamesOfRoles)
     * 
     * sends this info to launch Modify User View
     * 
     */
    public function getModifyUserInfo(){

        $user = Auth::user();
        $names = $this->getNamesOfRoles();
        
        return view('modifyuser', [
            'user' => $user,
            'names' => $names,
        ]);

    }

    /**
     * gets user information: 
     * admin, company_id, created_at, email, email_verified_at, event_visibility,
     * first_name, id, last_name, phone, profile_image, two_factor_recovery_codes,
     * two_factor_secret, updated_at, weekly_hours
     * 
     * gets functions of logged user (call to getFunctions)
     * 
     * sends this info to launch My Profile View
     */
    public function getMyProfileView(){

        $user = Auth::user();
        $functions = $this->getFunctions();
        return view('myprofile', [
            'user' => $user,
            'function' => $functions,
        ]);

    }


    /* FUNCTIONS TO GET INFO FROM DB */



    ////// FOR MY CALENDAR VIEW //////

    /**
     * gets user logged functions (FunctionOfUser)
     * 
     * based on function ids gets the ids of users who have the same functions as user logged
     * and gets their events
     * 
     * return: similar_users: array of user ids with same functions as user logged and their events
     */
    public function getDisponibility(){

        $user = Auth::user();

        // Get my functions 
        $myfunctions = FunctionOfUser::where('user_id', $user->id)->get();
        
        // Get all table of functions 
        $functions = FunctionOfUser::all();

        $similar_users = [];

        foreach($myfunctions as $fun){
            // My function 
            $fun_id = $fun->function_id; 
            foreach($functions as $othfun){
                $oth_id = $othfun->function_id; 
                if($othfun->user_id != $user->id){
                    // See if other user has same function
                    if($fun_id == $oth_id){
                        $events = Holiday::where('user_id', $othfun->user_id)->get();
                        array_push($similar_users, ['user_id' => $othfun->user_id, 'events' => $events]);
                    }
                }
            }
        }
        return $similar_users; 


    }


    /**
     * gets the events of users whose ids are different from user logged id
     * 
     * return: array which contains name of user (first_name + last_name) dates of event and type of event.
     */
    public function getOtherUserEvents(){

        $this_user = Auth::user(); 
        $events =Holiday::all();

        $other_user_events = [];

        foreach($events as $ev){
            if($ev->user_id != $this_user->id){
                $user = User::where('id', $ev->user_id)->get();
                $name = $user[0]->first_name.' '.$user[0]->last_name;
                array_push($other_user_events, ['user'=> $name, 'start'=> $ev->staring_date, 'end'=> $ev->ending_date, 'type' => $ev->event_type]);
            }
          
        }
        
        return $other_user_events;

    }

    /**
     * gets user logged and extracts the events asociated to his user id
     * 
     * return: array of events ordered by id
     * 
     */
    public function getEvents(){

        $user = Auth::user(); 

        $holidays = Holiday::where('user_id', $user->id)->sewhere('staring_date', '>=', new DateTime())->orderBy('staring_date', 'desc')->get();
        
        return $holidays;

    }


    ////// FOR COMPANY CALENDAR VIEW //////

    /**
     * gets user logged and his functions
     * gets users whose visibility of events is true
     * 
     * for those users extracts events and roles
     * 
     * return: array of users with name (first_name + last_name), the roles of user logged, dates of events and type
     * and the roles of corresponding user
     * 
     */
    public function getAllEvents(){

        $user = Auth::user();
        $user_role = FunctionOfUser::where('user_id', $user->id)->get();
        $users = User::where('event_visibility', true)->orderBy('id')->get();
        $holidays = [];
        foreach($users as $us){
            $events = Holiday::where('user_id', $us->id)->get();
            $roles = FunctionOfUser::where('user_id', $us->id)->get();
            $name = $us->first_name.' '.$us->last_name;
            foreach($events as $ev){
                $startingDate = $ev->staring_date;
                $endDate = $ev->ending_date;
                $eventType = $ev->event_type;
                array_push($holidays, ['user_id'=>$name, "user_role"=>$user_role, "startingDate"=>$startingDate, "endingDate"=>$endDate, "type"=> $eventType, 'roles' =>$roles]);
            }
        }

        return $holidays;
    }


    ////// FOR MANAGEMENT VIEW //////

    /**
     * gets user logged and if this user is admin gets users who are not admins
     * 
     * return: array of users (id, name, email and phone) who are not admins or [] if user is not admin
     * 
     */
    public function getInfo(){

        $user = Auth::user(); 

        $admin = $user->admin; 

        $new_users = [];

        if($admin){
            //$users = User::where('admin', 0)->orderBy('id')->get();
            $users = User::orderBy('id')->get();
            foreach($users as $us) {
                $name = $us->first_name.' '.$us->last_name;
                array_push($new_users, ['identifier'=>$us->id, 'user'=>$name, 'email'=>$us->email, 'phone'=>$us->phone]);
            }
        }

        return $new_users; 
    }

    ////// FOR ADD NEW USER VIEW //////

    /**
     * return: array with name of all functions
     */
    public function getNamesOfRoles(){
        $names = Functions::all('name');
        return $names;
    }




    ////// FOR MY PROFILE VIEW //////

    /**
     * gets user logged and extracts his functions
     * 
     * looks for name of function in functions table 
     * 
     * return: array with the name of functions of user logged
     * 
     */
    public function getFunctions(){
        $user = Auth::user();
        $functions = FunctionOfUser::where('user_id', $user->id)->get();
        $names = [];


        foreach($functions as $fun){
            $name_functions = Functions::where('id', $fun->function_id)->get();
            array_push($names, ['function'=>$name_functions[0]->name]);
        }

        return $names; 
    }



    /* FUNCTIONS TO POST INFO TO DB */


    ////// FOR MY CALENDAR VIEW //////

    /** Function to change events visibility of logged user */
    public function postModifyEventVisibility(Request $request){

        $user = Auth::user();
        $user->event_visibility = !$user->event_visibility;
        $user->save();
        
    }

     ////// FOR MANAGEMENT VIEW //////

    /** Function to delete user selected and his holidays, records and functions */
    public function postDeleteUser(Request $request){

        $user = User::find($request->iden);
        $user->holidays()->delete();
        $user->records()->delete();
        $user->functionsOfUser()->delete();

        $user->delete();

        return response()->json([
            "msg"=>"Eliminado usuario y registros con id '$request'", 
        ]);


    }

    ////// FOR MODIFY USER VIEW //////


    /**
     * gets user to modify info 
     * 
     * return: user to modify info to post it in modify user view  
     */
    public function postModifyUserInfo(Request $request){
        
        
        $user = User::where('id', $request->input('id'))->first();

        error_log($user);

        $names = $this->getNamesOfRoles();

        $functions = []; 
        $funciones = FunctionOfUser::where('user_id', $request->input('id'))->get();
        foreach($names as $name){
            $function = $name->name; 
            $id = Functions::where('name', $function)->get();
            $id = $id[0]->id;
            foreach($funciones as $fun){
                if($id == $fun->function_id){
                    array_push($functions, [$function => true]);
                }
            }
        }
        
        return response()->json([
            "msg" => "Modificar usuario con id '$user->id'",
            "userData" => [
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "phone" => $user->phone,
                "email" => $user->email,
                "admin" => $user->admin,
                "funciones" => $functions,
                "profile_img" => $user->profile_image,
            ]
        ]);
    }


    ////// FOR ADD NEW USER VIEW //////

    /** 
     * Function to check if a string corresponds to an email
     */
    public function validateEmail(String $email) {
        $regex = "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/";
        return preg_match($regex, $email);
    }

    /**
     * Function to post user info
     * 
     * checks if email is correct and passwords match
     * 
     * if there are not errors a new user is created
     * 
     * his profile image is added to public file
     * 
     * his functions are added to functionofuser table
     * 
     * launches Management View if info is correct
     * 
     * sends error message to Add New User View if info is wrong
     * 
     */
    public function postImage(Request $request){

        $errors = false;
        $error_msg = "";  

        if(!$this->validateEmail($request->email)){
            $errors = true;
            $error_msg = "The email is not valid";
        }

        $other_users = User::orderBy('id')->get(); 

        foreach($other_users as $us){
            $email_other = $us->email; 
            if($email_other == $request->email){
                $errors = true; 
                $error_msg = "The email is already in database";
            }
        }

        if($request->password != $request->password_conf){
            $errors = true; 
            $error_msg = "The password confirmation does not match password";
        }

        if($request->password == ""){
            $errors = true; 
            $error_msg = "Please enter a password";
        }


        if($errors == false){
            $new_user = new User; 

            $new_user->first_name = $request->first_name; 
            $new_user->last_name = $request->last_name; 
            $new_user->phone = $request->phone; 
            $new_user->email = $request->email; 

            $psswd = $request->password; 
            $psswd_conf = $request->password_conf; 
            if($psswd_conf == $psswd){
                $new_user->password = Hash::make($psswd);
            }

            $new_user->admin = $request->admin=='on'; 
            $new_user->event_visibility = true; 

            if($request->hasFile('file')){
                
                $request->validate([
                    'image' => 'mimes:jpeg, bmp, png'
                ]);

                $filename = $request->file->hashName();
                $file_path = public_path('images/profileImages/'.$filename);

                move_uploaded_file($request->file, $file_path);

                $new_user->profile_image = $file_path; 

            }

            $new_user->save();

            $names = $this->getNamesOfRoles();

            foreach($names as $name){
                $campo = ($name->name);
                $campo = str_replace(" ", "_", $campo);
                if($request->input($campo)== 'on'){
                    $new_function = new FunctionOfUser;
                    $new_function->user_id = $new_user->id;
                    $campo = $name->name;
                    $fun = Functions::where('name', $campo)->get();
                    $new_function->function_id = $fun[0]->id;
                    $new_function->save();
                }
            }

            return $this->getManagementInfo();

        }else{
            $user = Auth::user();
            $names = $this->getNamesOfRoles();
    
            return view('addnewuser', [
                'user' => $user,
                'names' => $names,
                'msg' => $error_msg,
            ]);
    
        }

    }

    /**
     * gets user to modify with the corresponding email (since it cannot change)
     * 
     * sets this user information to the new data submitted
     * 
     * if passwords match it is changed
     * 
     * the profile image is submitted and added to public file
     * 
     * checks if functions selected are already selected for user, if not 
     * they are added to functionofuser table
     * 
     * if some of the functions of the user are not selected, but they are in 
     * functionofuser table they are deleted
     * 
     * if all goes well launches Management View
     * 
     */
    public function modifyUser(Request $request){
        
        $user = User::where('email', $request->email)->first();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name; 
        $user->phone = $request->phone; 
        $psswd = $request->password; 
        $psswd_conf = $request->password_conf; 
        if($psswd_conf == $psswd){
            if($psswd != ""){
                $user->password = Hash::make($psswd);
            }
        }
        $user->admin = $request->admin=='on'; 
    
        if($request->hasFile('file')){
            
            $request->validate([
                'image' => 'mimes:jpeg, bmp, png'
            ]);

            $filename = $request->file->hashName();
            $file_path = public_path('images/profileImages/'.$filename);

            move_uploaded_file($request->file, $file_path);

            $user->profile_image = $file_path; 
        }

        $user->save();


        $names = $this->getNamesOfRoles();
        $hisfunctions = FunctionOfUser::where('user_id', $user->id)->get();

        foreach($names as $name){
            $campo = ($name->name);
            $campo = str_replace(" ", "_", $campo);
            if($request->input($campo)== 'on'){
                $campo = $name->name;
                $fun = Functions::where('name', $campo)->get();
                $guardar = true; 
                foreach($hisfunctions as $f){
                    if($f->function_id == $fun[0]->id){
                        $guardar = false; 
                    }
                }
                if($guardar == true){
                    $new_function = new FunctionOfUser;
                    $new_function->user_id = $user->id;
                    $new_function->function_id = $fun[0]->id;
                    $new_function->save();
                }
            }else{
                foreach($hisfunctions as $f){
                    $campo = $name->name;
                    $fun = Functions::where('name', $campo)->get();
                    $idoffunction = $fun[0]->id;
                    if($f->function_id == $idoffunction){
                        $matchThese = ['user_id' => $user->id, 'function_id' => $idoffunction];
                        $funcion_borrar = FunctionOfUser::where($matchThese)->get();
                        $funcion_borrar->each->delete();
                    }
                }
            }
        }

        return $this->getManagementInfo();

    }

    
}
