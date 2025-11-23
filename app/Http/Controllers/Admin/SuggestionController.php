<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductSuggestion;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SuggestionController extends Controller
{
    public function index(request $request)
    {

        if ($request->ajax()) {
            $suggestions = ProductSuggestion::latest()->get();
            return Datatables::of($suggestions)
                ->addColumn('action', function ($suggestions) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $suggestions->id . '" data-title="' . $suggestions->title . '"><i class="fas fa-trash"></i></button>
                       ';
                })
                ->editColumn('created_at', function ($suggestions) {
                    return $suggestions->created_at
                        ? $suggestions->created_at->format('Y-m-d H:i')
                        : '';
                })
                ->editColumn('provider_id', function ($suggestions) {
                    return $suggestions->provider->name;
                })
                ->editColumn('image', function ($suggestions) {
                    if ($suggestions->image) {
                        return '<img alt="image" onclick="window.open(this.src)" class="avatar-md rounded-circle" src="' . asset($suggestions->image) . '">';
                    } else {
                        return '<img alt="image" onclick="window.open(this.src)" class="avatar-md rounded-circle" src="' . asset('assets/uploads/product.webp') . '">';
                    }
                })

                ->editColumn('main_category_id', function ($suggestions) {
                    return $suggestions->category->title_ar;
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.suggestions.index');
        }
    }

    public function delete(request $request)
    {
        $pro = ProductSuggestion::findOrFail($request->id)->first();
        if (file_exists($pro->getAttributes()['image']))
            unlink($pro->getAttributes()['image']);

        $pro->delete();

        return response(['message' => 'تمت عملية الحذف بنجاح', 'status' => 200], 200);
    }
}
