<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkRequest extends FormRequest
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
            case 'upload':
                $rules = [
                'file' => 'required',
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
            case 'upload':
                $messages = [
                'file.required' => 'File is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
