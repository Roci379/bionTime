<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Company extends Model {


    protected $table        = 'companies';
    protected $primaryKey   = 'id';
    protected $fillable     = array("name");

    public function offices(){
        return $this->hasMany(Office::class, 'office_id', 'id');
    }

    public function holidayLimits(){
        return $this->hasOne(HolidayLimit::class, 'company_id', 'id');
    }
}
