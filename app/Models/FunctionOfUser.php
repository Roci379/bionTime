<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FunctionOfUser extends Model {


    protected $table = 'functionofuser';
    public $timestamps = false;
    
    protected $primaryKey = 'id';
    // protected $primaryKey = array('function_id', 'user_id');
    /* protected $fillable     = array("number_of_days"); */

    public function functions(){
        return $this->belongsTo(Functions::class, 'function_id', 'id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
