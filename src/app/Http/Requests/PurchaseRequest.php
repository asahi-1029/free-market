<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method' => ['required','exists:payment_methods,id'],
            'address' => ['required'],
            'postal_code' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'payment_method.exists' => '正しい支払い方法を選択してください',
            'address.required' => '配送先を登録してください',
            'postal_code.required' => '配送先を登録してください',
        ];
    }
}
