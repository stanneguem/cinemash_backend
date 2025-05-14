<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id', 'montant', 'date',
        'methode', 'statut', 'type'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class);
    }
}
