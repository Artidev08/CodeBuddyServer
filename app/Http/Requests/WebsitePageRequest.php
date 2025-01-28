<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebsitePageRequest extends FormRequest
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
                'slug' => 'required|unique:website_pages,slug',
                'content' => 'required',
                ];
                break;
            case 'update':
                $rules = [
                'title' => 'required',
                'slug' => 'required|unique:website_pages,slug,'.$this->id,
                'content' => 'required',
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
                'slug.required' => 'Slug is required',
                'slug.unique' => 'Slug is already taken please rewrite the title',
                'content.required' => 'Content is required',
                // 'status.required' => 'Status is required',
                ];
                break;
            case 'update':
                $messages = [
                'title.required' => 'Title is required',
                'slug.required' => 'Slug is already taken please rewrite the title',
                'slug.unique' => 'Slug is required',
                'content.required' => 'Content is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
