<?php

namespace Yashiroiori\AclManagerTabler\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('add-user') || auth()->user()->isAdmin()){
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Ingrese el nombre del usuario',
            'email.required' => 'Ingrese el correo electrónico del usuario',
            'email.unique' => 'El correo electrónico ya se ha registrado',
        ];
    }
}
