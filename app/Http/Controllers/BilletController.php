<?php

namespace App\Http\Controllers;

use App\Http\Requests\AchatBilletRequest;
use App\Models\Billet;
use App\Models\Promotion;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BilletController extends Controller
{
    public function achatBillet(AchatBilletRequest $request)
    {
        $utilisateur = $request->user();
        $seance = Seance::findOrFail($request->seance_id);
        $nombrePlaces = $request->nombre_places;
        $promotion = null;
        $prixBase = $seance->prix_base;
        $reduction = 0;

        // Vérifier la disponibilité
        if ($seance->places_disponibles < $nombrePlaces) {
            return response()->json(['message' => 'Places insuffisantes'], 400);
        }

        // Vérifier l'âge minimum
        $ageUtilisateur = $utilisateur->date_naissance->age;
        if ($ageUtilisateur < $seance->film->age_minimum) {
            return response()->json(['message' => 'Âge minimum non atteint'], 403);
        }

        // Appliquer la promotion si disponible
        if ($request->has('promotion_code')) {
            $promotion = Promotion::where('code', $request->promotion_code)
                ->where('date_debut', '<=', now())
                ->where('date_fin', '>=', now())
                ->first();

            if ($promotion) {
                if ($promotion->type === 'pourcentage') {
                    $reduction = $prixBase * ($promotion->reduction / 100);
                } else {
                    $reduction = $promotion->reduction;
                }
            }
        }

        $prixTotal = ($prixBase - $reduction) * $nombrePlaces;

        // Vérifier le solde
        if ($utilisateur->solde_wallet < $prixTotal) {
            return response()->json(['message' => 'Solde insuffisant'], 400);
        }

        // Créer les billets
        $billets = [];
        for ($i = 0; $i < $nombrePlaces; $i++) {
            $billets[] = Billet::create([
                'seance_id' => $seance->id,
                'utilisateur_id' => $utilisateur->id,
                'prix_paye' => $prixBase - $reduction,
                'date_achat' => now(),
                'statut' => 'valide',
                'code_qr' => Str::uuid(),
                'promotion_id' => $promotion ? $promotion->id : null,
            ]);
        }

        // Mettre à jour les places disponibles
        $seance->places_disponibles -= $nombrePlaces;
        $seance->save();

        // Débiter le wallet
        $utilisateur->solde_wallet -= $prixTotal;
        $utilisateur->save();

        // Enregistrer le paiement
        $paiement = $utilisateur->paiements()->create([
            'montant' => $prixTotal,
            'date' => now(),
            'methode' => 'wallet',
            'statut' => 'reussi',
            'type' => 'achat_billet'
        ]);

        return response()->json([
            'message' => 'Billets achetés avec succès',
            'billets' => $billets,
            'nouveau_solde' => $utilisateur->solde_wallet
        ], 201);
    }

    public function annulerBillet(Request $request, $id)
    {
        $billet = Billet::where('utilisateur_id', $request->user()->id)
            ->where('statut', 'valide')
            ->findOrFail($id);

        // Vérifier si la séance n'a pas encore eu lieu
        if ($billet->seance->date_heure < now()) {
            return response()->json(['message' => 'Impossible d\'annuler un billet pour une séance passée'], 400);
        }

        // Rembourser 80% du prix
        $remboursement = $billet->prix_paye * 0.8;
        $billet->utilisateur->solde_wallet += $remboursement;
        $billet->utilisateur->save();

        // Mettre à jour le statut
        $billet->statut = 'annule';
        $billet->save();

        // Mettre à jour les places disponibles
        $billet->seance->places_disponibles += 1;
        $billet->seance->save();

        // Enregistrer le paiement de remboursement
        $paiement = $billet->utilisateur->paiements()->create([
            'montant' => $remboursement,
            'date' => now(),
            'methode' => 'remboursement',
            'statut' => 'reussi',
            'type' => 'recharge_wallet'
        ]);

        return response()->json([
            'message' => 'Billet annulé avec succès',
            'remboursement' => $remboursement,
            'nouveau_solde' => $billet->utilisateur->solde_wallet
        ]);
    }

    public function revendreBillet(Request $request, $id)
    {
        $billet = Billet::where('utilisateur_id', $request->user()->id)
            ->where('statut', 'valide')
            ->findOrFail($id);

        // Vérifier si la séance n'a pas encore eu lieu
        if ($billet->seance->date_heure < now()) {
            return response()->json(['message' => 'Impossible de revendre un billet pour une séance passée'], 400);
        }

        // Mettre le billet en statut "revendu"
        $billet->statut = 'revendu';
        $billet->save();

        return response()->json([
            'message' => 'Billet mis en vente avec succès',
            'billet' => $billet
        ]);
    }

    public function acheterBilletRevendu(Request $request, $id)
    {
        $billet = Billet::where('statut', 'revendu')
            ->findOrFail($id);
        $utilisateur = $request->user();

        // Vérifier l'âge minimum
        $ageUtilisateur = $utilisateur->date_naissance->age;
        if ($ageUtilisateur < $billet->seance->film->age_minimum) {
            return response()->json(['message' => 'Âge minimum non atteint'], 403);
        }

        // Vérifier le solde
        if ($utilisateur->solde_wallet < $billet->prix_paye) {
            return response()->json(['message' => 'Solde insuffisant'], 400);
        }

        // Paiement au revendeur (90% du prix)
        $montantRevendeur = $billet->prix_paye * 0.9;
        $billet->revendeur->solde_wallet += $montantRevendeur;
        $billet->revendeur->save();

        // Débiter l'acheteur
        $utilisateur->solde_wallet -= $billet->prix_paye;
        $utilisateur->save();

        // Mettre à jour le billet
        $billet->utilisateur_id = $utilisateur->id;
        $billet->revendeur_id = $billet->utilisateur_id; // ancien propriétaire
        $billet->statut = 'valide';
        $billet->save();

        // Enregistrer les paiements
        // Pour l'acheteur
        $utilisateur->paiements()->create([
            'montant' => $billet->prix_paye,
            'date' => now(),
            'methode' => 'wallet',
            'statut' => 'reussi',
            'type' => 'achat_billet'
        ]);

        // Pour le revendeur
        $billet->revendeur->paiements()->create([
            'montant' => $montantRevendeur,
            'date' => now(),
            'methode' => 'wallet',
            'statut' => 'reussi',
            'type' => 'recharge_wallet'
        ]);

        return response()->json([
            'message' => 'Billet acheté avec succès',
            'billet' => $billet,
            'nouveau_solde' => $utilisateur->solde_wallet
        ]);
    }

    public function billetsRevendus(Request $request)
    {
        $billets = Billet::where('statut', 'revendu')
            ->with(['seance.film', 'seance.salle', 'utilisateur'])
            ->get();

        return response()->json($billets);
    }
}
