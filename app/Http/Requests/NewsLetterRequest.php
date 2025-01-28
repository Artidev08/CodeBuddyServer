<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsLetterRequest extends FormRequest
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
                // 'group_id'     => 'required',
                'type'     => 'required',
                'value'     => 'required|email',
                ];
                break;
            case 'update':
                $rules = [
                // 'group_id'     => 'required',
                'type'     => 'required',
                'value'     => 'required|email',
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
                'type.required' => 'Type is required',
                'value.required' => 'Value is required',
                ];
                break;
            case 'update':
                $messages = [
                'type.required' => 'Type is required',
                'value.required' => 'Value is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
