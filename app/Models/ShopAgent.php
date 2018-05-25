<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopAgent extends Model
{
    protected $table = 'business_shop_agent';

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'business_shop_id', 'id');
    }

    public function agency()
    {
        return $this->belongsTo(BusinessInfo::class, 'agency_id','id');
    }
}
