<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Billet;
use App\Models\Paiement;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'nom', 'prenom', 'email', 'date_naissance', 'telephone',
        'mot_de_passe', 'genre', 'pays', 'ville', 'image_URL',
        'solde_wallet', 'statut', 'is_admin'
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
    ];

        public function billets()
    {
        return $this->hasMany(Billet::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function billetsRevendus()
    {
        return $this->hasMany(Billet::class, 'revendeur_id');
    }

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}
