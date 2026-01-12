<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // --- KHÔNG TRÙNG TÊN SP TRONG CÙNG LOẠI ---
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')
                    ->where('product_type_id', $this->product_type_id),
            ],

            'product_type_id' => 'required|exists:product_types,product_type_id',
            'product_size_id' => 'nullable|exists:product_sizes,product_size_id',

            'description' => 'required|array',
            'description.*' => 'required|string',

            'image' => 'required|array|min:1',
            'image.*' => 'required|image|max:20048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được quá 255 ký tự.',
            'name.unique' => 'Tên sản phẩm đã tồn tại trong loại sản phẩm này.',

            'product_type_id.required' => 'Vui lòng chọn loại sản phẩm.',
            'product_type_id.exists' => 'Loại sản phẩm không hợp lệ.',

            'description.required' => 'Vui lòng nhập mô tả sản phẩm.',
            'description.*.required' => 'Mô tả không được để trống.',

            'image.required' => 'Vui lòng chọn ít nhất 1 hình ảnh.',
            'image.min' => 'Vui lòng chọn ít nhất 1 hình ảnh.',
            'image.*.image' => 'File tải lên phải là hình ảnh.',
            'image.*.max' => 'Kích thước hình ảnh không được vượt quá 20MB.',
        ];
    }
}
