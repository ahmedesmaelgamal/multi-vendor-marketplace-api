<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderOffer;
use App\Models\OrderDetails;
use App\Models\OrderOfferDetails;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function newOrders(request $request)
    {
        if ($request->ajax()) {
            $orders = Order::where('status', 'new')->latest()->get();
            return Datatables::of($orders)
                ->addColumn('action', function ($orders) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $orders->id . '" data-title="' . $orders->user->name . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->addColumn('details', function ($orders) {
                    $detailsUrl = route('orderDetails', $orders->id);
                    $offersUrl = route('orderOffers', $orders->id);

                    return '
        <a class="btn btn-sm btn-info m-1" href="' . $detailsUrl . '">تفاصيل</a>
        <a class="btn btn-sm btn-primary m-1" href="' . $offersUrl . '">العروض</a>
    ';
                })
                ->editColumn('user_id', function ($orders) {
                    $url  = route('clientProfile', $orders->user->id);
                    $name = $orders->user->name;
                    return "<a class='text-dark fw-bold'  href = '" . $url . "'>$name</a>";
                })
                ->addColumn('phone', function ($orders) {
                    $phone = $orders->user->phone_code . $orders->user->phone;
                    return '<a href = "tel:' . $phone . '"> ' . $phone . '</a>';
                })
                ->editColumn('created_at', function ($orders) {
                    return $orders->created_at->diffForHumans();
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.orders.new-orders');
        }
    }


    public function currentOrders(request $request)
    {
        if ($request->ajax()) {
            $orders = Order::whereIn('status', ['accepted', 'offered', 'preparing', 'on_way'])->latest()->get();
            return Datatables::of($orders)
                ->addColumn('details', function ($orders) {
                    $detailsUrl = route('orderDetails', $orders->id);
                    $offersUrl = route('orderOffers', $orders->id);

                    return '
        <a class="btn btn-sm btn-info m-1" href="' . $detailsUrl . '">تفاصيل</a>
        <a class="btn btn-sm btn-primary m-1" href="' . $offersUrl . '">العروض</a>
    ';
                })
                ->editColumn('user_id', function ($orders) {
                    $url  = route('clientProfile', $orders->user->id);
                    $name = $orders->user->name;
                    return "<a class='text-dark fw-bold'  href = '" . $url . "'>$name</a>";
                })
                ->addColumn('phone', function ($orders) {
                    $phone = $orders->user->phone_code . $orders->user->phone;
                    return '<a href = "tel:' . $phone . '"> ' . $phone . '</a>';
                })
                ->editColumn('created_at', function ($orders) {
                    return $orders->created_at->diffForHumans();
                })
                ->editColumn('status', function ($orders) {
                    if ($orders->status == 'accepted')
                        return '<span class="badge badge-default">انتظار تقديم العروض</span>';
                    elseif ($orders->status == 'offered')
                        return '<span class="badge badge-warning">انتظار قبول العروض</span>';
                    elseif ($orders->status == 'preparing')
                        return '<span class="badge badge-success">يتم التحضير</span>';
                    else
                        return '<span class="badge badge-primary">يتم توصيله</span>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.orders.current-orders');
        }
    }

    public function endedOrders(request $request)
    {
        if ($request->ajax()) {
            $orders = Order::whereIn('status', ['rejected', 'delivered'])->latest()->get();
            return Datatables::of($orders)
                ->addColumn('details', function ($orders) {
                    $detailsUrl = route('orderDetails', $orders->id);
                    $offersUrl = route('orderOffers', $orders->id);

                    return '
        <a class="btn btn-sm btn-info m-1" href="' . $detailsUrl . '">تفاصيل</a>
        <a class="btn btn-sm btn-primary m-1" href="' . $offersUrl . '">العروض</a>
    ';
                })
                ->editColumn('user_id', function ($orders) {
                    $url  = route('clientProfile', $orders->user->id);
                    $name = $orders->user->name;
                    return "<a class='text-dark fw-bold'  href = '" . $url . "'>$name</a>";
                })
                ->addColumn('phone', function ($orders) {
                    $phone = $orders->user->phone_code . $orders->user->phone;
                    return '<a href = "tel:' . $phone . '"> ' . $phone . '</a>';
                })
                ->editColumn('created_at', function ($orders) {
                    return $orders->created_at->diffForHumans();
                })
                ->editColumn('status', function ($orders) {
                    if ($orders->status == 'rejected')
                        return '<span class="badge badge-danger">تم الالغاء</span>';
                    else
                        return '<span class="badge badge-primary">تم التوصيل</span>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.orders.ended-orders');
        }
    }

    public function orderDetails($id)
    {
        $orderDetails = OrderDetails::where('order_id', $id)->first();
        $orderDetailsCollection = OrderDetails::where('order_id', $id)->get();
        return view('admin.orders.order-details', compact('orderDetails', 'orderDetailsCollection'));
    }

    public function orderOffers($id)
    {
        $offers = OrderOffer::where('order_id', $id)->with('delivery_time')->get();
        return view('admin.orders.order-offers', compact('offers'));
    }

    public function offer_details($id)
    {
        $offersDetails = OrderOfferDetails::where('order_offer_id', $id)->get();
        return view('admin.orders.offers_details', compact('offersDetails'));
    }
}
