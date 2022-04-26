<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Device extends Model {


    protected $table        = 'devices';
    protected $primaryKey   = 'id';
    protected $fillable     = array("name");

    public function office(){
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }

    public function records(){
        return $this->hasMany(Record::class, 'device_id', 'id');
    }

}
