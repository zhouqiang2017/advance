<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'business_shop';

    public function agent()
    {
        return $this->hasMany(ShopAgent::class, 'id', 'business_shop_id');
    }
}
