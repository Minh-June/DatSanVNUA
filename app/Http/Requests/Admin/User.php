<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class User extends FormRequest
{
    /**
     * XĂ¡c Ä‘á»‹nh ngÆ°á»i dĂ¹ng cĂ³ quyá»n gá»­i yĂªu cáº§u nĂ y khĂ´ng.
     */
    public function authorize(): bool
    {
        return true; // Cho phĂ©p thá»±c thi request
    }

    /**
     * CĂ¡c quy táº¯c xĂ¡c thá»±c Ă¡p dá»¥ng cho request.
     */
    public function rules(): array
    {
        return [
            'keyword' => [
                'required',
                'regex:/^[a-zA-ZĂ€-á»¹\s\-]+$/u', // Chá»‰ cho phĂ©p chá»¯ cĂ¡i vĂ  khoáº£ng tráº¯ng
            ],
        ];
    }

    /**
     * TĂ¹y chá»‰nh thĂ´ng bĂ¡o lá»—i.
     */
    public function messages(): array
    {
        return [
            'keyword.required' => 'Vui lĂ²ng nháº­p tá»« khĂ³a tĂ¬m kiáº¿m.',
            'keyword.regex' => 'Tá»« khĂ³a khĂ´ng Ä‘Æ°á»£c chá»©a sá»‘ hoáº·c kĂ½ tá»± Ä‘áº·c biá»‡t.',
        ];
    }
}
