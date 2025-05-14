<?php

namespace App\Models;

use App\Models\Film;
use App\Models\Salle;
use App\Models\Billet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seance extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_id', 'salle_id', 'date_heure',
        'prix_base', 'places_disponibles'
    ];

    protected $casts = [
        'date_heure' => 'datetime',
    ];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function billets()
    {
        return $this->hasMany(Billet::class);
    }
}
