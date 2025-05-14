<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilmRequest;
use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index(Request $request)
    {
        $films = Film::with(['genres', 'acteurs'])->get();
        return response()->json($films);
    }

    public function show(Request $request, $id)
    {
        $film = Film::with(['genres', 'acteurs', 'seances.salle'])->findOrFail($id);
        return response()->json($film);
    }

    public function store(FilmRequest $request)
    {
        $film = Film::create($request->validated());

        if ($request->has('acteurs')) {
            foreach ($request->acteurs as $acteur) {
                $film->acteurs()->attach($acteur['id'], [
                    'role_principal' => $acteur['role_principal'] ?? false,
                    'role_nom' => $acteur['role_nom'] ?? null
                ]);
            }
        }

        if ($request->has('genres')) {
            $film->genres()->sync($request->genres);
        }

        return response()->json($film->load(['genres', 'acteurs']), 201);
    }

    public function update(FilmRequest $request, $id)
    {
        $film = Film::findOrFail($id);
        $film->update($request->validated());

        if ($request->has('acteurs')) {
            $film->acteurs()->detach();
            foreach ($request->acteurs as $acteur) {
                $film->acteurs()->attach($acteur['id'], [
                    'role_principal' => $acteur['role_principal'] ?? false,
                    'role_nom' => $acteur['role_nom'] ?? null
                ]);
            }
        }

        if ($request->has('genres')) {
            $film->genres()->sync($request->genres);
        }

        return response()->json($film->load(['genres', 'acteurs']));
    }

    public function destroy(Request $request, $id)
    {
        $film = Film::findOrFail($id);
        $film->delete();

        return response()->json(['message' => 'Film supprimé avec succès']);
    }
}
