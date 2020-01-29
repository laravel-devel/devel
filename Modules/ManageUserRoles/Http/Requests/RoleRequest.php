<?php

namespace Modules\ManageUserRoles\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'key' => [
                'required',
                'string',
                'max:191',
            ],
            'name' => [
                'required',
                'string',
                'max:191',
            ],
            'default' => [
                'sometimes',
            ],
            'permissions' => [
                'sometimes',
                'nullable',
                'array',
            ],
        ];

        if (!$this->route('id')) {
            $rules['key'][] = 'unique:user_roles,key';
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
