<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use Illuminate\Validation\Rule;
use App\Models\{Order,
    OrderDetails,
    OrderOffer,
    OrderProviders,
    OrderRepresentative,
    ProductsDontHaveProvider,
    Provider,
    Product,
    Representative,
    Reviews,
    Token};

class OrderController extends Controller
{
    use GeneralTrait,NotificationTrait;
    public function storeOrder(Request $request)
    {
//        dd($request->all());;
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,406);
        }
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:addresses,id',
            'delivery_date_time_id'=>'required|exists:delivery_times,id',
            'note' => 'nullable',
            'products' => 'required|array',
//            'details.*.category_id' => ["required",Rule::exists('categories','id')
//                ->where('level',1)],
            'products.*.id' => 'required|exists:products,id',
            'products.*.category_id' => ["required",Rule::exists('categories','id')
                ->where('level',1)],
            'products.*.sub_category_id' => ["required",Rule::exists('categories','id')
                ->where('level',2)],
            'products.*.qty' => 'required',
        ]);
//        dd($validator->fails());
        if ($validator->fails()) {
//            dd($validator->errors());
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,400);
        }
        $backResult = [];

//        foreach ($request->products as $key => $value) {
//            $checkData = [];
//            $checkData['products_id'] = [];
//            $checkData['category_id'] = $value['category_id'];
//            foreach($value['products'] as $key2 => $value2) {
//                $checkData['products_id'][] = $value2['id'];
//            }
//
////            if (!$this->checkIfOrderCanStore($checkData)){
////                $backResult[] = $value['category_id'];
////            }
//        }
//
////        if (count($backResult) > 0) {
////            return $this->returnData('categories_id', $backResult,'المنتجات التي تم اختيارها غير متوفرة في المتاجر ',404);
////        }




//        foreach($request->products as $key=>$data)
//        {
            $createData['user_id'] = $request->user_id;
            $createData['delivery_date_time_id'] = $request->delivery_date_time_id;
//            $createData['category_id'] = $data['category_id'];
            $createData['address_id'] = $request->address_id;
            $createData['note'] = $request->note;
            $createData['status'] = 'new';
            $create = Order::create($createData);
            foreach ($request['products'] as $key => $value3) {

                $createDetailsData['product_id'] = $value3['id'];
                $createDetailsData['category_id'] = $value3['category_id'];
                $createDetailsData['sub_category_id'] = $value3['sub_category_id'];
                $createDetailsData['qty'] = $value3['qty'];

                $createDetailsData['order_id'] = $create->id;
                $createDetailsData['user_id'] = $request->user_id;
                OrderDetails::create($createDetailsData);
            }
            $this->sendOrder($create->id);
//        }
        return $this->returnData('data', null, 'تم ارسال الطلبات بنجاح', 200);
    }//end fun
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendOrder($id)
    {
        $detailsGroupedBy = OrderDetails::where('order_id',$id)->groupBy('category_id')->get();
        $products = [];
        foreach($detailsGroupedBy as $key => $detail){
            $details = OrderDetails::where('order_id',$id)
                ->where('category_id',$detail->category_id)->pluck('product_id')->toArray();
            $providersWhoSaleCategories = Provider::whereHas('categories',function($query) use($detail){
                $query->where('category_id',$detail->category_id);
            })->get();

            $countAllProducts = count($details);
            foreach($providersWhoSaleCategories as $key => $provider){
                $selectProductsProviderDontHaveIt= ProductsDontHaveProvider::where('provider_id',$provider->id)
                    ->whereIn('product_id',$details)->count();
                $selectProductsProviderHaveIt = $countAllProducts - $selectProductsProviderDontHaveIt;

                $persent = $selectProductsProviderHaveIt / $countAllProducts;
                if($persent >= 0.5){
                    $data=[];
                    $data['order_id'] = $id;
                    $data['provider_id'] = $provider->id;
                    $data['status'] = 'new';
                    $title = 'new order';
                    $body = 'you have new order';
                    $this->sendBasicNotification($title,$body,$id,null,$provider->id,);
                   $products[] = OrderProviders::create($data);
                }
            }
        }


        $order = Order::with('details','address.time')->find($id);
        if (count($products) == 0) {
             $order->delete();
             OrderDetails::where('order_id',$id)->delete();
            return $this->returnError(402,'this product is not available',402);
        }
        return $this->returnData('data',$order,'',200);
    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,406);
        }
