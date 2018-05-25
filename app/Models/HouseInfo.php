<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseInfo extends Model
{
    protected $table = 'business_house_info';

    protected $keyType = 'string';

    public function images()
    {
        return $this->hasMany(HouseInterImg::class,'xz_house_id', 'id');
    }
}
