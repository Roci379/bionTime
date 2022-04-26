<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Office extends Model {

    protected $table        = 'company_offices';
    protected $primaryKey   = 'id';

    public function company(){
        return $this->belongsTo(Company::class, 'office_id', 'id');
    }

    public function province(){
        return $this->belongsTo(IntelProvince::class, 'province_id', 'id');
    }

    public function devices(){
        return $this->hasMany(Device::class, 'office_id', 'id');
    }

    public function timeLimits(){
        return $this->hasOne(TimeLimit::class, 'office_id', 'id');
    }

}
