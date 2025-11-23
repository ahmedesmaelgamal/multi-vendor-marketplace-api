<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategorySubCategories;
use App\Models\Product;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use GeneralTrait;

    public function sub_categories(request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $main_category = Category::where([['id',$request->category_id],['level',1]])->first();
        if(!$main_category)
            return $this->returnError('E405','يرجي اختيار قسم رئيسي',400);
        else{
            $ids = $main_category->subCategory->pluck('sub_category_id');
            $data = Category::whereIn('id',$ids)->get();
            return $this->returnData('data', $data, 'get data successfully');
        }
    }

    public function search(request $request){
        $validator = Validator::make($request->all(), [
            'category_id'     => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'search_word'     => 'nullable',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $data = Product::latest();
        if($request->has('search_word')){
            $data->where(function($query)use($request)
            {
                $query->where('title_ar','like','%'.$request->search_word.'%')
                    ->orWhere('title_en','like','%'.$request->search_word.'%');
            });
        }
        if($request->has('category_id')){
             $data->where('category_id',$request->category_id);
        }
        if($request->has('sub_category_id')){
            $data->where('sub_category_id',$request->sub_category_id);
        }
        return $this->returnData('data', $data->with('mainCategory','subCategory')->get(), 'get data successfully');
    }//end fun
}//end class
