<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $paiements = Paiement::where('utilisateur_id', $request->user()->id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($paiements);
    }
}
