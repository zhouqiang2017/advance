<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOpenLog extends Model
{
    protected $table = 'pg_user_open_log';

    protected $fillable = ['pg_user_id', 'business_id', 'business_shop_id', 'business_shop_agent_id', 'follow_channel_id', 'note','pg_share_user_id'];
}
