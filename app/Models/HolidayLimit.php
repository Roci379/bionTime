<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class HolidayLimit extends Model {


    protected $table        = 'holidays_limits';
    protected $primaryKey   = 'id';
    /* protected $fillable     = array("number_of_days"); */

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

}
