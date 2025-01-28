<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParagraphContentRequest extends FormRequest
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
                'code' => 'required',
                'group' => 'required',
                ];
                break;
            case 'update':
                $rules = [
                'code' => 'required',
                'group' => 'required',
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
                'code.required' => 'Code is required',
                'group.required' => 'Group is required',
                ];
                break;
            case 'update':
                $messages = [
                'group.required' => 'Group is required',
                'code.required' => 'Code is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
