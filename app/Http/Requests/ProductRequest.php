<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        switch ($this->request_with) {
            case 'listing':
                $rules = [
                'search' => 'string',
                'status' => 'in:Active,Inactive|nullable',
                'page' => 'numeric|nullable',
                'limit' => 'numeric|nullable',
                ];
                break;
            case 'create':
                $rules = [
                'name' => 'required|max:255',
                'quantity' => 'required|numeric',
                'price' => 'required|numeric',
                'compare_at_price' => 'required|numeric',
                'status' => 'required|in:Active,Inactive',
                ];
                break;
            case 'create_variant':
                $rules = [
                'product_id' => 'required|numeric',
                'name' => 'required|max:255',
                'quantity' => 'required|numeric',
                'price' => 'required|numeric',
                'compare_at_price' => 'required|numeric',
                'status' => 'required|in:Active,Inactive',
                ];
                break;
            case 'edit':
                $rules = [
                'name' => 'required|max:255',
                'quantity' => 'required|numeric',
                'price' => 'required|numeric',
                'url' => 'required',
                'compare_at_price' => 'required|numeric',
                'status' => 'required|in:Active,Inactive',
                ];
                break;
            case 'action':
                $rules = [
                'ids' => 'required|array|min:1',
                'action' => 'required|in:Active,Inactive,Delete,Move To Trash,Delete Permanently,Restore,Export',
                ];
                break;
            case 'destroy-media':
                $rules = [
                'media' => 'required|string',
                ];
                break;
            case 'sorting':
                $rules = [
                'ids' => 'required|array|min:1',
                ];
                break;
            case 'import':
                $rules = [
                'file' => 'mimes:xls,xlsx,csv',
                ];
                break;
            default:
                $rules = [];
                break;
        }
        return $rules;
    }
}
