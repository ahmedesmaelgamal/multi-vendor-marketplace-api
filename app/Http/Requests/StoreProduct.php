<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
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
            'title_ar'        => 'required|max:255',
            'title_en'        => 'required|max:255',
            'category_id'     => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:categories,id',
            'details_at'      => 'required',
            'details_en'      => 'required',
            'main_image'      => 'required_without:id|mimes:jpeg,jpg,png,gif,webp',
        ];
    }

    public function messages()
    {
        return [
            'main_image.required_without' => 'يرجي رفع صورة المنتج',
            'main_image.mimes'            => 'صيفة الصورة غير مقبولة',
            'title_ar.required'           => 'يرجي ادخال عنوان المنتج باللغة العربية',
            'title_en.required'           => 'يرجي ادخال عنوان المنتج باللغة الانجليزية',
            'category_id.required'        => 'يرجي اختيار التصنيف الرئيسي للمنتج',
            'sub_category_id.required'    => 'يرجي اختيار التصنيف الفرعي للمنتج',
            'details_at.required'         => 'يرجي كتابة وصف المنتج باللغة العربية',
            'details_en.required'         => 'يرجي كتابة وصف المنتج باللغة الانجليزية',
        ];
    }
}
