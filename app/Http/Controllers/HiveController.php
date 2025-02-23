<?php

namespace App\Http\Controllers;

use App\Models\Hive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class HiveController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'registration' => 'required|integer',
            'status' => 'required|in:in_use,in_stock',
            'size' => 'required|integer',
            'race' => 'required|string',
            'queenYear' => 'required|integer',
            'temperature' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'apiary_id' => 'required|exists:apiaries,id',
        ]);

        $hive = Hive::create([
            'registration' => $validatedData['registration'],
            'status' => $validatedData['status'],
            'size' => $validatedData['size'],
            'race' => $validatedData['race'],
            'queenYear' => $validatedData['queenYear'],
            'temperature' => $validatedData['temperature'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'apiary_id' => $validatedData['apiary_id'],
        ]);

        return response()->json($hive, 201);
    }

    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'registration' => 'integer',
            'status' => 'in:in_use,in_stock',
            'size' => 'integer',
            'race' => 'string',
            'queenYear' => 'integer',
            'temperature' => 'integer',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'apiary_id' => 'exists:apiaries,id',
        ]);

        $hive = Hive::find($id);

        if (!$hive) {
            return response()->json(['message' => 'Aucune ruche trouvée'], 404);
        }

        if ($hive->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $hive->update(array_filter($validatedData, fn($value) => !is_null($value)));

        return response()->json($hive);
    }

    public function delete(int $id)
    {
        $hive = Hive::find($id);

        if (!$hive) {
            return response()->json(['message' => 'Aucune ruche trouvée'], 404);
        }

        if ($hive->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $hive->delete();

        return response()->json(['message' => 'La ruche a bien été supprimée'], 200);
    }
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
