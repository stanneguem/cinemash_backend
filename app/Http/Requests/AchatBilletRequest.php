<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AchatBilletRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'seance_id' => 'required|exists:seances,id',
            'nombre_places' => 'required|integer|min:1|max:10',
            'promotion_code' => 'sometimes|string|exists:promotions,code',
        ];
    }
}
