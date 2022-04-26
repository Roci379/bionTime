<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;

class JaviDemoController extends Controller {




    public function view(){

        return view('javidemo');

    } 


    public function mark(Request $request){

        $this->validate($request, [
            'action' => 'string|required|in:start,stop'
        ]);

        $data = $this->getStatusAndRecords();


        if($request->action === 'start'){

            if($data->status !== 'stopped' ){
                abort("Cannot start if status != stopped");
            }

            $data->status = 'working';
            $data->records[] = (object) [
                'init' => date("Y-m-d H:i:s"),
                'end' => null,
            ];

        } else if ($request->action === 'stop'){

            if($data->status !== 'working' ){
                abort("Cannot stop if status != working");
            }

            $data->status = 'stopped';
            $data->records[count($data->records)-1]->end = date("Y-m-d H:i:s");
        }
        
        // Save in session
        session(['demo-status-record' => $data]);


    }


    public function list(){


        $data = $this->getStatusAndRecords();

        return response()->json($data);

    }

    private function getStatusAndRecords(){

        $default = (object) [
            "status" => "stopped",
            "records" => [], 
        ];

        return session('demo-status-record', $default);

    }


}
