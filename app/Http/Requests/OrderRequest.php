<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
                'user_id'     => 'required',
                'txn_no'     => 'required',
                'discount'     => 'sometimes',
                'tax'     => 'sometimes',
                'sub_total'     => 'required',
                'total'     => 'required',
                'status'     => 'sometimes',
                'payment_gateway'     => 'sometimes',
                'remarks'     => 'sometimes',
                'from'     => 'sometimes',
                'to'     => 'sometimes',
                ];
                break;
            case 'password':
                $rules = [
                'password' => 'required | confirmed ',
                'password' => ' required | min:6',
                ];
                break;
            case 'profile_img':
                $rules = [
                'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
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
        return [
            'first_name.required' => 'First Name is required',
            'email.required' => 'Email is required',
        ];
    }
}
