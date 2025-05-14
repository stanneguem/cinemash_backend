<?php

namespace App\Models;

use App\Models\Seance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'capacite', 'adresse', 'ville', 'equipements'];

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
