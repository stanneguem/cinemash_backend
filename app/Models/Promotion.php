<?php

namespace App\Models;

use App\Models\Billet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'reduction', 'type',
        'date_debut', 'date_fin', 'utilisations_max'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function billets()
    {
        return $this->hasMany(Billet::class);
    }
}
