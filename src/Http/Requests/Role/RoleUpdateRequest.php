<?php

namespace Yashiroiori\AclManagerTabler\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('add-role') || auth()->user()->isAdmin()){
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
            'name' => 'required|unique:roles,name,'.$this->route('role')->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Ingrese el nombre del rol',
            'name.unique' => 'El nombre del rol ingresado ya se encuentra registrado',
        ];
    }
}
