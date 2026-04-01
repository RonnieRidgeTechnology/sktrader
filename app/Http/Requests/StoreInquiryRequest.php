<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'company_name'   => ['nullable', 'string', 'max:255'],
            'email'          => ['required', 'email'],
            'whatsapp'       => ['nullable', 'string', 'max:50'],
            'country'        => ['nullable', 'string', 'max:100'],
            'product_interest'=> ['nullable', 'string', 'max:255'],
            'quantity'       => ['nullable', 'string', 'max:100'],
            'message'        => ['required', 'string', 'max:5000'],
            'attachment'     => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,ai,eps', 'max:5120'],
            'website'        => ['nullable', 'max:0'], // honeypot: must be empty
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Please enter your name.',
            'email.required' => 'Please enter your email.',
            'message.required' => 'Please enter your message.',
            'attachment.max' => 'File must not exceed 5MB.',
        ];
    }
}
