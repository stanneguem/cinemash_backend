<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:utilisateurs,email,'.$this->user()->id,
            'date_naissance' => 'sometimes|date',
            'telephone' => 'sometimes|string|max:20',
            'genre' => 'sometimes|string|in:homme,femme,autre',
            'pays' => 'sometimes|string|max:255',
            'ville' => 'sometimes|string|max:255',
            'image_URL' => 'sometimes|string|url',
        ];
    }
}
