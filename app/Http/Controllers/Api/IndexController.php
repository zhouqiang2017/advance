<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class IndexController extends ApiController
{
    public function index()
    {
        return new  UserCollection(User::paginate(Input::get('limit') ?: 3));
    }
}
