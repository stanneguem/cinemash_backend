<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeanceRequest;
use App\Models\Seance;
use Illuminate\Http\Request;

class SeanceController extends Controller
{
    public function index(Request $request)
    {
        $seances = Seance::with(['film', 'salle'])->get();
        return response()->json($seances);
    }

    public function show(Request $request, $id)
    {
        $seance = Seance::with(['film', 'salle'])->findOrFail($id);
        return response()->json($seance);
    }

    public function store(SeanceRequest $request)
    {
        $seance = Seance::create($request->validated());
        return response()->json($seance->load(['film', 'salle']), 201);
    }

    public function update(SeanceRequest $request, $id)
    {
        $seance = Seance::findOrFail($id);
        $seance->update($request->validated());
        return response()->json($seance->load(['film', 'salle']));
    }

    public function destroy(Request $request, $id)
    {
        $seance = Seance::findOrFail($id);
        $seance->delete();
        return response()->json(['message' => 'Séance supprimée avec succès']);
    }
}
