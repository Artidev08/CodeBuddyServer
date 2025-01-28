<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
                'first_name'     => 'required | string|max:30 ',
                'last_name'     => 'required | string |max:30',
                'email'    => 'required|email|unique:users,email,'.$this->id,
                'phone' => 'required|regex:/^[6-9]\d{9}$/|min:10|max:12',
                'dob' => 'before:today',
                'gender' => 'nullable|in:Male,Female',
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
                'phone.required' => 'Phone is required',
                'dob.required' => 'DOB should be before today',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
