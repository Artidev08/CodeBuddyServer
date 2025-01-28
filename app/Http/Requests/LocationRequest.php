<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
            case 'country-create':
                $rules = [
                'name' => 'required',
                'iso3' => 'required|min:3|numeric',
                'currency' => 'required|min:3|regex:/^[A-Z]{3}$/',
                'phonecode' => 'required|min:3|numeric',
                ];
                break;
            case 'country-update':
                $rules = [
                'name' => 'required',
                'iso3' => 'required|min:3|numeric',
                'currency' => 'required|min:3|regex:/^[A-Z]{3}$/',
                'phonecode' => 'required|min:3|numeric',
                ];
                break;
            case 'state-create':
                $rules = [
                'name'     => 'required',
                ];
                break;
            case 'state-update':
                $rules = [
                'name'     => 'required',
                ];
                break;
            case 'city-create':
                $rules = [
                'name'     => 'required',

                'latitude' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                'longitude' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                ];
                break;
            case 'city-update':
                $rules = [
                'name'     => 'required',
                'latitude' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                'longitude' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
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
            case 'country-create':
                $messages = [
                'name.required'     => 'Name is required',
                'iso3.required'     => 'Country Code is required',
                ];
                break;
            case 'country-update':
                $messages = [
                'name.required'     => 'Name is required',
                'iso3.required'     => 'Country Code is required',
                ];
                break;
            case 'state-create':
                $messages = [
                'name.required'     => 'Name is required',
                ];
                break;
            case 'state-update':
                $messages = [
                'name.required'     => 'Name is required',
                ];
                break;
            case 'city-create':
                $messages = [
                'name.required'     => 'Name is required',
                'latitude' => 'Latitude is required',
                'longitude' => 'Longitude is required',
                ];
                break;
            case 'city-update':
                $messages = [
                'name.required'  => 'Name is required',
                'latitude' => 'Latitude is required',
                'longitude' => 'Longitude is required',
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
