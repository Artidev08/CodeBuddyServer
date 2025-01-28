<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
                'name' => 'required',
                'slug'     => 'unique:items,slug',
                'sku'     => 'unique:items,sku',
                ];
                break;
            case 'update':
                $rules = [
                'name' => 'required',
                'slug'     => 'unique:items,slug,deleted_at,NULL,'.$this->id,
                'sku'     => 'unique:items,sku,deleted_at,NULL,'.$this->id,
                ];
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
                'slug.required' => 'Slug is required',
                ];
                break;
            case 'update':
                $messages = [
                'name.required' => 'Name is required',
                'slug.required' => 'Slug is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
