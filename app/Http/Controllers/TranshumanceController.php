<?php

namespace App\Http\Controllers;

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

        $transhumance->update(array_filter($validatedData, fn($value) => !is_null($value)));

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

        $transhumance->delete();

        return response()->json(['message' => 'La transhumance a bien été supprimée'], 200);
    }
}
