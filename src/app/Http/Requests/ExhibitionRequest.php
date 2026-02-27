<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => ['required'],
            'description' => ['required','max:255'],
            'image' => ['required','image','mimes:jpeg,png'],
            'category_ids' => ['required','array'],
            'category_ids.*' => ['exists:categories,id'],
            'condition' => ['required'],
            'price' => ['required','numeric','min:0'],
            'brand' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品を入力してください。',
            'description.required' => '商品説明を入力してください。',
            'description.max' => '商品説明は255文字以内で入力。',
            'image.required' => '商品画像をアップロードしてください。',
            'image.image' => '画像ファイルをアップロードしてください。',
            'image.mimes' => '画像はjpegまたはpng形式で入力してください。',
            'category_ids.required' => 'カテゴリーを1つ以上選択してください。',
            'category_ids.array' => 'カテゴリーの形式が不正です。',
            'category_ids.*.exists' => '存在しないカテゴリーが選択されています。',
            'condition.required' => '商品の状態を選択してください。',
            'price.required' => '商品価格を入力してください。',
            'price.numeric' => '商品価格は数値で入力してください。',
            'price.min' => '商品価格は0円以上で入力してください。',
        ];
    }
}
