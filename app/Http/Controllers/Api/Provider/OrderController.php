<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderOffer;
use App\Models\OrderOfferDetails;
use App\Models\OrderProviders;
use App\Models\OrderRepresentative;
use App\Models\PinOrder;
use App\Traits\GeneralTrait;
use App\Traits\NotificationTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    use NotificationTrait;
    use GeneralTrait;
    public function orders(request $request){
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $pin_orders_ids = PinOrder::where('provider_id',$request->provider_id)->pluck('order_id')->toArray();
        $hidden_orders_ids = OrderProviders::where([['provider_id',$request->provider_id],['status','hide']])->pluck('order_id')->toArray();

        //الطلبات تكون جديدة لما الحالة جديدة ويكون العرض بتاعي حالته جديد
        $order['news']  = Order::where('status','new')
            ->whereHas('orderProviders', function ($query) use ($request){
                return $query->where([['status','new'],['provider_id',$request->provider_id]]);
            })->with('user','address')->latest()->get();
        foreach ($order['news'] as $item){
            $item['offer_status_code'] = 200;
            $item['offer_status']      = "no offer submitted";
            $item['day']  = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y / m / d');
            $item['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('g:i A');
            if(in_array($item->id, $pin_orders_ids))
                $item['is_pin'] = 1;
            else
                $item['is_pin'] = 0;
        }
        $order['news'] = array_values($order['news']->sortByDesc('is_pin')->toArray());

        //الطلبات تكون حالية لما الحالة جديدة او مقبولة او يتم التحضير او يتم توصيلها ويكون العرض بتاعي حالته تم العرض
//        $order['current'] = Order::whereIn('status',['new','accepted','preparing','on_way'])
//            ->whereHas('orderProviders', function ($query) use ($request){
//                return $query->where([['status','offered'],['provider_id',$request->provider_id]]);
//            })->whereHas('orderOffers', function ($query) use ($request){
//                $query->whereIn('status',['new','accepted'])->where('provider_id',$request->provider_id);
//            })->with('user','address')->latest()->get();


        $order['current'] = Order::where(function($query_current) use ($request)
        {
            $query_current->whereIn('status',['accepted','preparing','on_way'])
                ->Where('provider_id',$request->provider_id);
        })->orWhereHas('orderOffers', function ($query) use ($request){
            $query->whereIn('status',['new','accepted'])->where('provider_id',$request->provider_id);
        })->with('user','address')->latest()->get();

        foreach ($order['current'] as $item){
            $item['day']  = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y / m / d');
            $item['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('g:i A');
            $status = OrderOffer::where('order_id',$item->id)->first()->status;
            $order_status = $item->status;
            // لو العرض لسه متقبلش والطلب لسه جديد
            if($status == 'new' && $order_status == 'offered')
            {
                $item['offer_status_code'] = 201;
                $item['offer_status'] = 'waiting for the customer response';
            }
            // لو العرض اتقبل
            elseif ($status == 'accepted'  && $order_status == 'accepted'){
                $item['offer_status_code'] = 202;
                $item['offer_status'] = 'offer accepted';
            }
            elseif($order_status == 'preparing'){
                $item['offer_status_code'] = 203;
                $item['offer_status'] = 'Preparing';
            }
            elseif($order_status == 'on_way'){
                $item['offer_status_code'] = 204;
                $item['offer_status'] = 'on the way';
            }

            if(in_array($item->id, $pin_orders_ids))
                $item['is_pin'] = 1;
            else
                $item['is_pin'] = 0;
        }
        $order['current'] = array_values($order['current']->sortByDesc('is_pin')->toArray());


        //الطلبات تكون سابقة لما الحالة تم توصيلها ومقدم عرض عليها واتقبل ** او لو الحالة جديدة وانا مقدم عرض واترفض
        $order['previous'] = Order::whereIn('status',['new','delivered'])->whereNotIn('id',$hidden_orders_ids)
            ->whereHas('orderOffers', function ($query) use ($request){
                return $query->whereIn('status',['rejected','accepted'])->where('provider_id',$request->provider_id);
            })
            ->with('user','address')->latest()->get();
        // dd($order['previous']);
        foreach ($order['previous'] as $item){
            $item['day']  = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y / m / d');
            $item['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('g:i A');
            $status = OrderOffer::where('order_id',$item->id)->first()->status;
            if($status == 'rejected'){
                $item['offer_status_code'] = 205;
                $item['offer_status'] = 'your offer has been rejected';
            }else{
                $item['offer_status_code'] = 206;
                $item['offer_status'] = 'Delivered';
            }

            if(in_array($item->id, $pin_orders_ids))
                $item['is_pin'] = 1;
            else
                $item['is_pin'] = 0;
        }
        $order['previous'] = array_values($order['previous']->sortByDesc('is_pin')->toArray());
        
        
        
        
        //الطلبات تكون سابقة لما الحالة تم توصيلها ومقدم عرض عليها واتقبل ** او لو الحالة جديدة وانا مقدم عرض واترفض
        // $order['getRejectedFromAllOrders'] = Order::whereIn('status',['new','delivered'])->whereNotIn('id',$hidden_orders_ids)
        //     ->whereHas('orderOffers', function ($query) use ($request){
        //         return $query->whereIn('status',['rejected','accepted'])->where('provider_id',$request->provider_id);
        //     })
        //     ->with('user','address')->latest()->get();
        // // dd($order['previous']);
        // foreach ($order['previous'] as $item){
        //     $item['day']  = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y / m / d');
        //     $item['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('g:i A');
        //     $status = OrderOffer::where('order_id',$item->id)->first()->status;
        //     if($status == 'rejected'){
        //         $item['offer_status_code'] = 205;
        //         $item['offer_status'] = 'your offer has been rejected';
        //     }else{
        //         $item['offer_status_code'] = 206;
        //         $item['offer_status'] = 'Delivered';
        //     }

        //     if(in_array($item->id, $pin_orders_ids))
        //         $item['is_pin'] = 1;
        //     else
        //         $item['is_pin'] = 0;
        // }
        // $order['previous'] = array_values($order['previous']->sortByDesc('is_pin')->toArray());

        return $this->returnData('data',$order);

    }

    public function pin_order(request $request){
        $validator =Validator::make($request->all(),[
            'provider_id' => 'required|exists:providers,id',
            'order_id'    => 'required|exists:orders,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $count = PinOrder::where([['provider_id',$request->provider_id],['order_id',$request->order_id]])->count();
        if($count == 0){
            $inputs = $request->all();
            PinOrder::create($inputs);
            return $this->returnSuccessMessage('Added To Pinned Orders successfully','200');
        }else{
            PinOrder::where([['provider_id',$request->provider_id],['order_id',$request->order_id]])->delete();
            return $this->returnSuccessMessage('Removed From Pinned Orders successfully','200');
        }
    }

    public function order_details(request $request){
        $validator =Validator::make($request->all(),[
            'order_id'     => 'required|exists:orders,id',
            'provider_id'  => 'required|exists:providers,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $order = Order::with(['address','user','details.product'=> function ($query) {
            $query->select('id','main_image','title_ar','title_en');
        }])->find($request->order_id);
        $rev = OrderRepresentative::where('order_id',$order->id)->whereNotIn('status',['new','rejected'])->first();
        if($rev){
            $order['representative'] = $rev->representative;
        }else{
            $order['representative'] = null;
        }

        $order['is_pin']  = 0;

        $pin = PinOrder::where([['provider_id',$request->provider_id],['order_id',$request->order_id]])->first();
        if($pin)
            $order['is_pin']  = 1;

        $order['day']  = Carbon::createFromFormat('Y-m-d H:i:s', $order['created_at'])->format('Y / m / d');
        $order['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order['created_at'])->format('g:i A');
        $offer = OrderOffer::where('order_id',$order->id)->with('offer_details')->first();

        if ($offer) {
            $status = $offer->status;
        }else{
            $status = 'no_offer';
        }
        $order_status = $order->status;


        // لو العرض لسه متقبلش والطلب لسه جديد
        if($status == 'new' && $order_status == 'offered')
        {
            $order['offer_status_code'] = 201;
            $order['offer_status'] = 'waiting for the customer response';
        }
        // لو العرض اتقبل
        elseif ($status == 'accepted'  && $order_status == 'accepted'){
            $order['offer_status_code'] = 202;
            $order['offer_status'] = 'offer accepted';
        }elseif($order_status == 'preparing'){
            $order['offer_status_code'] = 203;
            $order['offer_status'] = 'Preparing';
        }elseif($order_status == 'on_way'){
            $order['offer_status_code'] = 204;
            $order['offer_status'] = 'on the way';
        }elseif($status == 'rejected'){
            $order['offer_status_code'] = 205;
            $order['offer_status'] = 'your offer has been rejected';
        }elseif($order_status == 'delivered'){
            $order['offer_status_code'] = 206;
            $order['offer_status'] = 'Delivered';
        }elseif($status == 'no_offer'){
            $order['offer_status_code'] = 209;
            $order['offer_status'] = 'you don`t have any offer for this order';
        }else{
            $order['offer_status_code'] = 210;
            $order['offer_status'] = '';
        }

        $data['order'] = $order;
        $data['offer'] = $offer;



        return $this->returnData('data',$data);
    }

    public function hide_order(request $request){
        $validator =Validator::make($request->all(),[
            'provider_id' => 'required|exists:providers,id',
            'order_id'    => 'required|exists:orders,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $order = OrderProviders::where([['provider_id',$request->provider_id],['order_id',$request->order_id]])->first();
        if($order->status == 'new'){
            $order->update([
                'status' => 'hide'
            ]);
            return $this->returnSuccessMessage('تم الاخفاء بنجاح','200');
        }else if($order->status == 'hide'){
            $order->update([
                'status' => 'new'
            ]);
            return $this->returnSuccessMessage('تم الاظهار بنجاح','200');
        }
        else{
            return $this->returnSuccessMessage('لا يمكن تعديل حالة الطلب','200');
        }
    }

    public function add_offer(request $request){

        $validator = Validator::make($request->all(),[
            'provider_id' => 'required|exists:providers,id',
            'order_id'    => 'required|exists:orders,id',
//            'delivery_date_time' => 'required',
            'note'          => 'nullable',
            'total_price'   => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'offer_details' => 'required|array',
            'offer_details.*.product_id'    => 'required|exists:products,id',
            'offer_details.*.qty'           => 'required|integer|min:1',
            'offer_details.*.type'          => 'required|in:price,less,other',
            'offer_details.*.price'         => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'offer_details.*.total_price'   => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'offer_details.*.available_qty' => 'required|integer|min:0',
            'offer_details.*.other_product_id' =>  'nullable|exists:products,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $order = Order::find($request->order_id);
        $title = 'new offer';
        $body = 'you have new offer';
        $user_id = $order->user_id;
        $provider_id = null;


        $order_provider = OrderProviders::where([['order_id',$request->order_id],['provider_id',$request->provider_id]])->first();


        if($order->status != 'new')
            return $this->returnError('','يجب ان تكون حالة الطلب جديد لتقوم بتقديم عرض','405');

        if ($order->details->count() > count($request->offer_details))
            return $this->returnError('','يجب تقديم عروض لجميع منتجات الطلب','406');

        if($order_provider->status != 'new')
            return $this->returnError('','لقد قدمت عرض من قبل علي هذا الطلب او تم اخفاءه','405');


        $order_offer = OrderOffer::create([
            'order_id'           => $request->order_id,
            'provider_id'        => $request->provider_id,
            'status'             => 'new',
            'delivery_date_time' => $order->delivery_date_time,
            'total_price'        => $request->total_price,
            'total_tax'        => $request->total_tax,
            'total_before_tax'        => $request->total_before_tax,
            'note'               => $request->note,
        ]);

        foreach ($request->offer_details as $detail){
            OrderOfferDetails::create([
                'order_id'         => $request->order_id,
                'order_offer_id'   => $order_offer->id,
                'product_id'       => $detail['product_id'],
                'qty'              => $detail['qty'],
                'type'             => $detail['type'],
                'price'            => $detail['price'],
                'total_price'      => $detail['total_price'],
                'available_qty'    => $detail['available_qty'],
                'other_product_id' => ($detail['other_product_id']) ?? null,
            ]);
        }

        $order_provider->update(['status' => 'offered']);
        $title = 'new offer';
        $body = 'you have new offer';
        $user_id = $order->user_id;
        $provider_id = null;

        Order::find($order->id)->update(['status'=> 'offered']);
//        dd($order);
         $this->sendBasicNotification($title,$body,$order->id,$user_id,$provider_id,$request->provider_id);

        return $this->returnSuccessMessage('تم تقديم العرض بنجاح');
    }

//    public function update_order_status(request $request){
//        $validator = Validator::make($request->all(),[
//            'provider_id' => 'required|exists:providers,id',
//            'order_id'    => 'required|exists:orders,id',
//        ]);
//        if ($validator->fails()) {
//            $code = $this->returnCodeAccordingToInput($validator);
//            return $this->returnValidationError($code, $validator);
//        }
//
//
//    }


}
