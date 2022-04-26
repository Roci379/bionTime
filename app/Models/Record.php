<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Record extends Model {


    protected $table        = 'records';
    protected $primaryKey   = 'id';
    protected $fillable     = array("date");

    public function device(){
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }

}
