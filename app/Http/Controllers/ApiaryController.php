<?php

namespace App\Http\Controllers;

use App\Models\Apiary;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiaryController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'temperature' => 'required|integer',
        ]);

        $apiary = Apiary::create([
            'name' => $validatedData['name'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'temperature' => $validatedData['temperature'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($apiary, 201);
    }

    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'name' => 'name',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'temperature' => 'integer',
        ]);

        $apiary = Apiary::find($id);

        if (!$apiary) {
            return response()->json(['message' => 'Aucun rucher trouvé'], 404);
        }

        if ($apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $apiary->update(array_filter($validatedData, fn($value) => !is_null($value)));

        return response()->json($apiary);
    }

    public function delete(int $id)
    {
        $apiary = Apiary::find($id);

        if (!$apiary) {
            return response()->json(['message' => 'Aucun rucher trouvé'], 404);
        }

        if ($apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $apiary->delete();

        return response()->json(['message' => 'Le rucher a bien été supprimé'], 200);
    }

    public function about(int $idApiary): JsonResponse
    {
        $apiary = Apiary::findOrFail($idApiary);

        $nbHives = $this->nbHives($idApiary)->getData(true)['nbHives'];

        $honeyQuantity = $this->honeyQuantity($idApiary)->getData(true)['honeyQuantity'];

        return response()->json([
            'apiary' => $apiary,
            'nbHives' => $nbHives,
            'honeyQuantity' => $honeyQuantity
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

    public function honeyQuantity(int $idApiary): JsonResponse
    {
        $apiary = Apiary::findOrFail($idApiary);

        $honeyQuantity = $apiary->harvests()->sum('quantity');

        return response()->json([
            'honeyQuantity' => $honeyQuantity
        ]);
    }

    public function recentlyTranshumed(Request $request, int $idApiary): JsonResponse
    {
        $apiary = Apiary::findOrFail($idApiary);

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if ($startDate == null || $endDate == null) {
            $transhumedDates = $apiary->transhumances()
            ->pluck('date');
        }
        else {
            $transhumedDates = $apiary->transhumances()
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('date');
        }

        return response()->json([
            'transhumedDates' => $transhumedDates
        ]);
    }
}
