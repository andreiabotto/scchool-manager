<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'filled|required|string|min:3|max:255',
            'description' => 'filled|required|string|max:500',
            'type' => 'filled|required|integer|between:0,3',
        ];
    }

    public function messages()
    {
        return [
            'name.filled' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'name.min' => 'O campo nome deve ter mais de 3 caracteres.',
            'description.filled' => 'O campo descrição é obrigatório.',
            'description.string' => 'O campo descrição deve ser uma string.',
            'description.max' => 'O campo descrição não pode ter mais de 500 caracteres.',
            'type.filled' => 'O campo tipo é obrigatório.',
            'type.integer' => 'O campo tipo deve ser um número inteiro.',
            'type.between' => 'O campo tipo deve estar entre 0 e 3.',
        ];
    }
}
