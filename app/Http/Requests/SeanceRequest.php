<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeanceRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->is_admin;
    }

    public function rules()
    {
        return [
            'film_id' => 'required|exists:films,id',
            'salle_id' => 'required|exists:salles,id',
            'date_heure' => 'required|date|after:now',
            'prix_base' => 'required|numeric|min:0',
            'places_disponibles' => 'required|integer|min:1',
        ];
    }
}
