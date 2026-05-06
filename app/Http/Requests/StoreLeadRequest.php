<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'regex:/^[\+\d\s\-\(\)]{10,20}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
            'service_id' => ['nullable', 'exists:services,id'],
            'calculated_price' => ['nullable', 'numeric', 'min:0'],
            'calculated_area' => ['nullable', 'integer', 'min:0'],
            'source' => ['nullable', Rule::in(['form', 'calculator', 'direct', 'phone'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Пожалуйста, укажите ваше имя.',
            'phone.required' => 'Пожалуйста, укажите номер телефона.',
            'phone.regex' => 'Введите корректный номер телефона.',
            'email.email' => 'Введите корректный email адрес.',
        ];
    }
}
