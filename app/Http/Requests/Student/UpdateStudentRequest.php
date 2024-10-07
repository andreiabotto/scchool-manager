<?php

namespace App\Http\Requests\Student;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'filled|required|string|min:3|max:255',
            'date_of_birth' => 'filled|required|date|date_format:Y-m-d',
            'user_id' => 'filled|required|integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'name.filled' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'name.min' => 'O campo nome deve ter mais de 3 caracteres.',

            'date_of_birth.filled' => 'O campo data de nascimento é obrigatório.',
            'date_of_birth.date' => 'O campo data de nascimento deve ser uma data válida.',
            'date_of_birth.date_format' => 'O campo data de nascimento deve estar no formato Y-m-d (ex: 1990-01-01).',

            'user_id.filled' => 'O campo ID do usuário é obrigatório.',
            'user_id.integer' => 'O campo ID do usuário deve ser um número inteiro.',
            'user_id.exists' => 'O ID do usuário fornecido não existe na tabela de usuários.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $errors,
        ], 422));  // Status 422: Unprocessable Entity
    }
}
