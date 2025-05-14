<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilmRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->is_admin;
    }

    public function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'date_sortie' => 'required|date',
            'duree' => 'required|integer|min:1',
            'studio_production' => 'required|string|max:255',
            'image_URL' => 'sometimes|url',
            'age_minimum' => 'sometimes|integer|min:0',
            'acteurs' => 'sometimes|array',
            'acteurs.*.id' => 'required|exists:acteurs,id',
            'acteurs.*.role_principal' => 'sometimes|boolean',
            'acteurs.*.role_nom' => 'sometimes|string|max:255',
            'genres' => 'sometimes|array',
            'genres.*' => 'exists:genres,id',
        ];
    }
}
