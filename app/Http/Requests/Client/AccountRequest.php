<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'fullname' => 'required|string|max:255',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'phonenb' => 'required|string|max:10|regex:/^[0-9]+$/',
            'email' => 'required|string|email|max:255|unique:users,email,' . session('user_id') . ',user_id',
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => 'Há» vĂ  tĂªn lĂ  báº¯t buá»™c.',
            'gender.required' => 'Giá»›i tĂ­nh lĂ  báº¯t buá»™c.',
            'birthdate.required' => 'NgĂ y sinh lĂ  báº¯t buá»™c.',
            'phonenb.required' => 'Sá»‘ Ä‘iá»‡n thoáº¡i lĂ  báº¯t buá»™c.',
            'phonenb.regex' => 'Sá»‘ Ä‘iá»‡n thoáº¡i chá»‰ Ä‘Æ°á»£c chá»©a cĂ¡c kĂ½ tá»± sá»‘.',
            'email.required' => 'Email lĂ  báº¯t buá»™c.',
            'email.unique' => 'Email Ä‘Ă£ Ä‘Æ°á»£c sá»­ dá»¥ng.',
        ];
    }
}
