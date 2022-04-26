<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TimeLimit extends Model {


    protected $table        = 'holidays_limits';
    protected $primaryKey   = 'id';
    /* protected $fillable     = array("number_of_days"); */

    public function office(){
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }

}
