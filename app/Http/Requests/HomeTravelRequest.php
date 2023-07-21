<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeTravelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'from' => 'required|string|min:2|max:255',
                'to' => 'required|string|min:2|max:255',
                'travelDate' => 'required|date',
        ];

    }

    public function messages()
    {
		return [
			'required' => 'Este campo é obrigatório!',
            'string'   => 'Este campo precisa conter uma string',
            'min'      => 'Precisa conter pelo menos 2 caracteres',
            'max'      => 'Tamanho máximo de 255 caracteres',
            'date'     => 'Este campo não tem uma data válida.',

		];
    }
}
