<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use App\Models\History;
use App\Models\Hive;
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

        foreach ($validatedData['hives'] as $hiveId) {
            $hive = Hive::find($hiveId);
            History::create([
                'apiary_id' => $hive->apiary_id,
                'title' => 'Maladie détectée',
                'date' => now(),
                'description' => "Maladie : {$disease->type} \nRuche : {$hive->registration} \nRucher : {$hive->apiary->name}",
            ]);
        }

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

        $filteredData = array_filter($validatedData, fn($value, $key) => !is_null($value) && $disease->$key != $value, ARRAY_FILTER_USE_BOTH);

        if (!empty($filteredData)) {
            $disease->update($filteredData);
            $hive = $disease->hives()->first();
            History::create([
                'apiary_id' => $hive->apiary->id,
                'title' => 'Maladie modifiée',
                'date' => now(),
                'description' => "Maladie : {$disease->type} \nRuche : {$hive->registration} \nRucher : {$hive->apiary->name} \nDonnées modifiées : " . implode(', ', array_map(fn($key, $value) => "$key : $value", array_keys($filteredData), $filteredData)),
            ]);
        }

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

        $hive = $disease->hives()->first();
        History::create([
            'apiary_id' => $hive->apiary->id,
            'title' => 'Maladie supprimée',
            'date' => now(),
            'description' => "Maladie : {$disease->type} \nRuche : {$hive->registration} \nRucher : {$hive->apiary->name}",
        ]);

        $disease->delete();

        return response()->json(['message' => 'La maladie a bien été supprimée']);
    }
}
