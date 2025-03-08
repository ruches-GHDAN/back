<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\History;
use App\Models\Hive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'type' => 'required|string',
            'quantity' => 'required|integer',
            'hives' => 'required|array|min:1',
            'hives.*' => 'exists:hives,id',
        ]);

        $food = Food::create([
            'date' => $validatedData['date'],
            'type' => $validatedData['type'],
            'quantity' => $validatedData['quantity'],
        ]);

        $food->hives()->attach($validatedData['hives']);

        foreach ($validatedData['hives'] as $hiveId) {
            $hive = Hive::find($hiveId);
            History::create([
                'apiary_id' => $hive->apiary_id,
                'title' => 'Nourriture ajoutée',
                'date' => now(),
                'description' => "Nourriture : {$food->type} \nQuantité : {$food->quantity} \nRuche : {$hive->registration} \nRucher : {$hive->apiary->name}",
            ]);
        }

        return response()->json($food, 201);
    }

    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'date' => 'date',
            'type' => 'string',
            'quantity' => 'integer',
        ]);

        $food = Food::find($id);

        if (!$food) {
            return response()->json(['message' => 'Aucune nourriture trouvée'], 404);
        }

        if ($food->hives()->first()->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $filteredData = array_filter($validatedData, fn($value, $key) => !is_null($value) && $food->$key != $value, ARRAY_FILTER_USE_BOTH);

        if (!empty($filteredData)) {
            $food->update($filteredData);
            $hive = $food->hives()->first();
            History::create([
                'apiary_id' => $hive->apiary->id,
                'title' => 'Nourriture modifiée',
                'date' => now(),
                'description' => "Nourriture : {$food->type} \nQuantité : {$food->quantity} \nRuche : {$hive->registration} \nRucher : {$hive->apiary->name} \nDonnées modifiées : " . implode(', ', array_map(fn($key, $value) => "$key : $value", array_keys($filteredData), $filteredData)),
            ]);
        }

        return response()->json($food);
    }

    public function delete(int $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json(['message' => 'Aucune nourriture trouvée'], 404);
        }

        if ($food->hives()->first()->apiary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'avez pas l\'autorisation'], 403);
        }

        $hive = $food->hives()->first();
        History::create([
            'apiary_id' => $hive->apiary->id,
            'title' => 'Nourriture supprimée',
            'date' => now(),
            'description' => "Nourriture : {$food->type} \nQuantité : {$food->quantity} \nRuche : {$hive->registration} \nRucher : {$hive->apiary->name}",
        ]);

        $food->delete();

        return response()->json(['message' => 'La nourriture a bien été supprimée']);
    }

}
