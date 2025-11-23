<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategorySubCategories;
use App\Models\Product;
use App\Models\Slider;
use App\Models\OrderOfferDetails;
use App\Models\OrderOffer;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomePageController extends Controller
{
    use GeneralTrait;

    public function search(request $request){
        $data['categories'] = Category::where(function ($query_all)use ($request){
                $query_all->where('title_ar','like','%'.$request->search_word.'%')
                    ->orwhere('title_en','like','%'.$request->search_word.'%');
            })
            ->where('level',2)
        ->get();


        $data['products'] = Product::where(function ($query_all)use ($request){
                $query_all->where('title_ar','like','%'.$request->search_word.'%')
                    ->orwhere('title_en','like','%'.$request->search_word.'%');
            })->with('mainCategory','subCategory')
            ->get();

        return $this->returnData('data', $data, 'get data successfully');
    }

    public function sliders(){
        $sliders = Slider::active()->get();
        return $this->returnData('data', $sliders, 'get data successfully');
    }

    public function main_categories(){
        $main_categories = Category::mainCategory()->get();
        return $this->returnData('data', $main_categories, 'get data successfully');
    }

    public function latest_products(){
        $products = Product::latest()->with('mainCategory','subCategory')->take(15)->get();
        return $this->returnData('data', $products, 'get data successfully');
    }//end fun

    public function mostSales(Request $request){



        $offerDetails = OrderOfferDetails::wherehas('order_offer',function($query){
            $query->where('status','accepted');
        })->selectRaw('*, sum(qty) as total_sum')
            ->groupBy('product_id')
            ->take(15)
            ->get()->toArray();
        uasort($offerDetails, function($a, $b) {
            return $b['total_sum'] <=> $a['total_sum'];
        });
        $offersProducts = array_values($offerDetails);

        $products = array_column($offersProducts, 'product');

        return $this->returnData('data', $products, 'get data successfully');
    }

}//end class
