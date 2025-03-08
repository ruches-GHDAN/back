<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Transhumance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TranshumanceController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'reason' => 'required|string',
            'oldLatitude' => 'required|numeric',
            'oldLongitude' => 'required|numeric',
            'apiary_id' => 'required|exists:apiaries,id',
        ]);

        $transhumance = Transhumance::create([
            'date' => $validatedData['date'],
            'reason' => $validatedData['reason'],
            'oldLatitude' => $validatedData['oldLatitude'],
            'oldLongitude' => $validatedData['oldLongitude'],
            'apiary_id' => $validatedData['apiary_id'],
        ]);

        History::create([
            'apiary_id' => $transhumance->apiary_id,
            'title' => 'Transhumance créée',
            'date' => $transhumance->date,
            'description' => "Transhumance : {$transhumance->reason} \nRucher : {$transhumance->apiary->name}",
        ]);

        return response()->json($transhumance, 201);
    }

    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'date' => 'date',
            'reason' => 'string',
            'oldLatitude' => 'numeric',
            'oldLongitude' => 'numeric',
            'apiary_id' => 'exists:apiaries,id',
        ]);

        $transhumance = Transhumance::find($id);

        if (!$transhumance) {
            return response()->json(['message' => 'Aucune transhumance trouvée'], 404);
        }

        if ($transhumance->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $filteredData = array_filter($validatedData, fn($value, $key) => !is_null($value) && $transhumance->$key != $value, ARRAY_FILTER_USE_BOTH);
        if (!empty($filteredData)) {
            $transhumance->update($filteredData);

            History::create([
                'apiary_id' => $transhumance->apiary->id,
                'title' => 'Transhumance modifiée',
                'date' => now(),
                'description' => "Transhumance : {$transhumance->reason} \nRucher : {$transhumance->apiary->name} \nDonnées modifiées : " . implode(', ', array_map(fn($key, $value) => "$key : $value", array_keys($filteredData), $filteredData)),
            ]);
        }

        return response()->json($transhumance);
    }

    public function delete(int $id)
    {
        $transhumance = Transhumance::find($id);

        if (!$transhumance) {
            return response()->json(['message' => 'Aucune transhumance trouvée'], 404);
        }

        if ($transhumance->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        History::create([
            'apiary_id' => $transhumance->apiary_id,
            'title' => 'Transhumance supprimée',
            'date' => now(),
            'description' => "Transhumance : {$transhumance->reason} \nRucher : {$transhumance->apiary->name}",
        ]);

        $transhumance->delete();

        return response()->json(['message' => 'La transhumance a bien été supprimée']);
    }
}
