<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * change
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
                'name' => 'required',
                'level' => 'required',
                'category_type_id' => 'required_without:category_type_code',
                'category_type_code' => 'required_without:category_type_id',
                ];
                break;
            case 'update':
                $rules = [
                'name' => 'required',
                'level' => 'required',
                'category_type_id' => 'required_without:category_type_code',
                'category_type_code' => 'required_without:category_type_id',
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
                'name.required' => 'Name is required',
                'level.required' => 'Level is required',
                'category_type_id.required' => 'Category is required',
                ];
                break;
            case 'update':
                $messages = [
                'name.required' => 'Name is required',
                'level.required' => 'Level is required',
                'category_type_id.required' => 'Category is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
