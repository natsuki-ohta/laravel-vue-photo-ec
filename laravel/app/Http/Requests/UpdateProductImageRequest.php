<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'image' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png',
                'max:5120', 
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => '画像ファイルを選択してください。',
            'image.mimes' => '画像はjpgまたはpng形式のみアップロード可能です。',
            'image.max' => '画像サイズは5MB以内にしてください。',
        ];
    }
}