<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotionRequest;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $promotions = Promotion::all();
        return response()->json($promotions);
    }

    public function store(PromotionRequest $request)
    {
        $promotion = Promotion::create($request->validated());
        return response()->json($promotion, 201);
    }

    public function show(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        return response()->json($promotion);
    }

    public function update(PromotionRequest $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->update($request->validated());
        return response()->json($promotion);
    }

    public function destroy(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
        return response()->json(['message' => 'Promotion supprimée avec succès']);
    }

    public function verifierPromotion(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $promotion = Promotion::where('code', $request->code)
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now())
            ->first();

        if (!$promotion) {
            return response()->json(['message' => 'Code promotionnel invalide ou expiré'], 404);
        }

        return response()->json($promotion);
    }
}
