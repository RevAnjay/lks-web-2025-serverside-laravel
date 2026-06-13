<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_card_number' => 'required|exists:societies,id_card_number',
            'password' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'id_card_number.required' => 'ID Card Number is required',
            'id_card_number.exists' => 'ID Card Number does not exist',
            'password.required' => 'Password is required',
        ];
    }
}
