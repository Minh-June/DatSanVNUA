<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phÄ‚Â©p thÃ¡Â»Â±c hiÃ¡Â»â€¡n request
    }

    public function rules(): array
    {
        return [
            'matkhau_hientai'    => 'required',
            'matkhau_moi'        => 'required|min:6',
            'xacnhan_matkhau'    => 'required|same:matkhau_moi',
        ];
    }

    public function messages(): array
    {
        return [
            'matkhau_hientai.required'  => 'Vui lÄ‚Â²ng nhÃ¡ÂºÂ­p mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u hiÃ¡Â»â€¡n tÃ¡ÂºÂ¡i.',
            'matkhau_moi.required'      => 'Vui lÄ‚Â²ng nhÃ¡ÂºÂ­p mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u mÃ¡Â»â€ºi.',
            'matkhau_moi.min'           => 'MÃ¡ÂºÂ­t khÃ¡ÂºÂ©u mÃ¡Â»â€ºi phÃ¡ÂºÂ£i cÄ‚Â³ Ä‚Â­t nhÃ¡ÂºÂ¥t 6 kÄ‚Â½ tÃ¡Â»Â±.',
            'xacnhan_matkhau.required'  => 'Vui lÄ‚Â²ng xÄ‚Â¡c nhÃ¡ÂºÂ­n mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u mÃ¡Â»â€ºi.',
            'xacnhan_matkhau.same'      => 'MÃ¡ÂºÂ­t khÃ¡ÂºÂ©u xÄ‚Â¡c nhÃ¡ÂºÂ­n khÄ‚Â´ng khÃ¡Â»â€ºp.',
        ];
    }
}
