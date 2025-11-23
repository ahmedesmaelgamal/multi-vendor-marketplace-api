<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Provider;
use App\Traits\Notification;
use App\Traits\NotificationTrait;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProviderController extends Controller
{
    use PhotoTrait, Notification;

    public function index(Request $request)
    {
        $query = Provider::with('categories');

        if ($request->ajax()) {

            if ($request->filled('category_id')) {
                $query = $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('categories.id', $request->category_id);
                });
            }


            return Datatables::of($query)
                ->editColumn('action', function ($representative) {
                    $url = route('ProviderShow', $representative->id);
                    return '
        <a href="' . $url . '" class="btn btn-pill btn-info-light">
            <i class="fa fa-eye"></i>
        </a>
        ';
                })
                ->editColumn('image', function ($provider) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar-md rounded-circle" src="' . $provider->image . '">
                ';
                })
                ->editColumn('status', function ($provider) {
                    return '
                        <div class="form-check form-switch">
                            <input style="margin-left: 0px;" 
                                class="tgl tgl-ios statusBtn form-check-input" 
                                data-id="' . $provider->id . '" 
                                name="statusBtn" 
                                id="statusUser-' . $provider->id . '" 
                                type="checkbox" 
                                ' . ($provider->status == 1 ? 'checked' : '') . (false ? ' disabled' : '') . '/>
                            <label class="tgl-btn" dir="ltr" for="statusUser-' . $provider->id . '"></label>
                        </div>';
                })

                ->addColumn('categories', function ($provider) {
                    $count = $provider->categories->count();
                    return '<a href="javascript:void(0)"
                          class="showCategoriesLink"
                          data-id="' . $provider->id . '">
                          <span class="badge badge-info">' . $count . ' Categories</span>
                        </a>';
                })
                ->escapeColumns([])
                ->make(true);
        }
        $categories = Category::all();
        return view('admin.providers.index', compact('categories'));
    }


    public function show($id)
    {
        $orders = Order::where('provider_id', $id)->get();

        return view('admin.providers.show', compact(var_name: 'orders'));
    }

    public function updateColumnSelected(Request $request, $column = "status")
    {
        try {
            $ids = $request->input('ids');
            if (is_array($ids) && count($ids)) {
                foreach ($ids as $id) {

                    $obj = $this->getById($id);

                    $obj->{$column} = !$obj->{$column};
                    $obj->save();
                }

                $this->sendBasicNotification(
                    title: "update status notification",
                    body: "your account is active now",
                    provider_id: $obj->id
                );

                return response()->json(['status' => 200, 'message' => 'updated_successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'something_went_wrong']);
        }
    }


    public function getById(int $id)
    {
        return Provider::findOrFail($id);
    }
}
