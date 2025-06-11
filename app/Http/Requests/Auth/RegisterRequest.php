<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname'  => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'gender'    => 'required|in:Nam,Ná»¯,KhĂ¡c',
            'birthdate' => 'required|date|before:today',
            'phonenb'   => ['required', 'regex:/^0[0-9]{9}$/', 'unique:users,phonenb'],
            'email'     => 'required|string|email|max:255|unique:users,email',
            'username'  => ['required', 'string', 'max:255', 'unique:users,username', 'regex:/^[a-zA-Z0-9]+$/'],
            'password'  => 'required|string|min:6|max:100',
        ];
    }

    public function messages()
    {
        return [
            // Há» tĂªn
            'fullname.required'     => 'Há» vĂ  tĂªn lĂ  báº¯t buá»™c.',
            'fullname.regex'        => 'Há» vĂ  tĂªn chá»‰ Ä‘Æ°á»£c chá»©a chá»¯ cĂ¡i vĂ  khoáº£ng tráº¯ng.',

            // Giá»›i tĂ­nh
            'gender.required'       => 'Giá»›i tĂ­nh lĂ  báº¯t buá»™c.',
            'gender.in'             => 'Giá»›i tĂ­nh khĂ´ng há»£p lá»‡.',

            // NgĂ y sinh
            'birthdate.required'    => 'NgĂ y sinh lĂ  báº¯t buá»™c.',
            'birthdate.date'        => 'NgĂ y sinh khĂ´ng há»£p lá»‡.',
            'birthdate.before'      => 'NgĂ y sinh pháº£i nhá» hÆ¡n ngĂ y hiá»‡n táº¡i.',

            // Sá»‘ Ä‘iá»‡n thoáº¡i
            'phonenb.required'      => 'Sá»‘ Ä‘iá»‡n thoáº¡i lĂ  báº¯t buá»™c.',
            'phonenb.unique'        => 'Sá»‘ Ä‘iá»‡n thoáº¡i Ä‘Ă£ Ä‘Æ°á»£c sá»­ dá»¥ng.',
            'phonenb.regex'         => 'Sá»‘ Ä‘iá»‡n thoáº¡i pháº£i cĂ³ 10 chá»¯ sá»‘ vĂ  báº¯t Ä‘áº§u báº±ng sá»‘ 0.',

            // Email
            'email.required'        => 'Email lĂ  báº¯t buá»™c.',
            'email.email'           => 'Email khĂ´ng há»£p lá»‡.',
            'email.max'             => 'Email khĂ´ng Ä‘Æ°á»£c vÆ°á»£t quĂ¡ 255 kĂ½ tá»±.',
            'email.unique'          => 'Email Ä‘Ă£ Ä‘Æ°á»£c sá»­ dá»¥ng.',

            // TĂªn Ä‘Äƒng nháº­p
            'username.required'     => 'TĂªn ngÆ°á»i dĂ¹ng lĂ  báº¯t buá»™c.',
            'username.unique'       => 'TĂªn ngÆ°á»i dĂ¹ng Ä‘Ă£ Ä‘Æ°á»£c sá»­ dá»¥ng.',
            'username.regex'        => 'TĂªn ngÆ°á»i dĂ¹ng chá»‰ Ä‘Æ°á»£c chá»©a chá»¯ cĂ¡i vĂ  sá»‘, khĂ´ng chá»©a dáº¥u cĂ¡ch hoáº·c kĂ½ tá»± Ä‘áº·c biá»‡t.',

            // Máº­t kháº©u
            'password.required'     => 'Máº­t kháº©u lĂ  báº¯t buá»™c.',
            'password.min'          => 'Máº­t kháº©u pháº£i cĂ³ Ă­t nháº¥t 6 kĂ½ tá»±.',
            'password.max'          => 'Máº­t kháº©u khĂ´ng Ä‘Æ°á»£c vÆ°á»£t quĂ¡ 100 kĂ½ tá»±.',
        ];
    }
}
