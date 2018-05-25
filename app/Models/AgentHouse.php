<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentHouse extends Model
{
    protected $table = 'pg_anget_house';

    protected $fillable = [ 'agent_id', 'house_id','diy_name','sort','status'];

}
