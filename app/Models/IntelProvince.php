<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class IntelProvince extends Model{

    protected $table        = 'intel_province';
    protected $primaryKey   = 'id';
    protected $fillable     = array("province_name", "country_name");


    public function offices(){
        return $this->hasMany(Office::class, 'province_id', 'id');
    }

}
