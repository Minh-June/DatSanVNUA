<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'images.*.image'      => 'File tÃ¡ÂºÂ£i lÄ‚Âªn phÃ¡ÂºÂ£i lÄ‚Â  hÄ‚Â¬nh Ã¡ÂºÂ£nh.',
            'images.*.mimes'      => 'HÄ‚Â¬nh Ã¡ÂºÂ£nh phÃ¡ÂºÂ£i cÄ‚Â³ Ã„â€˜Ã¡Â»â€¹nh dÃ¡ÂºÂ¡ng jpeg, jpg hoÃ¡ÂºÂ·c png.',
            'images.*.max'        => 'HÄ‚Â¬nh Ã¡ÂºÂ£nh khÄ‚Â´ng Ã„â€˜Ã†Â°Ã¡Â»Â£c vÃ†Â°Ã¡Â»Â£t quÄ‚Â¡ 2MB.',
        ];
    }
}
