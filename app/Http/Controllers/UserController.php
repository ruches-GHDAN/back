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
        $user = $request->user();
        $apiaries = $user->apiaries;

        $apiaryData = $apiaries->map(function ($apiary) {
            $idName = $apiary->only('id', 'name');
            $nbHives = $apiary->hives()->count();
            $honeyQuantityResponse = app('App\Http\Controllers\ApiaryController')->honeyQuantity($apiary->id);
            $honeyQuantity = $honeyQuantityResponse->original['honeyQuantity'];
            $location = $apiary->only('latitude', 'longitude');
            $nbRucheActives = $apiary->hives()->where('status', 'in_use')->count();

            return [
                'id' => $idName['id'],
                'name' => $idName['name'],
                'nbHives' => $nbHives,
                'honeyQuantity' => $honeyQuantity,
                'location' => $location,
                'nbRucheActives' => $nbRucheActives
            ];
        });

        return response()->json($apiaryData);
    }

    public function hives(Request $request)
    {
        $user = $request->user();
        $hives = $user->hives();

        $hiveData = $hives->get()->map(function ($hive) use ($user) {
            $hiveRegistration = $hive->registration;
            $apiaryName = $user->apiaries()->whereHas('hives', function ($query) use ($hive) {
                $query->where('id', $hive->id);
            })->pluck('name')->first();

            $transhumanceDate = $user->transhumances()->select('date')->where('apiary_id', $hive->apiary_id)->first();

            $installDate = $transhumanceDate ? $transhumanceDate : $hive->created_at;

            $disease = $user->apiaries()
                ->join('hives', 'apiaries.id', '=', 'hives.apiary_id')
                ->join('disease_hive', 'hives.id', '=', 'disease_hive.hive_id')
                ->join('diseases', 'disease_hive.disease_id', '=', 'diseases.id')
                ->pluck('diseases.type')
                ->first();

            $queenYear = $hive->queenYear;
            $status = $hive->status;

            return [
                'id' => $hive->id,
                'HiveRegistration' => $hiveRegistration,
                'ApiaryName' => $apiaryName,
                'InstallDate' => $installDate,
                'HiveSize' => $hive->size,
                'Disease' => $disease,
                'QueenYear' => $queenYear,
                'Status' => $status
            ];
        });

        return response()->json(
            $hiveData
        );
    }


}
