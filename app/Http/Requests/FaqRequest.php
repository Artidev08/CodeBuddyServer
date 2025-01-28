<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
                'title'     => 'required',
                'description'     => 'required|min:10',
                ];
                break;
            case 'update':
                $rules = [
                'title'     => 'required',
                'description'     => 'required|min:10',
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
                'title.required' => 'Title is required',
                'description.required' => 'Description is required',
                ];
                break;
            case 'update':
                $messages = [
                'title.required' => 'Title is required',
                'description.required' => 'Description is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
