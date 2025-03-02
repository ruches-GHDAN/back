<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Apiary;
use Illuminate\Http\Request;
use App\Models\User;

class HistoryController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'date' => 'required|date',
            'description' => 'string',
            'apiary_id' => 'required|integer|exists:apiaries,id'
        ]);

        $history = new History();
        $history->title = $request->input('title');
        $history->date = $request->input('date');
        $history->description = $request->input('description');
        $history->apiary_id = $request->input(key: 'apiary_id');
        $history->save();

        return response()->json([
            'message' => 'History created successfully',
            'history' => $history
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string',
            'date' => 'nullable|date',
            'description' => 'nullable|string'
        ]);

        $history = History::findOrFail($id);

        if ($request->has('title')) {
            $history->title = $request->input('title');
        }
        if ($request->has('date')) {
            $history->date = $request->input('date');
        }
        if ($request->has('description')) {
            $history->description = $request->input('description');
        }

        $history->save();

        return response()->json([
            'message' => 'History updated successfully',
            'history' => $history
        ]);
    }

    public function delete($id)
    {
        $history = History::findOrFail($id);
        $history->delete();

        return response()->json([
            'message' => 'History deleted successfully'
        ]);
    }

    public function getHistoryByApiary(Request $request, $idApiary)
    {
        $request->validate([
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
        ]);

        $query = History::where('apiary_id', $idApiary);

        if ($request->has('startDate') && $request->has('endDate')) {
            $query->whereBetween('date', [$request->input('startDate'), $request->input('endDate')]);
        }

        $history = $query->select(['title', 'date', 'description'])->get();

        return response()->json($history);
    }

    public function getHistoryByUser(Request $request, $idUser)
    {
        $request->validate([
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
        ]);

        $query = User::findOrFail($idUser)->histories();

        if ($request->has('startDate') && $request->has('endDate')) {
            $query->whereBetween('date', [$request->input('startDate'), $request->input('endDate')]);
        }

        $history = $query->select(['title', 'date', 'description','apiary_id'])->get();

        return response()->json($history);
    }

}