//        dd('bjhkn');
        $order['new_orders'] = Order::where('user_id', $request->user_id)->with('category','address.time',
            'accepted_offer.offer_details','accepted_offer.provider')->whereIn('status',['new','offered','accepted',
            'preparing','on_way'])->latest()->get();

        $order['old'] = Order::where('user_id', $request->user_id)->with('category','address.time'
            ,'accepted_offer.offer_details','accepted_offer.provider')
            ->whereIn('status',['rejected','delivered'])->latest()->get();
        return $this->returnData('data',$order,200);
    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,405);
        }
        $order = Order::with('details','category','offers.offer_details','offers.provider','address.time'
            ,'accepted_offer.offer_details','accepted_offer.provider')->find($request->order_id);
        $order['order_representative'] = OrderRepresentative::with('representative')->where('order_id',$request->order_id)
            ->whereNotIn('status',['new','rejected'])->first();
        if (!$order) {
            return $this->returnError(405,'order not found',405);
        }

        return $this->returnData('data',$order,200);
    }//end fun
    /**
     * @param $data
     * @return bool
     */
    private function checkIfOrderCanStore($data)
    {
        $products = [];
            $details = $data['products_id'];
                $providersWhoSaleCategories = Provider::whereHas('categories',function($query) use($data){
                $query->where('category_id',$data['category_id']);
            })->get();
            if (count($providersWhoSaleCategories) == 0) {
                return false;
            }

            $countAllProducts = count($details);
            foreach($providersWhoSaleCategories as $key => $provider){
                $selectProductsProviderDontHaveIt= ProductsDontHaveProvider::where('provider_id',$provider->id)
                    ->whereIn('product_id',$details)->count();
                $selectProductsProviderHaveIt = $countAllProducts - $selectProductsProviderDontHaveIt;

                $persent = $selectProductsProviderHaveIt / $countAllProducts;
                if($persent >= 0.5){
                    $products[] = $data['category_id'];
                }
            }

        if (count($products) == 0) {
            return false;
        }
        return true;
    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function offerDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|exists:order_offers,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,405);
        }

        $offer = OrderOffer::with('offer_details','provider')->find($request->offer_id);
        if (!$offer) {
            return $this->returnError(405,'offer not found',405);
        }
        return $this->returnData('data',$offer,200);
    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOfferStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|exists:order_offers,id',
            'status' => 'required|in:accepted,rejected',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,405);
        }
        $offer = OrderOffer::find($request->offer_id);
        if (!$offer) {
            return $this->returnError(405,'offer not found',405);
        }
        if ($offer->status != 'new') {
            return $this->returnError(403,"offer already {$offer->status}",403);
        }


        $offer->status = $request->status;
        $offer->save();


        $title = '';
        $body = '';
        $order_id = $offer->order_id;
        $provider_id = $offer->provider_id;
        $user_id = null;

        if ($offer->status == 'accepted') {
            $order = Order::find($offer->order_id);
            $order->status = 'accepted';
            $order->total_price = $offer->total_price;
            $order->accepted_offer_id = $request->offer_id;
            $order->provider_id = $offer->provider_id;
            $order->save();
            $title = 'accepted offer';
            $body = 'your offer accepted';
        }else{
            $title = 'rejected offer';
            $body = 'your offer rejected';
        }
        $this->sendBasicNotification($title,$body,$order_id,$user_id,$provider_id,'accept');



        return $this->returnData('data',$offer,200);
    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeRate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
            'user_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'rate' => 'required|numeric|min:1|max:5',
            'text' => 'nullable',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,405);
        }//

        $checkIfRated = Reviews::where($request->only('provider_id','user_id','order_id'))->count();

        if ($checkIfRated) {
            return $this->returnError(403,'you already rated this provider',403);
        }

        Reviews::create($request->all());
        return $this->returnData('data', null, 'تم حفظ التقييم', 200);

    }//enf fun

    public function updateOrderStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:cancel,preparing',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,405);
        }//


       $update = Order::findOrFail($request->order_id)->update($request->only('status'));
        $order = Order::find($request->order_id);
        $representatives_ids = Representative::where([['provider_id',$order->provider_id],['status','<>',0]])
            ->orWhere([['provider_id',null],['status','<>',0]])->pluck('id');

        if ($update)
        {
            $title = 'new notification';
            $body = '';
            $user_id = $order->user_id;
            $provider_id = null;
            if ($request->status == 'preparing'){
                $body = 'your order is preparing';
                // forward order to all provider's representatives
                foreach ($representatives_ids as $id){
                    OrderRepresentative::create([
                        'order_id'          => $request->order_id,
                        'representative_id' => $id,
                        'status'            => 'new',
                    ]);
                     $this->sendBasicNotification($title,$body,$request->order_id,$user_id,null,$order->provider_id,$id);
                }
            }

//            elseif ($request->status == 'on_way')
//                $body = 'your order is on way';
//            elseif ($request->status == 'delivered'){
//                OrderOffer::where('order_id',$request->order_id)
//                    ->where('provider_id',$order->provider_id)->update(['status'=>'ended']);
//                $body = 'your order is delivered';
//            }
            else{
                $body = 'your order is canceled';
//                $user_id =  null;
                $provider_id = null;
                $this->sendBasicNotification($title,$body,$request->order_id,$user_id,$provider_id);
            }

        }

        return $this->returnData('data', null, 'تم تحديث حالة الطلب', 200);
    }//end fun


}//end class
