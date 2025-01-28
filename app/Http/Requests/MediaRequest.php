<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
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
        return [
            'model_type'     => 'required | string ',
            'model_id'     => 'required|numeric',
            'media'     => 'required | string ',
        ];
    }
    public function messages()
    {
        return  [
            'model_type.required'     => 'Model Name is required',
            'model_type.string'     => 'Model Name must be string',
            'model_id.required'    => 'Model Id is required',
            'model_id.numeric'    => 'Model Id must be numeric',
            'media.required' => 'Collection Name is required',
            'media.string'     => 'Collection Name must be string',
        ];
        ;
    }
}
