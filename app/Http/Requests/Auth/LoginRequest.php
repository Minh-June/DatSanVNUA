<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'TĂªn ngÆ°á»i dĂ¹ng lĂ  báº¯t buá»™c.',
            'username.string' => 'TĂªn ngÆ°á»i dĂ¹ng pháº£i lĂ  má»™t chuá»—i.',
            'username.max' => 'TĂªn ngÆ°á»i dĂ¹ng khĂ´ng Ä‘Æ°á»£c vÆ°á»£t quĂ¡ 255 kĂ½ tá»±.',
            'password.required' => 'Máº­t kháº©u lĂ  báº¯t buá»™c.',
            'password.string' => 'Máº­t kháº©u pháº£i lĂ  má»™t chuá»—i.',
            'password.min' => 'Máº­t kháº©u pháº£i cĂ³ Ă­t nháº¥t 6 kĂ½ tá»±.',
        ];
    }

    /**
     * Xá»­ lĂ½ xĂ¡c thá»±c trong request.
     */
    public function authenticate(): void
    {
        if (!Auth::attempt([
            'username' => $this->input('username'),
            'password' => $this->input('password'),
        ])) {
            throw ValidationException::withMessages([
                'login_failed' => 'TĂªn Ä‘Äƒng nháº­p vĂ  máº­t kháº©u khĂ´ng Ä‘Ăºng.',
            ]);
        }
    }
}
