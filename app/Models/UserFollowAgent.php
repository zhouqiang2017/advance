<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFollowAgent extends Model
{
    protected $table = 'pg_user_follow_agent';

    protected $fillable = ['pg_user_id', 'business_id', 'business_shop_id', 'business_shop_agent_id', 'follow_channel_id'];
}
