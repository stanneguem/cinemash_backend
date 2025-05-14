<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->is_admin;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:promotions',
            'reduction' => 'required|numeric|min:0',
            'type' => 'required|in:pourcentage,fixe',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'utilisations_max' => 'nullable|integer|min:1',
        ];
    }
}
