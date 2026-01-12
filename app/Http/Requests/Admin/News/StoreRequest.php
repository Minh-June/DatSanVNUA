<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'news_type_id' => 'required|exists:news_types,news_type_id',

            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('news', 'title')
                    ->where('news_type_id', $this->news_type_id),
            ],

            'content' => 'required|array|min:1',
            'content.0' => 'required|string',
            'content.*' => 'nullable|string',

            'note' => 'nullable|array',
            'note.*' => 'nullable|string|max:10000',

            'image' => 'nullable|array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'news_type_id.required' => 'Vui lòng chọn loại tin tức.',
            'title.required' => 'Vui lòng nhập tiêu đề bài đăng.',
            'title.unique' => 'Tiêu đề đã tồn tại trong loại tin tức này.',
            'content.0.required' => 'Nội dung đầu tiên không được để trống.',
            'image.*.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.*.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'image.*.max' => 'Kích thước ảnh tối đa là 5MB.',
        ];
    }
}
