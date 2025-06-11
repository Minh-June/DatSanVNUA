<?php

namespace App\Http\Requests\Admin\ImageYard;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'yard_id' => 'required|exists:yards,yard_id', // sá»­a láº¡i náº¿u trÆ°á»›c Ä‘Ă³ ghi sai
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'yard_id.required' => 'Vui lĂ²ng chá»n sĂ¢n thá»ƒ thao.',
            'yard_id.exists' => 'SĂ¢n thá»ƒ thao khĂ´ng há»£p lá»‡.',
            'image.required' => 'HĂ¬nh áº£nh sĂ¢n khĂ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng.',
            'image.image' => 'Tá»‡p táº£i lĂªn pháº£i lĂ  hĂ¬nh áº£nh.',
            'image.mimes' => 'HĂ¬nh áº£nh pháº£i cĂ³ Ä‘á»‹nh dáº¡ng jpeg, png, jpg.',
            'image.max' => 'KĂ­ch thÆ°á»›c hĂ¬nh áº£nh khĂ´ng Ä‘Æ°á»£c vÆ°á»£t quĂ¡ 2MB.',
        ];
    }


}
