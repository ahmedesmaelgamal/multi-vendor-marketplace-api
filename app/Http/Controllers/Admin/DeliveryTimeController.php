<?php

namespace App\Http\Controllers\Admin;

// use App\Models\Team;
use App\Models\DeliveryTime;
use App\Http\Controllers\Controller;
use App\Traits\PhotoTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DeliveryTimeController extends Controller
{
    use PhotoTrait;
    public function index(request $request)
    {
        if ($request->ajax()) {
            $deliveryTimes = DeliveryTime::latest()->get();

            return Datatables::of($deliveryTimes)
                ->editColumn('from', function ($deliveryTime) {
                    return date('h:i A', $deliveryTime->from / 1000);
                    // أو باستخدام Carbon:
                    // return \Carbon\Carbon::createFromTimestampMs($deliveryTime->from)->format('h:i A');
                })
                ->editColumn('to', function ($deliveryTime) {
                    return date('h:i A', $deliveryTime->to / 1000);
                })
                ->addColumn('action', function ($deliveryTime) {
                    return '
                <button type="button" data-id="' . $deliveryTime->id . '" class="btn btn-pill btn-info-light editBtn">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                        data-id="' . $deliveryTime->id . '" data-title="' . $deliveryTime->title . '">
                    <i class="fas fa-trash"></i>
                </button>
            ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.delivery_time.index');
        }
    }


    public function create()
    {
        return view('admin.delivery_time.parts.create');
    }

    public function store(request $request): \Illuminate\Http\JsonResponse
    {
        $inputs = $request->validate([
            'from'   => 'required',
            'to'   => 'required',
        ], []);
        $from = Carbon::parse($request->from)->getTimestamp() * 1000;
        $to = Carbon::parse($request->to)->getTimestamp() * 1000;
        // dd($from);


        if (DeliveryTime::create([
            'from' => $from,
            'to' => $to,
        ]))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryTime  $deliveryTime
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryTime $deliveryTime)
    {
        //
    }


    public function edit(DeliveryTime $deliveryTime)
    {
        $deliveryTime = DeliveryTime::findOrFail($deliveryTime->id);
        return view('admin.delivery_time.parts.edit', compact(var_name: 'deliveryTime'));
    }



    public function update(Request $request, $id)

    {
        // Validate
        $request->validate([
            'from' => 'required',
            'to'   => 'required',
        ]);

        // تحويل الوقت إلى timestamp * 1000 مثل طريقة الـ store
        $from = Carbon::parse($request->from)->getTimestamp() * 1000;
        $to   = Carbon::parse($request->to)->getTimestamp() * 1000;

        // جلب الـ DeliveryTime
        $deliveryTime = DeliveryTime::findOrFail($id);

        // تحديث
        $deliveryTime->update([
            'from' => $from,
            'to'   => $to,
        ]);

        // إعادة الرد JSON
        return response()->json([
            'message' => 'تم تحديث وقت التوصيل بنجاح',
            'status' => 200
        ], 200);
    }




    public function delete(Request $request)
    {
        $deliveryTime = DeliveryTime::findOrFail($request->id);
        if (file_exists($deliveryTime->photo)) {
            unlink($deliveryTime->photo);
        }
        $deliveryTime->delete();
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }
}
