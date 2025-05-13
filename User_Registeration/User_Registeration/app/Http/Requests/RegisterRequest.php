<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to true to allow validation
    }

    public function rules()
    {
        return [
            'fullname' => 'required|string|max:255|regex:/^[a-zA-Z-\' ]*$/',
            'username' => 'required|string|max:255|unique:users|regex:/^\S*$/',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^[0-9]{11}$/',
            'whatsapp_number' => 'nullable|string|regex:/^[0-9]{11}$/',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ];
    }

    public function messages()
    {
        return [
            'fullname.regex' => 'Only letters and spaces are allowed in full name.',
        'username.regex' => 'Username must not contain spaces.',
        'phone.regex' => 'Phone number must be exactly 11 digits.',
        'whatsapp_number.regex' => 'WhatsApp number must be exactly 11 digits.',
        'password.regex' => 'Password must contain at least one number, one uppercase, and one lowercase letter, and be at least 8 characters long.',
        'image.mimes' => 'Image must be a JPG, JPEG, PNG, or GIF.',
        'image.image' => 'Only image files are allowed.',
        ];
    }
}