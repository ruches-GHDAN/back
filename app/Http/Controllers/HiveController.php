<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hive;
use Illuminate\Http\JsonResponse;

class HiveController extends Controller
{
    public function about(int $idHive): JsonResponse
    {
        $hive = Hive::findOrFail($idHive);

        $apiary = $hive->apiary()->select(['id','name'])->firstOrFail();

        return response()->json([
            'hive' => $hive,
            'apiary' => $apiary
        ]);
    }

    public function isSick(int $idHive) : JsonResponse
    {
        $hive = Hive::findOrFail($idHive);

        $isSick = $hive->diseases()->where('hive_id', $idHive)->whereNull('dateEnd')->exists();

        return response()->json([
            'isSick' => $isSick
        ]);
    }

    public function wasSick(int $idHive) : JsonResponse
    {
        $hive = Hive::findOrFail($idHive);

        $wasSick = $hive->diseases()->where('hive_id', $idHive)->whereNotNull('dateEnd')->exists();

        return response()->json([
            'wasSick' => $wasSick
        ]);
    }
}
