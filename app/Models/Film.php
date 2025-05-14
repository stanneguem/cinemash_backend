<?php

namespace App\Models;

use App\Models\Genre;
use App\Models\Acteur;
use App\Models\Seance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'synopsis', 'date_sortie', 'duree',
        'studio_production', 'image_URL', 'note', 'age_minimum'
    ];

    public function acteurs()
    {
        return $this->belongsToMany(Acteur::class)
                    ->withPivot('role_principal', 'role_nom');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
