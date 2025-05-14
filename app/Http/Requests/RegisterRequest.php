<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mot_de_passe' => ['required', 'confirmed', Password::min(8)],
            'date_naissance' => 'required|date',
            'telephone' => 'required|string|max:20',
            'genre' => 'required|string|in:homme,femme,autre',
            'pays' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
        ];
    }
}
