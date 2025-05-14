<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\BilletController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PromotionController;

// Authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    // Authentification
    Route::post('/logout', [AuthController::class, 'logout']);

    // Utilisateur
    Route::get('/profile', [UtilisateurController::class, 'profile']);
    Route::put('/profile', [UtilisateurController::class, 'updateProfile']);
    Route::delete('/profile', [UtilisateurController::class, 'deleteAccount']);
    Route::post('/recharge-wallet', [UtilisateurController::class, 'rechargeWallet']);
    Route::get('/paiements', [PaiementController::class, 'index']);
    Route::get('/mes-billets', [UtilisateurController::class, 'billetsUtilisateur']);

    // Billets
    Route::post('/acheter-billet', [BilletController::class, 'achatBillet']);
    Route::post('/annuler-billet/{id}', [BilletController::class, 'annulerBillet']);
    Route::post('/revendre-billet/{id}', [BilletController::class, 'revendreBillet']);
    Route::post('/acheter-billet-revendu/{id}', [BilletController::class, 'acheterBilletRevendu']);
    Route::get('/billets-revendus', [BilletController::class, 'billetsRevendus']);

    // Promotions
    Route::get('/promotions', [PromotionController::class, 'index']);
    Route::post('/verifier-promotion', [PromotionController::class, 'verifierPromotion']);

    // Films et séances (lecture seule pour les utilisateurs normaux)
    Route::get('/films', [FilmController::class, 'index']);
    Route::get('/films/{id}', [FilmController::class, 'show']);
    Route::get('/seances', [SeanceController::class, 'index']);
    Route::get('/seances/{id}', [SeanceController::class, 'show']);

    // Gestion des utilisateurs
    Route::get('/utilisateurs', [UtilisateurController::class, 'index']);
    Route::put('/utilisateurs/{id}/statut', [UtilisateurController::class, 'updateStatut']);

    // Gestion des films
    Route::post('/films', [FilmController::class, 'store']);
    Route::put('/films/{id}', [FilmController::class, 'update']);
    Route::delete('/films/{id}', [FilmController::class, 'destroy']);

    // Gestion des séances
    Route::post('/seances', [SeanceController::class, 'store']);
    Route::put('/seances/{id}', [SeanceController::class, 'update']);
    Route::delete('/seances/{id}', [SeanceController::class, 'destroy']);

    // Gestion des promotions
    Route::post('/promotions', [PromotionController::class, 'store']);
    Route::put('/promotions/{id}', [PromotionController::class, 'update']);
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy']);
});
