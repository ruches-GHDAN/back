<?php

namespace App\Http\Controllers;

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

        $harvest->update(array_filter($validatedData, fn($value) => !is_null($value)));

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

        $harvest->delete();

        return response()->json(['message' => 'La récolte a bien été supprimée'], 200);
    }
}
