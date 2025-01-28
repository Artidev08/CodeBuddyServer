<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportTicketRequest extends FormRequest
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
                'subject' => 'required',
                'message' => 'required',
                'user_id' => 'required',
                'ticket_type_id' => 'required',
                ];
                break;
            case 'reply':
                $rules = [
                'reply' => 'required',
                ];
                break;
            case 'media':
                $rules = [
                'file_name' => 'required',
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
                'subject.required' => 'Subject is required',
                'message.required' => 'Message is required',
                'user_id.required' => 'Customer is required',
                'ticket_type_id.required' => 'Category is required',
                ];
                break;
            case 'reply':
                $messages = [
                'reply.required' => 'Reply is required',
                ];
                break;
            case 'media':
                $messages = [
                'file_name.required' => 'File is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
