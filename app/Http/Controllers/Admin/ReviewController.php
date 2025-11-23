<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{
    public function index(request $request)
    {
        if($request->ajax()) {
            $reviews = Reviews::latest()->get();
            return Datatables::of($reviews)
                ->addColumn('action', function ($reviews) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $reviews->id . '" data-title="' . $reviews->text . '"><i class="fas fa-trash"></i></button>
                       ';
                })
                ->editColumn('created_at', function ($reviews) {
                    return $reviews->created_at->diffForHumans();
                })
                ->editColumn('provider_id', function ($reviews) {
                    return $reviews->provider->name;
                })
                ->editColumn('user_id', function ($reviews) {
                    $url  = route('clientProfile',$reviews->user->id);
                    $name = $reviews->user->name;
                    return "<a class='fw-bold'  href = '".$url."'>$name</a>";
                })
                ->editColumn('order_id', function ($reviews) {
                    return '<span class="fw-bold">#'.$reviews->order_id.'</span>';
                })
                ->editColumn('rate', function ($reviews) {
                    $html = null;
                    for($i = 1 ; $i <= $reviews->rate ; $i++)
                        $html .= '<i class ="fa fa-star text-warning"></i>';
                    for($i = $reviews->rate ; $i < 5 ; $i++)
                        $html .= '<i class ="fa fa-star text-dark"></i>';

                    return $html;
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('admin.reviews.index');
        }
    }

    public function delete(request $request)
    {
        $review = Reviews::findOrFail($request->id)->first();
        $review->delete();
        return response(['message'=>'تمت عملية الحذف بنجاح','status'=>200],200);
    }
}
