<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Models\MyList;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductsDontHaveProvider;
use App\Models\ProductSuggestion;
use App\Models\Provider;
use App\Models\ProviderCategories;
use App\Models\Reviews;
use App\Traits\GeneralTrait;
use App\Traits\PhotoTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    use GeneralTrait, PhotoTrait;

    public function statistics(request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id'  => 'required|exists:providers,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $data['client_month'] = Order::where('provider_id', $request->provider_id)
            ->whereMonth('created_at', date('m'))->count();

        $data['orders'] = Order::whereMonth('created_at', date('m'))->where("provider_id", $request->provider_id)->count();

        $data['client_year'] = Order::where('provider_id', $request->provider_id)
            ->whereYear('created_at', date('Y'))->count();

        $data['miss_orders'] = Order::whereMonth('created_at', date('m'))->where("provider_id", $request->provider_id)->doesntHave('orderOffers')->count();

        return $this->returnData('data', $data);
    }

    public function reviews(request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id'  => 'required|exists:providers,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $data = Reviews::where('provider_id', $request->provider_id)->with('user')->get();
        return $this->returnData('data', $data, 'get data successfully');
    }


    public function suggest_product(request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id'  => 'required|exists:providers,id',
            'image'  => 'required|image|mimes:jpeg,jpg,png,gif:',
            'title' => 'required',
            'main_category_id' => ["required", Rule::exists('categories', 'id')
                ->where('level', 1)],
            'specifications' => 'nullable'
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $data = $request->all();
        $file_name = $this->saveImage($request->image, 'assets/uploads/suggests');
        $data['image'] = 'assets/uploads/suggests/' . $file_name;
        ProductSuggestion::create($data);
        return $this->returnSuccessMessage('تم تقديم الاقتراح بنجاح');
    }

    //    public function control_products(request $request){
    //        $validator = Validator::make($request->all(), [
    //            'provider_id'     => 'required|exists:providers,id',
    //            'category_id'     => 'nullable|exists:categories,id',
    //            'sub_category_id' => 'nullable|exists:categories,id',
    //        ]);
    //        if ($validator->fails()) {
    //            $code = $this->returnCodeAccordingToInput($validator);
    //            return $this->returnValidationError($code, $validator);
    //        }
    //        $providerCategoriesIds = ProviderCategories::where('provider_id',$request->provider_id)->pluck('category_id')->toArray();
    //        $dontHaveIds = ProductsDontHaveProvider::where('provider_id',$request->provider_id)->pluck('product_id')->toArray();
    //        $query = Product::whereIn('category_id', $providerCategoriesIds);
    ////        dd($query->get());
    //        if($request->has('category_id')){
    //            $query->where('category_id', $request->category_id);
    //        }
    //
    //        if($request->has('sub_category_id')){
    //            $query->where('sub_category_id', $request->sub_category_id);
    //        }
    //
    //        $data = $query->get();
    ////        dd($data);
    //
    //
    ////        foreach ($data as $product){
    ////            if(in_array($product->id,$dontHaveIds)){
    ////                $product->have_or_not = 'not';
    ////            }
    ////            else{
    ////                $product->have_or_not = 'have';
    ////            }
    ////        }
    //
    //        return $this->returnData('data',$data,'get data successfully');
    //    }

    //    public function control_products(request $request){
    //        $validator = Validator::make($request->all(), [
    //            'provider_id'     => 'required|exists:providers,id',
    //            'category_id'     => 'nullable|exists:categories,id',
    //            'sub_category_id' => 'nullable|exists:categories,id',
    //        ]);
    //        if ($validator->fails()) {
    //            $code = $this->returnCodeAccordingToInput($validator);
    //            return $this->returnValidationError($code, $validator);
    //        }
    //        $providerCategoriesIds = ProviderCategories::where('provider_id',$request->provider_id)->pluck('category_id')->toArray();
    //        $dontHaveIds = ProductsDontHaveProvider::where('provider_id',$request->provider_id)->pluck('product_id')->toArray();
    //        $data = Product::whereIn('category_id',$providerCategoriesIds)->get();
    ////        dd($providerCategoriesIds,$data);
    //        if($request->has('category_id')){
    //            $data = $data->where('category_id',$request->category_id)->values();
    //        }
    //        if($request->has('sub_category_id')){
    //            $data = $data->where('sub_category_id',$request->sub_category_id)->values();
    //        }
    //        foreach ($data as $product){
    //            if(in_array($product->id,$dontHaveIds)){
    //                $product->have_or_not = 'not';
    //            }
    //            else{
    //                $product->have_or_not = 'have';
    //            }
    //        }
    //        return $this->returnData('data',$data,'get data successfully');
    //    }




    //     public function control_products(request $request){
    //         $validator = Validator::make($request->all(), [
    //             'provider_id'     => 'required|exists:providers,id',
    //             'category_id'     => 'nullable|exists:categories,id',
    //             'sub_category_id' => 'nullable|exists:categories,id',
    //         ]);
    //         if ($validator->fails()) {
    //             $code = $this->returnCodeAccordingToInput($validator);
    //             return $this->returnValidationError($code, $validator);
    //         }
    //         //the categories that the provider has (assigned to the provider in register)
    // //        $providerCategoriesIds = ProviderCategories::where('provider_id',$request->provider_id)
    // //            ->where(function ($query) use ($request) {
    // //                if ($request->has('category_id')) {
    // //                    $query->where('category_id', $request->category_id);
    // //                }
    // //                if ($request->has('sub_category_id')) {
    // //                    $query->where('sub_category_id', $request->sub_category_id);
    // //                }
    // //            })->pluck('category_id')->toArray();


    //         if($request->provider_id ){

    //         }
    //         $providerCategoriesIds = ProviderCategories::where('provider_id',$request->provider_id)->where('category_id',$request->category_id)->pluck('category_id')->toArray();;

    //         $providerId = $request->provider_id;
    //         $data = Product::all();
    // //        $data = Product::whereIn('category_id', $providerCategoriesIds)->get();
    //         $data->each(function ($product) use ($providerId) {
    //             $product->is_list = $product->checkIsList($providerId);
    //         });

    //         //the products that the provider removed so no order with this product will reach him
    //         // (the default for this this is that all the products will be assigned to the provider untill it is removed by him then a column in the table products_dont_have_providers will be added )
    // //        $dontHaveIds = ProductsDontHaveProvider::where('provider_id',$request->provider_id)->pluck('product_id')->toArray();
    //         //get all the products of the provider




    // //        dd($providerCategoriesIds,$data);
    // //        foreach ($data as $product){
    // //            $product->setAttribute('is_list', true);
    // //        }

    // //        if($request->has('category_id')){
    // //            $data = $data->where('category_id',$request->category_id)->values();
    // //        }
    // //        if($request->has('sub_category_id')){
    // //            $data = $data->where('sub_category_id',$request->sub_category_id)->values();
    // //        }
    // //        foreach ($data as $product){
    // //            if(in_array($product->id,$dontHaveIds)){
    // //                $product->have_or_not = 'not';
    // //            }
    // //            else{
    // //                $product->have_or_not = 'have';
    // //            }
    // //        }
    //         return $this->returnData('data',$data,'get data successfully');
    //     }

    public function control_products(Request $request)
    {
        // if (!auth()->check()) {
        //     return $this->returnError('E401', 'Unauthorized: please login first', 401);
        // }

        $validator = Validator::make($request->all(), [
            'provider_id'     => 'required|exists:providers,id',
            'category_id'     => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $providerCategoriesQuery = ProviderCategories::where('provider_id', $request->provider_id);

        if ($request->filled('category_id')) {
            $providerCategoriesQuery->where('category_id', $request->category_id);
        }

        // if ($request->filled('sub_category_id')) {
        //     $providerCategoriesQuery->where('sub_category_id', $request->sub_category_id);
        // }

        $providerCategoriesIds = $providerCategoriesQuery->pluck('category_id')->toArray();

        $query = Product::query();

        if (!empty($providerCategoriesIds)) {
            $query->whereIn('category_id', $providerCategoriesIds);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        $data = $query->orderBy('id', 'desc')->get();

        $providerId = $request->provider_id;
        $data->each(function ($product) use ($providerId) {
            $product->is_list = $product->checkIsList($providerId);
        });

        return $this->returnData('data', $data, 'Data retrieved successfully');
    }

    public function edit_my_products(request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id'   => 'required|exists:providers,id',
            'products_id'   => 'required|array',
            'is_list'        => 'required|array',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $provider = Provider::find($request->provider_id);
        //        $provider->hiddenProducts()->delete();
        foreach ($request->is_list as $key => $is_list) {
            $data['provider_id'] = $request->provider_id;
            $data['product_id']  = $request->products_id[$key];
            $new = ProductsDontHaveProvider::where([
                ['product_id', $data['product_id']],
                ['provider_id', $request->provider_id]
            ])->first();
            if ($is_list == true) {
                if ($new) {
                    $new->delete();
                }
            } else
                ProductsDontHaveProvider::updateOrCreate($data);
        }
        //        dd($data);
        return $this->returnSuccessMessage('تم التحديث بنجاح');
    }



    //    public function edit_my_products(request $request){
    //        $validator = Validator::make($request->all(), [
    //            'provider_id'     => 'required|exists:providers,id',
    //            'products_id'   => 'required|array',
    //        ]);
    //        if ($validator->fails()) {
    //            $code = $this->returnCodeAccordingToInput($validator);
    //            return $this->returnValidationError($code, $validator);
    //        }
    //
    //        $provider = Provider::find($request->provider_id);
    ////        $provider->hiddenProducts()->delete();
    //        foreach ($request->products_id as $key => $value){
    //            $data['provider_id'] = $request->provider_id;
    //            $data['product_id']  = $value;
    //            dd($data);
    //            $new = ProductsDontHaveProvider::where([
    //                ['product_id',$value],
    //                ['provider_id',$request->provider_id]
    //            ])->first();
    //            if($new){
    //                $new->delete();
    //                MyList::create([]);
    //            }
    //            else
    //                ProductsDontHaveProvider::updateOrCreate($data);
    //        }
    ////        dd($data);
    //        return $this->returnSuccessMessage('تم التحديث بنجاح');
    //    }
}
