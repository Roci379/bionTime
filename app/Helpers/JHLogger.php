<?php

namespace App\Helpers;


class JHLogger {

    private $stream = null;
    private $filepath = null;

    public function __construct($file = 'jhlog', $add_date = false){

        if($add_date){
            $today_date = '_' . date("Y-m-d");
        } else {
            $today_date = '';
        }

        $this->filepath = storage_path("logs/$file$today_date.log");

    }


    public function log($something){

        if(!is_resource($this->stream)){

            $dir = dirname($this->filepath);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $this->stream = fopen($this->filepath, 'a');
        }

        $record = JH::better_print_r($something);

        $record = mb_convert_encoding($record, "UTF-8");

        fwrite($this->stream, '[' . date('Y-m-d H:i:s') . '] ');
        fwrite($this->stream, (string) $record . "\n");

    }


    public function close(){
        if(!is_resource($this->stream)){
            fclose($this->stream);
            $this->stream = null;
        }

    }


}