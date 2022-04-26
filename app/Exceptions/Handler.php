<?php


namespace App\Exceptions;

use Exception; 
use Illuminate\Support\Facades\App;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $e
     * @return void
     * @throws Exception
     */
    public function report($e) {
        //
        // Send exception to the error_log
        // @javi: This is super-super useful!!! (Why I didn't think of it before???)
        // @javi: I can click on the phpstorm log and it opens the file!!!
        // @javi 2020: I will hide this in the test because is not really useful there
        //
        if(!App::environment('production') && !App::runningUnitTests()){
            error_log('-- exception :( --');
            $m = $e->getMessage();
            $f = $e->getFile();
            $l = $e->getLine();
            error_log("$m $f:$l");
            error_log($e->getTraceAsString());
            if($e instanceof NotFoundHttpException){
                error_log("URL: " . request()->url());
            }
            error_log('-- exception :( --');
        }
        if ($this->shouldntReport($e)) {
            return;
        }
        
        parent::report($e);
    }
}
