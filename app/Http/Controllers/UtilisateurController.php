<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\RechargeWalletRequest;

class UtilisateurController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $utilisateur = $request->user();

        $utilisateur->update($request->validated());

        return response()->json($utilisateur);
    }

    public function deleteAccount(Request $request)
    {
        $utilisateur = $request->user();

        // Annuler tous les billets non utilisés
        $utilisateur->billets()
            ->where('statut', 'valide')
            ->update(['statut' => 'annule']);

        // Supprimer le compte
        $utilisateur->delete();

        return response()->json(['message' => 'Compte supprimé avec succès']);
    }

    public function rechargeWallet(RechargeWalletRequest $request)
    {
        $utilisateur = $request->user();
        $montant = $request->montant;

        // Créer un paiement
        $paiement = Paiement::create([
            'utilisateur_id' => $utilisateur->id,
            'montant' => $montant,
            'date' => now(),
            'methode' => $request->methode,
            'statut' => 'reussi',
            'type' => 'recharge_wallet'
        ]);

        // Mettre à jour le solde
        $utilisateur->solde_wallet += $montant;
        $utilisateur->save();

        return response()->json([
            'message' => 'Portefeuille rechargé avec succès',
            'nouveau_solde' => $utilisateur->solde_wallet
        ]);
    }

    public function historiquePaiements(Request $request)
    {
        $paiements = $request->user()->paiements()->orderBy('date', 'desc')->get();
        return response()->json($paiements);
    }

    public function billetsUtilisateur(Request $request)
    {
        $billets = $request->user()->billets()->with('seance.film', 'seance.salle')->get();
        return response()->json($billets);
    }

    // Méthodes pour l'admin
    public function index(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $utilisateurs = Utilisateur::all();
        return response()->json($utilisateurs);
    }

    public function updateStatut(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $request->validate([
            'statut' => 'required|in:actif,inactif,banni'
        ]);

        $utilisateur = User::findOrFail($id);
        $utilisateur->statut = $request->statut;
        $utilisateur->save();

        return response()->json($utilisateur);
    }
}
