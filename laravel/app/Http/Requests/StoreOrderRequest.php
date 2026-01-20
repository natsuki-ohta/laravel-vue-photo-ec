<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:products,id'],
            'items.*.price' => ['required', 'integer', 'min:0'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'last_name' => ['required', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name_kana' => ['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:50'],
            'first_name_kana' => ['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:50'],
            'postal' => ['required','regex:/^\d{3}-?\d{4}$/'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required','regex:/^0\d{1,4}-?\d{1,4}-?\d{4}$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => '商品が選択されていません',
            'items.min' => '商品が選択されていません',
            'last_name.required' => '姓を入力してください',
            'first_name.required' => '名を入力してください',
            'last_name_kana.required' => '姓（フリガナ）を入力してください',
            'last_name_kana.regex' => 'フリガナはカタカナで入力してください',
            'first_name_kana.required' => '名（フリガナ）を入力してください',
            'first_name_kana.regex' => 'フリガナはカタカナで入力してください',
            'postal.required' => '郵便番号を入力してください',
            'postal.regex' => '郵便番号の形式が正しくありません',
            'address.required' => '住所を入力してください',
            'phone.required' => '電話番号を入力してください',
            'phone.regex' => '電話番号の形式が正しくありません',
        ];
    }
}
