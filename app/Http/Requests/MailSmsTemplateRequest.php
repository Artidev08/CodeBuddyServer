<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailSmsTemplateRequest extends FormRequest
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
                'code' => 'required|unique:mail_sms_templates,code',
                'subject' => 'required',
                'type' => 'required',
                'purpose' => 'required',
                ];
                break;
            case 'update':
                $rules = [
                'code' => 'required',
                'subject' => 'required',
                'purpose' => 'required',
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
                'code.required' => 'Code Name is required',
                'subject.required' => 'Subject is required',
                'type.required' => 'Type is required',
                ];
                break;
            case 'update':
                $messages = [
                'code.required' => 'Code Name is required',
                'title.required' => 'Title Banner is required',
                'variables.required' => 'Variables is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
