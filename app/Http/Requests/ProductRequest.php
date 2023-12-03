<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_name' => 'required | max:255',
            'company_id' => 'required',
            'price' => 'required | integer',
            'stock' => 'required | integer',
            'comment' => 'nullable | max:1000', 
            'img_path' => 'nullable|image|max:2048',
        ];
    }

    public function attributes() {
        return [
            'product_name' => '商品名',
            'company_id' => 'メーカー名',
            'price' => '価格',
            'stock' => '在庫数',
            'comment' => 'コメント', 
            'img_path' => '商品画像',
        ];
    }

    public function messages() {
        return [
            'product_name.required' => ':attributeは必須項目です。',
            'company_id.required' => ':attributeは必須項目です。',
            'price.required' => ':attributeは必須項目です。',
            'price.integer' => ':attributeは:integerで入力して下さい。',
            'stock.required' => ':attributeは必須項目です。',
            'stock.integer' => ':attributeは:integerで入力して下さい。',
            'comment.max' => ':attributeは:max字以内で入力して下さい。', 
            'img_path.max' => ':attributeは:maxMB以内のものを挿入して下さい。',
        ];      
    }
}
