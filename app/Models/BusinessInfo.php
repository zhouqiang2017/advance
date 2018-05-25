<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessInfo extends Model
{
    protected $table = 'ms_business_info';

    public function houses()
    {
        return $this->hasMany(HouseInfo::class, 'agency_id', 'id');
    }
}
