<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilmRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'titre' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'date_sortie' => 'required|date',
            'duree' => 'required|integer|min:1',
            'studio_production' => 'required|string|max:255',
            'age_minimum' => 'sometimes|integer|min:0',
            'image_path' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'acteurs' => 'sometimes|array',
            'acteurs.*.id' => 'required_with:acteurs|exists:acteurs,id',
            'genres' => 'sometimes|array',
            'genres.*' => 'exists:genres,id',
        ];

        if ($this->isMethod('POST')) {
            $rules['image_path'] = 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $rules['image_path'] = 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'titre.required' => 'Le titre est requis.',
            'synopsis.required' => 'Le synopsis est requis.',
            'date_sortie.required' => 'La date de sortie est requise.',
            'duree.required' => 'La durée est requise.',
            'studio_production.required' => 'Le studio de production est requis.',
            'age_minimum.integer' => 'L\'âge minimum doit être un entier.',
            'image_path.image' => 'Le fichier doit être une image.',
            'image_path.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'image_path.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }


}
