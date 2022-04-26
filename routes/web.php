<?php

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Route::group(["prefix" => "javi"], function(){

    Route::get('/', [\App\Http\Controllers\JaviDemoController::class, 'view']);
    Route::post('/mark', [\App\Http\Controllers\JaviDemoController::class, 'mark']);
    Route::get('/list', [\App\Http\Controllers\JaviDemoController::class, 'list']);


});

// Add middleware para admin

Route::middleware(['role:admin'])->group(function (){
    Route::get('/management', [\App\Http\Controllers\Users\UserController::class, 'getManagementInfo'])->name('management');
    Route::get('/addnewuser', [\App\Http\Controllers\Users\UserController::class, 'getAddNewUserInfo'])->name('addnewuser');
    Route::get('/modifyUser', [\App\Http\Controllers\Users\UserController::class, 'getModifyUserInfo'])->name('modifyuser');
    Route::get('/reports', [\App\Http\Controllers\Reports\ReportsController::class, 'getUserReportsInfo'])->name('reports');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Records\RecordController::class, 'getDashboardInfo'])->name('dashboard');
    Route::get('/timer', [\App\Http\Controllers\Records\RecordController::class, 'getTimerInfo'])->name('timer');
    Route::get('/calendar', [\App\Http\Controllers\Users\UserController::class, 'getMyCalendarView'])->name('mycalendar');
    Route::get('/ccalendar', [\App\Http\Controllers\Users\UserController::class, 'getCompanyCalendarView'])->name('companycalendar');
    Route::get('/myprofile', [\App\Http\Controllers\Users\UserController::class, 'getMyProfileView'])->name('myprofile');

   

    // POST FUNCTIONS
   
    Route::post('/postRecord', [\App\Http\Controllers\Records\RecordController::class, 'postRecord']);
    Route::post('/postAddNewUser', [\App\Http\Controllers\Users\UserController::class, 'postAddNewUser']);
    
    Route::post('/postImage', [\App\Http\Controllers\Users\UserController::class, 'postImage']);
    Route::post('/postModify', [\App\Http\Controllers\Users\UserController::class, 'modifyUser']);

    /////////
    Route::resource('users', '\App\Http\Controllers\UserController');
    Route::post('/postDeleteUser', [\App\Http\Controllers\Users\UserController::class, 'postDeleteUser']);
    Route::post('/postDeleteRecord', [\App\Http\Controllers\Records\RecordController::class, 'postDeleteRecord']);
    Route::post('/postModifyStartRecord', [\App\Http\Controllers\Records\RecordController::class, 'postModifyStartRecord']);
    Route::post('/postModifyEndRecord', [\App\Http\Controllers\Records\RecordController::class, 'postModifyEndRecord']);
    Route::post('/postModifyUserInfo', [\App\Http\Controllers\Users\UserController::class, 'postModifyUserInfo']);
    Route::post('/postEvent', [\App\Http\Controllers\Holidays\HolidayController::class, 'postEvent']);
    Route::post('/postModifyEventVisibility', [\App\Http\Controllers\Users\UserController::class, 'postModifyEventVisibility']);



    // TO RELOAD TIMER CONTENT
    Route::post('/buttonClicked', [\App\Http\Controllers\Records\RecordController::class, 'buttonClicked']);
    Route::post('/timerValue', [\App\Http\Controllers\Records\RecordController::class, 'buttonClicked']);
    Route::get('/list', [\App\Http\Controllers\Records\RecordController::class, 'getRecords']);


    // TO TEST FUNCTIONS
    // Route::post('/postModifyUser', [\App\Http\Controllers\Users\UserController::class, 'postModifyUser']);
    // Route::get('/timer', [\App\Http\Controllers\Records\RecordController::class, 'getTimerInfos']);
    // Route::get('/companies', [\App\Http\Controllers\Companies\CompanyController::class, 'index']);
    // Route::get('/getPercentages', [\App\Http\Controllers\Records\RecordController::class, 'getDashboardInfo']);
    // Route::post('/postStart', [\App\Http\Controllers\Records\RecordController::class, 'postStart']);
    // Route::post('/postPause', [\App\Http\Controllers\Records\RecordController::class, 'postPause']);
    // Route::post('/postUnpause', [\App\Http\Controllers\Records\RecordController::class, 'postUnpause']);
    // Route::post('/postStop', [\App\Http\Controllers\Records\RecordController::class, 'postStop']);


});




Route::get('/', function () {
    return view('welcome');
});

/*
Route::prefix('/dashboard')->middleware('auth')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        if ($user !== null && $user instanceof App\Models\User) {
            //$user->load('records');
            $user = new UserResource($user);

            return view('dashboard', ['user' => $user->toJson()]);
        } else {
            return view('dashboard');
        }
    })->name('dashboard');
});*/

/*
Route::get('/timer', function () {
    $user = Auth::user();

    if ($user !== null && $user instanceof App\Models\User) {
        $user = new UserResource($user);
        return view('timer', ['user' => $user->toJson()]);
    } else {
        return view('dashboard');
    }
})->middleware('auth');




Route::get('/calendar', function () {
    $user = Auth::user();

    if ($user !== null && $user instanceof App\Models\User) {
        $user = new UserResource($user);
        return view('mycalendar', ['user' => $user->toJson()]);
    } else {
        return view('dashboard');
    }
})->middleware('auth');



Route::get('/ccalendar', function () {
    $user = Auth::user();

    if ($user !== null && $user instanceof App\Models\User) {
        $user = new UserResource($user);
        return view('companycalendar', ['user' => $user->toJson()]);
    } else {
        return view('dashboard');
    }
})->middleware('auth');


Route::get('/reports', function () {
    $user = Auth::user();

    if ($user !== null && $user instanceof App\Models\User) {
        $user = new UserResource($user);
        return view('reports', ['user' => $user->toJson()]);
    } else {
        return view('dashboard');
    }
})->middleware('auth');


Route::get('/management', function () {
    $user = Auth::user();

    if ($user !== null && $user instanceof App\Models\User) {
        $user = new UserResource($user);
        return view('management', ['user' => $user->toJson()]);
    } else {
        return view('dashboard');
    }
})->middleware('auth');

Route::get('/addnewuser', function () {
    $user = Auth::user();

    if ($user !== null && $user instanceof App\Models\User) {
        $user = new UserResource($user);
        return view('addnewuser', ['user' => $user->toJson()]);
    } else {
        return view('dashboard');
    }
})->middleware('auth');*/

/*
Route::get('/myprofile', function () {
    $user = Auth::user();

    if ($user !== null && $user instanceof App\Models\User) {
        $user = new UserResource($user);
        return view('myprofile', ['user' => $user->toJson()]);
    } else {
        return view('dashboard');
    }
})->middleware('auth');
*/

