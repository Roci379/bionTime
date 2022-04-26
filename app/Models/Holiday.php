<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Holiday extends Model {


    protected $table        = 'holidays';
    protected $primaryKey   = 'id';

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
