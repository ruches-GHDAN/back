<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiseaseController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'dateStart' => 'required|date',
            'dateEnd' => 'date',
            'treatment' => 'required|string',
            'type' => 'required|string',
            'hives' => 'required|array|min:1',
            'hives.*' => 'exists:hives,id',
        ]);

        $disease = Disease::create([
            'dateStart' => $validatedData['dateStart'],
            'dateEnd' => $validatedData['dateEnd'],
            'treatment' => $validatedData['treatment'],
            'type' => $validatedData['type'],
        ]);

        $disease->hives()->attach($validatedData['hives']);

        return response()->json($disease, 201);
    }

    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'dateStart' => 'date',
            'dateEnd' => 'date',
            'treatment' => 'string',
            'type' => 'string',
        ]);

        $disease = Disease::find($id);

        if (!$disease) {
            return response()->json(['message' => 'Aucune maladie trouvée'], 404);
        }

        if ($disease->hives()->first()->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $disease->update(array_filter($validatedData, fn($value) => !is_null($value)));

        return response()->json($disease);
    }

    public function delete(int $id)
    {
        $disease = Disease::find($id);

        if (!$disease) {
            return response()->json(['message' => 'Aucune maladie trouvée'], 404);
        }

        if ($disease->hives()->first()->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $disease->delete();

        return response()->json(['message' => 'La maladie a bien été supprimée'], 200);
    }
}
