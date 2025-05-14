<?php

namespace App\Models;

use App\Models\Film;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acteur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'photo_URL', 'biographie'];

    public function films()
    {
        return $this->belongsToMany(Film::class)
                    ->withPivot('role_principal', 'role_nom');
    }
}
