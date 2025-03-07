<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use App\Models\Harvest;
use Illuminate\Support\Facades\Auth;

class HarvestController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'quantity' => 'required|integer',
            'apiary_id' => 'required|exists:apiaries,id',
        ]);

        $harvest = Harvest::create([
            'date' => $validatedData['date'],
            'quantity' => $validatedData['quantity'],
            'apiary_id' => $validatedData['apiary_id'],
        ]);

        History::create([
            'apiary_id' => $harvest->apiary_id,
            'title' => 'Récolte créée',
            'date' => $harvest->date,
            'description' => "Récolte : {$harvest->quantity} kg \nRucher : {$harvest->apiary->name}",
        ]);

        return response()->json($harvest, 201);
    }

    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'date' => 'date',
            'quantity' => 'integer',
            'apiary_id' => 'exists:apiaries,id',
        ]);

        $harvest = Harvest::find($id);

        if (!$harvest) {
            return response()->json(['message' => 'Aucune récolte trouvée'], 404);
        }

        if ($harvest->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $filteredData = array_filter($validatedData, fn($value, $key) => !is_null($value) && $harvest->$key != $value, ARRAY_FILTER_USE_BOTH);
        if (!empty($filteredData)) {
            $harvest->update($filteredData);

            History::create([
                'apiary_id' => $harvest->apiary->id,
                'title' => 'Récolte modifiée',
                'date' => now(),
                'description' => "Récolte : {$harvest->quantity} kg \nRucher : {$harvest->apiary->name} \nDonnées modifiées : " . implode(', ', array_map(fn($key, $value) => "$key : $value", array_keys($filteredData), $filteredData)),
            ]);
        }

        return response()->json($harvest);
    }

    public function delete(int $id)
    {
        $harvest = Harvest::find($id);

        if (!$harvest) {
            return response()->json(['message' => 'Aucune récolte trouvée'], 404);
        }

        if ($harvest->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        History::create([
            'apiary_id' => $harvest->apiary->id,
            'title' => 'Récolte supprimée',
            'date' => now(),
            'description' => "Récolte : {$harvest->quantity} kg \nRucher : {$harvest->apiary->name} \nDonnées modifiées : " . implode(', ', array_map(fn($key, $value) => "$key : $value", array_keys($filteredData), $filteredData)),
        ]);

        $harvest->delete();

        return response()->json(['message' => 'La récolte a bien été supprimée'], 200);
    }
}
