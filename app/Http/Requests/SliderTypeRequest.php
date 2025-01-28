<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderTypeRequest extends FormRequest
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
                'title' => 'required',
                'code' => 'required|unique:slider_types,code',
                ];
                break;
            case 'update':
                $rules = [
                'title' => 'required',
                'code' => 'required|unique:slider_types,code,'.$this->id,
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
                'title.required' => 'Title Name is required',
                ];
                break;
            case 'update':
                $messages = [
                'title.required' => 'Title Name is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
