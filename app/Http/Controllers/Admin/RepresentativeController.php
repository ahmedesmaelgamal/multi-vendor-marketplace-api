<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\OrderProviders;
use App\Models\Representative;
use App\Models\OrderRepresentative;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RepresentativeController extends Controller
{
    use PhotoTrait;

    public function index(request $request)
    {

        if ($request->ajax()) {
            $representative = Representative::latest()->get();
            return Datatables::of(source: $representative)
                ->editColumn('action', function ($representative) {
                    $url = route('representatives.show', $representative->id);
                    return '
        <a href="' . $url . '" class="btn btn-pill btn-info-light">
            <i class="fa fa-eye"></i>
        </a>  ';
                })->editColumn('nationality_id', function ($representative) {
                    return $representative->nationality->title_ar ?? '';
                })->editColumn('image', function ($representative) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . get_user_file(image: $representative->image) . '">
                    ';
                })->editColumn('nationality_id', function ($representative) {
                    return $representative->nationality->title_ar ?? '';
                })->editColumn('town_id', function ($representative) {
                    return $representative->town->title_ar ?? '';
                })->editColumn('provider_id', function ($representative) {
                    return $representative->provider->name ?? '';
                })->editColumn('status', function ($representative) {
                    return $representative->status == 1
                        ? '<span class="badge badge-success">نشط</span>'
                        : '<span class="badge badge-danger">غير نشط</span>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.representatives.index');
        }
    }

    public function show($id)
    {
        $orderRepresentatives  = OrderRepresentative::where('representative_id', $id)->get();
        return view('admin.representatives.show', compact(var_name: 'orderRepresentatives'));
    }
}
