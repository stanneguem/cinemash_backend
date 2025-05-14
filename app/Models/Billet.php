<?php

namespace App\Models;

use App\Models\User;
use App\Models\Seance;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Billet extends Model
{
    use HasFactory;

    protected $fillable = [
        'seance_id', 'utilisateur_id', 'prix_paye',
        'date_achat', 'statut', 'code_qr',
        'promotion_id', 'revendeur_id'
    ];

    protected $casts = [
        'date_achat' => 'datetime',
    ];

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function revendeur()
    {
        return $this->belongsTo(User::class, 'revendeur_id');
    }
}
