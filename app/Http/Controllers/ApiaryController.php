<?php

namespace App\Http\Controllers;

use App\Models\Apiary;
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
}
