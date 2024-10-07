<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'filled|required|string|max:255',
            'email' => 'filled|required|string|email|max:255',
            'is_admin' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.filled' => 'Campo Nome precisa ser preenchido',
            'name.required' => 'Campo Nome precisa ser preenchido',
            'name.string' => 'Campo Nome precisa ser texto',
            'name.max:255' => 'Campo Nome precisa ter mais do 255 caracteres',
            'email.filled' => 'Campo E-mail precisa ser preenchido',
            'email.required' => 'Campo E-mail precisa ser preenchido',
            'email.string' => 'Campo E-mail precisa ser texto',
            'email.email' => 'Campo E-mail precisa ser valido',
            'email.unique' => 'Campo E-mail ja existe',
            'password.filled' => 'Campo senha precisa ser preenchido',
            'password.string' => 'Campo Senha precisa ser texto',
            'password.min:6' => 'Campo Senha precisa ter ao menos 6 caracteres',
            'password.confirmed' => 'Campo Senha precisa ser confirmado',
            'is_admin.boolean' => 'Campo is_admin precisa ser selecionado',
        ];
    }
}
