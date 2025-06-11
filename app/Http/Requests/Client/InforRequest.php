<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InforRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id(); // Láº¥y ID ngÆ°á»i dĂ¹ng hiá»‡n táº¡i

        return [
            'fullname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u' // Chá»‰ chá»©a chá»¯ vĂ  khoáº£ng tráº¯ng
            ],
            'gender' => 'required|in:Nam,Ná»¯,KhĂ¡c',
            'birthdate' => 'required|date',
            'phonenb' => [
                'required',
                'regex:/^0\d{9}$/', // Báº¯t Ä‘áº§u báº±ng 0, Ä‘á»§ 10 sá»‘
                'unique:users,phonenb,' . $userId . ',user_id', // Kiá»ƒm tra trĂ¹ng ngoáº¡i trá»« báº£n ghi hiá»‡n táº¡i
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $userId . ',user_id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Há» vĂ  tĂªn lĂ  báº¯t buá»™c.',
            'fullname.regex' => 'Há» vĂ  tĂªn chá»‰ Ä‘Æ°á»£c chá»©a chá»¯ cĂ¡i vĂ  khoáº£ng tráº¯ng.',
            'gender.required' => 'Giá»›i tĂ­nh lĂ  báº¯t buá»™c.',
            'gender.in' => 'Giá»›i tĂ­nh khĂ´ng há»£p lá»‡.',
            'birthdate.required' => 'NgĂ y sinh lĂ  báº¯t buá»™c.',
            'phonenb.required' => 'Sá»‘ Ä‘iá»‡n thoáº¡i lĂ  báº¯t buá»™c.',
            'phonenb.regex' => 'Sá»‘ Ä‘iá»‡n thoáº¡i pháº£i báº¯t Ä‘áº§u báº±ng 0 vĂ  cĂ³ Ä‘Ăºng 10 chá»¯ sá»‘.',
            'phonenb.unique' => 'Sá»‘ Ä‘iá»‡n thoáº¡i Ä‘Ă£ Ä‘Æ°á»£c sá»­ dá»¥ng.',
            'email.required' => 'Email lĂ  báº¯t buá»™c.',
            'email.email' => 'Email khĂ´ng Ä‘Ăºng Ä‘á»‹nh dáº¡ng.',
            'email.unique' => 'Email Ä‘Ă£ Ä‘Æ°á»£c sá»­ dá»¥ng.',
        ];
    }
}
