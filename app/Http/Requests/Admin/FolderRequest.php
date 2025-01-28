<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FolderRequest extends FormRequest
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
                    'titile'     => 'nullable', 
                    'created_by'     => 'nullable', 
                ];
                break;
            case 'update':
                $rules = [
                    'titile'     => 'nullable',  
                    'created_by'     => 'nullable',                      
                ];
                break;
            default:
                $rules = [];
                break;
        }
        return $rules;
    }
   
}
