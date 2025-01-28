<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->request_with) {
            case 'create':
                $rules = [
                'role' => 'required',
                'id'   => 'required'
                ];
                break;
            case 'update':
                $rules = [
                'role' => 'required',
                'id'   => 'required'
                ];
                break;
            default:
                $rules = [];
                break;
        }
        return $rules;
    }
    public function messages()
    {
        switch ($this->request_with) {
            case 'create':
                $messages = [
                'role.required' => 'Role is required',
                'id.required' => 'Id is required',
                ];
                break;
            case 'update':
                $messages = [
                'role.required' => 'Role is required',
                'id.required' => 'Id is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
