<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function apiaries(Request $request)
    {
        return response()->json($request->user()->apiaries()->select('id', 'name', 'longitude', 'latitude', 'temperature')->get());
    }

}
