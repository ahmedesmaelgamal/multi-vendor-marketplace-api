<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reviews;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function clientProfile($id)
    {
        $user    = User::findOrFail($id);
        $reviews = Reviews::where('user_id', $id)->get();
        $count   = $reviews->count();
        if ($count > 0)
            $average = $reviews->sum('rate') / $reviews->count();
        else
            $average = 0;

        $new_orders      = $user->orders->where('status', 'new')->count();
        $offered_orders  = $user->orders->where('status', 'offered')->count();
        $current_orders  = $user->orders->whereIn('status', ['accepted', 'preparing', 'on_way'])->count();
        $ended_orders    = $user->orders->where('status', 'delivered')->count();
        $canceled_orders = $user->orders->where('status', 'rejected')->count();

        return view('admin.users.profile', compact('user', 'average', 'count', 'new_orders', 'offered_orders', 'current_orders', 'ended_orders', 'canceled_orders'));
    }

    public function index(request $request)
    {
        if ($request->ajax()) {
            $clients = User::latest()->get();
            return Datatables::of($clients)
                ->addColumn('action', function ($clients) {
                    $url  = route('clientProfile', $clients->id);
                    return '
                     <a href="' . $url . '" title="عرض تفاصيل" class="btn btn-pill btn-success-light"><i class="fas fa-eye"></i></a>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $clients->id . '" data-title="' . $clients->name . '"><i class="fas fa-trash"></i></button>
                       ';
                })
                ->editColumn('name', function ($clients) {
                    $url  = route('clientProfile', $clients->id);
                    $name = $clients->name;
                    return "<a class='text-dark fw-bold'  href = '" . $url . "'>$name</a>";
                })

                ->addColumn('phone', function ($clients) {
                    $phone = $clients->phone_code . $clients->phone;
                    return '<a href = "tel:' . $phone . '"> ' . $phone . '</a>';
                })

                ->editColumn('image', function ($clients) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar-md rounded-circle" src="' . $clients->image . '">
                    ';
                })

                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.users.index');
        }
    }

    public function delete(request $request)
    {
        $client = User::findOrFail($request->id)->first();
        if (file_exists($client->getAttributes()['image']))
            unlink($client->getAttributes()['image']);

        $client->delete();
        return response(['message' => 'تمت عملية الحذف بنجاح', 'status' => 200], 200);
    }
}
