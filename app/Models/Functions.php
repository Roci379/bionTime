<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Functions extends Model {


    protected $table        = 'function';
    protected $primaryKey   = 'id';
    protected $fillable     = array("name");

    public function offices(){
        return $this->hasMany(FunctionOfUser::class, 'function_id', 'id');
    }

}
