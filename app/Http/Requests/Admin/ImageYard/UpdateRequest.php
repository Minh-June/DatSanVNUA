<?php

namespace App\Http\Requests\Admin\ImageYard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Thay Ä‘á»•i náº¿u cáº§n
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Vui lĂ²ng chá»n hĂ¬nh áº£nh.',
            'image.image' => 'Tá»‡p táº£i lĂªn pháº£i lĂ  hĂ¬nh áº£nh.',
            'image.mimes' => 'HĂ¬nh áº£nh pháº£i cĂ³ Ä‘á»‹nh dáº¡ng: jpeg, png, jpg.',
            'image.max' => 'KĂ­ch thÆ°á»›c hĂ¬nh áº£nh tá»‘i Ä‘a lĂ  2MB.',
        ];
    }

}
