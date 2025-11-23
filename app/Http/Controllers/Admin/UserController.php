<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    use PhotoTrait;

    public function index(request $request)
    {

        if ($request->ajax()) {
            $users = User::latest()->get();
            return Datatables::of($users)
                ->addColumn('action', function ($town) {
                    return '
                            <button type="button" data-id="' . $town->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $town->id . '" data-title="' . $town->title . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.users.index');
        }
    }

    public function showOrdersUser($id)
    {
        $orders = Order::where('user_id', $id)->get();
        return view('admin.users.show', compact('orders'));
    }
}
