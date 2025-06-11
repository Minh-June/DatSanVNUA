<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PickRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'        => 'required',
            'yard_id'        => 'required',
            'name'           => 'required|string|max:255',
            'phone'          => 'required|regex:/^[0-9]{10}$/',
            'notes'          => 'nullable|string',
            'selected_times' => 'required|array|min:1',      // Äá»•i thĂ nh array
            'selected_times.*' => 'string',                   // Má»—i pháº§n tá»­ trong máº£ng lĂ  string
            'date'           => 'required|date|after_or_equal:today',
            'total_price'    => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'        => 'ID ngÆ°á»i dĂ¹ng lĂ  báº¯t buá»™c.',
            'yard_id.required'        => 'ID sĂ¢n lĂ  báº¯t buá»™c.',
            'name.required'           => 'TĂªn lĂ  báº¯t buá»™c.',
            'phone.required' => 'Sá»‘ Ä‘iá»‡n thoáº¡i lĂ  báº¯t buá»™c.',
            'phone.regex' => 'Sá»‘ Ä‘iá»‡n thoáº¡i chá»‰ Ä‘Æ°á»£c chá»©a sá»‘ vĂ  pháº£i cĂ³ Ä‘Ăºng 10 chá»¯ sá»‘.',
            'selected_times.required' => 'Vui lĂ²ng chá»n Ă­t nháº¥t má»™t khung giá».',
            'date.required'           => 'NgĂ y lĂ  báº¯t buá»™c.',
            'date.after_or_equal'     => 'NgĂ y Ä‘áº·t pháº£i lĂ  hĂ´m nay hoáº·c sau.',
            'total_price.required'    => 'GiĂ¡ tá»•ng lĂ  báº¯t buá»™c.',
            'total_price.numeric'     => 'GiĂ¡ tá»•ng pháº£i lĂ  sá»‘.',
        ];
    }
}
