<?php

namespace App\Http\Controllers;

use App\Models\Apiary;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiaryController extends Controller
{
    public function about(int $idApiary): JsonResponse
    {
        $apiary = Apiary::findOrFail($idApiary);

        $nbHives = $this->nbHives($idApiary);

        return response()->json([
            'apiary' => $apiary,
            'nbHives' => $nbHives
        ]);
    }

    public function nbApiaries(int $idUser): JsonResponse
    {
        $user = User::findOrFail($idUser);

        $nbApiaries = $user->apiaries()->count();

        return response()->json([
            'nbApiaries' => $nbApiaries
        ]);
    }

    public function nbHives(int $idApiary): JsonResponse
    {
        $apiary = Apiary::findOrFail($idApiary);

        $nbHives = $apiary->hives()->count();

        return response()->json([
            'nbHives' => $nbHives
        ]);
    }

    public function getAllLocation(int $idUser):JsonResponse
    {
        $user = User::findOrFail($idUser);

        $locations = $user->apiaries()->select('latitude', 'longitude')->get();

        return response()->json([
            'locations' => $locations
        ]);
    }

    public function status(int $idApiary): JsonResponse
    {
        $apiary = Apiary::findOrFail($idApiary);

        $statusInUse = $apiary->hives()->where('status', 'in_use')->count();
        $statusInStock = $apiary->hives()->where('status', 'in_stock')->count();

        $status = [
            'in_use' => $statusInUse,
            'in_stock' => $statusInStock
        ];

        return response()->json([
            'status' => $status
        ]);
    }

    public function hasSickHive(int $idApiary): JsonResponse
    {
        $apiary = Apiary::findOrFail($idApiary);

        $sickHive = $apiary->hives()->whereHas('diseases', function ($query) {
            $query->whereNull('dateEnd');
        })->exists();

        return response()->json([
            'sickHive' => $sickHive
        ]);
    }
}
