<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayoutRequest extends FormRequest
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
                'amount' => 'required|min:1|',
                ];
                break;
            case 'update-status':
                $rules = [
                'txn_no' => 'required',
                'remark' => 'required|min:10',
                'status' => 'required|in:1,2',
                ];
                if($this->status == 1)
                    unset($rules['remark']);
                else
                    unset($rules['txn_no']);
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
                'amount.required' => 'Amount is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
