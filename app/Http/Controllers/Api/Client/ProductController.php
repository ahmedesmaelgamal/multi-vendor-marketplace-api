<?php

namespace App\Http\Controllers\Api\Client;

use     App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\MyList;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use GeneralTrait;
    public function product_details(request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $product = Product::where('id',$request->id)->with('images','mainCategory','subCategory')->first();
        return $this->returnData('data', $product, 'get data successfully');
    }

    public function all_products(){
        $products = Product::with('mainCategory','subCategory')->get();
        return $this->returnData('data', $products, 'get data successfully');
    }//end fun
    /**
     * @param Request $request
     * @return mixed
     */
    public function myList(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,406);
        }
        $products = Product::wherehas('list',function($query)use($request)
        {
            $query->where('user_id',$request->user_id);
        })->with('images','mainCategory','subCategory')->get();
        return $this->returnData('data', $products, 'get data successfully');
    }//end fun

//    public function storeToList(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'user_id' => 'required|exists:users,id',
//            'product_id' => 'required|exists:products,id',
//        ]);
//        if ($validator->fails()) {
//            $code = $this->returnCodeAccordingToInput($validator);
//            return $this->returnValidationError($code, $validator,400);
//        }
//        $message = '';
//
//        $list = MyList::where($request->only('user_id','product_id'));
//
//        if ($list->count())
//        {
//            $message = 'product deleted successfully';
//            $list->delete();
//        }
//        else
//        {
//            MyList::create($request->only('user_id','product_id'));
//            $message = 'product added to list';
//        }
//        return $this->returnSuccessMessage($message);
//    }//end fun



    public function storeToList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,400);
        }
        $message = '';

        $list = MyList::where($request->only('user_id','product_id'));

        if ($list->count())
        {
            $message = 'product deleted successfully';
            $list->delete();
        }
        else
        {
            MyList::create($request->only('user_id','product_id'));
            $message = 'product added to list';
        }
        return $this->returnSuccessMessage($message);
    }//end fun

}//end class
