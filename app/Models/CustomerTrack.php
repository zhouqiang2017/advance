<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTrack extends Model
{
    protected $table = 'pg_customer_track';

    protected $fillable = ['id', 'customer_id', 'agent_id', 'info','rank'];
}
