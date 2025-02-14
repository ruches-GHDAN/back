<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apiary;
use App\Models\User;

class DashboardController extends Controller
{
    public function globalDetail($idUser)
    {
        $user = User::find($idUser);

        $nbApiary = $user->apiaries()->count();

        $nbHives = $user->hives()->count();

        $honeyQuantity = $user->harvests()->count();

        return response()->json([
            'nbApiary' => $nbApiary,
            'nbHives' => $nbHives,
            'honeyQuantity' => $honeyQuantity
        ]);
    }
}
