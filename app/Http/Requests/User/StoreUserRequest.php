<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', 'min:6', Rules\Password::defaults()],
            'is_admin' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Campo Nome precisa ser preenchido',
            'name.string' => 'Campo Nome precisa ser texto',
            'name.max:255' => 'Campo Nome precisa ter mais do 255 caracteres',
            'email.required' => 'Campo E-mail precisa ser preenchido',
            'email.string' => 'Campo E-mail precisa ser texto',
            'email.email' => 'Campo E-mail precisa ser valido',
            'email.unique' => 'Campo E-mail ja existe',
            'password.required' => 'Campo Senha precisa ser preenchido',
            'password.string' => 'Campo Senha precisa ser texto',
            'password.min:6' => 'Campo Senha precisa ter ao menos 6 caracteres',
            'password.confirmed' => 'Campo Senha precisa ser confirmado',
            'is_admin.boolean' => 'Campo is_admin precisa ser selecionado',
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
