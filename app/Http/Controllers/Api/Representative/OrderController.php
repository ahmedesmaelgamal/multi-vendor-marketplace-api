<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderOffer;
use App\Models\OrderRepresentative;
use App\Traits\GeneralTrait;
use App\Traits\NotificationTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use GeneralTrait, NotificationTrait;
    public function new_orders(request $request)
    {
        $validator = Validator::make($request->all(), [
            'representative_id' => 'required|exists:representatives,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 400);
        }
        $data = OrderRepresentative::with('order', 'order.address', 'order.provider.town', 'order.provider.nationality')
            ->where([['representative_id', $request->representative_id], ['status', 'new']])->latest()->get();

        foreach ($data as $item) {
            $item['order']['day']  = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y / m / d');
            $item['order']['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('g:i A');
        }
        return $this->returnData('data', $data, 'Get Data Successfully');
    }

    public function order_details(request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'representative_id' => 'required|exists:representatives,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 400);
        }

        $order = Order::with('provider', 'user', 'address', 'provider.nationality', 'provider.town')->where('id', $request->order_id)->first();
        $offer = OrderOffer::with('offer_details')->where('order_id', $request->order_id)->first();
        if (!$offer) {
            return $this->returnError('E05', 'تأكد من قبول العرض عند العميل', 405);
        }
        $order['day']  = (Carbon::createFromFormat('Y-m-d H:i:s', $order['created_at'])->format('Y / m / d')) ?? 'غير محدد';
        $order['time'] = (Carbon::createFromFormat('Y-m-d H:i:s', $order['created_at'])->format('g:i A')) ?? 'غير محدد';
        $order['expected_delivery_time'] = $offer->delivery_date_time;
        $order['offer']  = $offer;
        $order['status'] = OrderRepresentative::where([['order_id', $order->id], ['representative_id', $request->representative_id]])->first()->status;
        return $this->returnData('data', $order, 'Get Data Successfully');
    }

    public function updateOrderStatus(request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'representative_id' => 'required|exists:representatives,id',
            'status' => 'required|in:accepted,picked_up,on_the_way,ended,rejected',
        ]);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 400);
        }

        $order_rep = OrderRepresentative::where([['order_id', $request->order_id], ['representative_id', $request->representative_id]])->first();

        if (!$order_rep)
            return $this->returnError('E10', 'يرجي اختيار طلب خاص بهذا المندوب', 405);

        $checkOrderStatus = OrderRepresentative::where('order_id', $request->order_id)
            ->whereNotIn('status', ['new', 'rejected'])->where('representative_id', '!=', $request->representative_id)->first();

        if ($checkOrderStatus)
            return $this->returnError('E10', 'يرجي التاكد من حالة الطلب', 420);


        $order = Order::find($request->order_id);

        $update = $order_rep->update(['status' => $request->status]);
        if ($update) {
            $title = 'new notification';
            $body = '';
            $user_id = $order->user_id;
            $provider_id = $order->provider_id;
            if ($request->status == 'picked_up') {
                $body = 'order is picked up by the delivery';
                $this->sendBasicNotification($title, $body, $request->order_id, $user_id, $provider_id, null, null, $request->representative_id);
            } elseif ($request->status == 'accepted') {
                $body = 'order is accepted by a representative';
                $this->sendBasicNotification($title, $body, $request->order_id, $user_id, $provider_id, null, null, $request->representative_id);
            } elseif ($request->status == 'on_the_way') {
                $order->status = 'on_way';
                $body = 'your order is on way';
                $this->sendBasicNotification($title, $body, $request->order_id, $user_id, $provider_id, null, null, $request->representative_id);
            } elseif ($request->status == 'ended') {
                $order->status = 'delivered';
                $delivery_time = Carbon::parse(now())->getTimestamp() * 1000;
                OrderOffer::where('order_id', $request->order_id)
                    ->where('provider_id', $order->provider_id)->update(['status' => 'ended']);
                $body = 'your order is delivered';
                Order::where('id', $request->order_id)->update(['status' => 'delivered', 'delivery_date_time' => $delivery_time]);
                $this->sendBasicNotification($title, $body, $request->order_id, $user_id, $provider_id, null, null, $request->representative_id);
            } elseif ($request->status == 'rejected') {
                $order->status = 'preparing';
                $body = 'delivery is rejected by the representative';

                // تحديث حالة الطلب الحالي
                $order_rep->update(['status' => 'rejected']);

                $order->rejected_from_all = 0;

                // نرسل إشعار إن المندوب ده رفض
                $this->sendBasicNotification(
                    $title,
                    $body,
                    $request->order_id,
                    null,
                    $order->provider_id,
                    null,
                    null,
                    $request->representative_id
                );

                //  هنا نعمل تشيك لو كل المناديب رفضوا
                $allRejected = OrderRepresentative::where('order_id', $request->order_id)
                    ->where('status', '!=', 'rejected')
                    ->exists();
                // خلي بالك دي بترجع بترو لو في مندوبين لسه مرفضوش ف عشان كده انا بعمل اتشيك ب فولس

                if (!$allRejected) {
                    
                    $order->status = 'accepted';
                    // here if all representitives reject the provider order 
                    //   make the rejected_from_all key of order true 
                    $order->rejected_from_all = 1;
                    $order->save();

                    $body = 'All representatives rejected the order';
                    $this->sendBasicNotification(
                        $title,
                        $body,
                        $request->order_id,
                        null,
                        $order->provider_id,
                        null,
                        null,
                        null
                    );
                }
                $order->save();
            }
        }
        return $this->returnData('data', $order_rep, 'Done Successful');
    }
    public function current_orders(request $request)
    {
        $validator = Validator::make($request->all(), [
            'representative_id' => 'required|exists:representatives,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 400);
        }
        $data = OrderRepresentative::with('order', 'order.provider', 'order.address', 'order.provider.town', 'order.provider.nationality')->where('representative_id', $request->representative_id)->whereNotIn('status', ['new', 'rejected', 'ended'])->latest()->get();
        foreach ($data as $item) {
            $item['order']['day']  = (Carbon::createFromFormat('Y-m-d H:i:s', $item['order']['created_at'])->format('Y / m / d')) ?? 'غير محدد';
            $item['order']['time'] = (Carbon::createFromFormat('Y-m-d H:i:s', $item['order']['created_at'])->format('g:i A')) ?? 'غير محدد';
        }
        return $this->returnData('data', $data, 'get data successfully');
    }

    public function last_orders(request $request)
    {
        $validator = Validator::make($request->all(), [
            'representative_id' => 'required|exists:representatives,id',
            'time'              => 'required|in:week,month,year,all',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 400);
        }
        if ($request->time == 'week') {
            $date = date("Y-m-d", strtotime(date('Y-m-d') . "-1 week"));
            $data = OrderRepresentative::where('representative_id', $request->representative_id)->wherein('status', ['ended'])
                ->whereBetween('created_at', [$date, date('Y-m-d')])
                ->with('order.provider', 'order.provider.nationality', 'order.provider.town', 'order.address')->latest()->get();
        } elseif ($request->time == 'month') {
            $date = date("Y-m-d", strtotime(date('Y-m-d') . "-1 month"));
            $data = OrderRepresentative::where('representative_id', $request->representative_id)->wherein('status', ['ended'])
                ->whereBetween('created_at', [$date, date('Y-m-d')])
                ->with('order.provider', 'order.provider.nationality', 'order.provider.town', 'order.address')->latest()->get();
        } elseif ($request->time == 'year') {
            $date = date("Y-m-d", strtotime(date('Y-m-d') . "-1 year"));
            $data = OrderRepresentative::where('representative_id', $request->representative_id)->wherein('status', ['ended'])
                ->whereBetween('created_at', [$date, date('Y-m-d')])
                ->with('order.provider', 'order.provider.nationality', 'order.provider.town', 'order.address')->latest()->get();
        } else {
            $data = OrderRepresentative::where('representative_id', $request->representative_id)->wherein('status', ['ended'])
                ->with('order.provider', 'order.provider.nationality', 'order.provider.town', 'order.address')->latest()->get();
        }
        foreach ($data as $item) {
            $item['order']['day']  = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y / m / d');
            $item['order']['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('g:i A');
        }
        return $this->returnData('data', $data, 'get data successfully');
    }


}
