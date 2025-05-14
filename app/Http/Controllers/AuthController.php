<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

        $utilisateur =  new User();

        $utilisateur->nom = $request->nom;
        $utilisateur->prenom = $request->prenom;
        $utilisateur->email = $request->email;
        $utilisateur->mot_de_passe = $request->mot_de_passe;
        $utilisateur->date_naissance = $request->date_naissance;
        $utilisateur->telephone = $request->telephone;
        $utilisateur->genre = $request->genre;
        $utilisateur->pays = $request->pays;
        $utilisateur->ville = $request->ville;
        $utilisateur->date_inscription = now();

        $utilisateur->save();


        // User::create([
        //     'nom' => $request->nom,
        //     'prenom' => $request->prenom,
        //     'email' => $request->email,
        //     'mot_de_passe' => Hash::make($request->mot_de_passe),
        //     'date_naissance' => $request->date_naissance,
        //     'telephone' => $request->telephone,
        //     'genre' => $request->genre,
        //     'pays' => $request->pays,
        //     'ville' => $request->ville,
        //     'date_inscription' => now(),
        // ]);

        // dd();

        $token = $utilisateur->createToken('cinemash_backend_service')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'utilisateur' => $utilisateur
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'mot_de_passe'))) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        $utilisateur = User::where('email', $request->email)->firstOrFail();
        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'utilisateur' => $utilisateur
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }
}
