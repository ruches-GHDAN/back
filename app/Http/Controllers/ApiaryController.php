<?php

namespace App\Http\Controllers;

use App\Models\Apiary;
use App\Models\History;
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

        History::create([
            'apiary_id' => $apiary->id,
            'title' => 'Création de rucher',
            'date' => now(),
            'description' => "Le rucher {$apiary->name} a été créé.",
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

        $filteredData = array_filter($validatedData, fn($value, $key) => !is_null($value) && $apiary->$key != $value, ARRAY_FILTER_USE_BOTH);

        if (!empty($filteredData)) {
            $apiary->update($filteredData);

            History::create([
                'apiary_id' => $apiary->id,
                'title' => 'Modification de rucher',
                'date' => now(),
                'description' => "Le rucher {$apiary->name} a été modifié : " . implode(', ', array_map(fn($key, $value) => "$key : $value", array_keys($filteredData), $filteredData)),
            ]);
        }

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

        History::create([
            'apiary_id' => $apiary->id,
            'title' => 'Suppression de rucher',
            'date' => now(),
            'description' => "Le rucher {$apiary->name} a été supprimé.",
        ]);

        $apiary->delete();

        return response()->json(['message' => 'Le rucher a bien été supprimé']);
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

    public function locateHives(int $idApiary) {
        $apiary = Apiary::findOrFail($idApiary);
        return $apiary->hives()->select('longitude','latitude')->get();
    }
}
