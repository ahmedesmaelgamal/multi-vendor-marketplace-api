<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Slider;
use App\Traits\WebpTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class SlidersController extends Controller
{
    use WebpTrait;
    public function index(request $request)
    {
        if($request->ajax()) {
            $sliders = Slider::latest()->get();
            return Datatables::of($sliders)
                ->addColumn('action', function ($sliders) {
                    return '
                            <button type="button" data-id="' . $sliders->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $sliders->id . '" data-title="' . $sliders->title . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('image', function ($sliders) {
                    return '
                    <img alt="Slider" onclick="window.open(this.src)" style="cursor:pointer" class="avatar-lg bradius" src="'.$sliders->image.'">
                    ';
                })
                ->editColumn('product_id', function ($sliders) {
                    return $sliders->product->title_ar;
                })
                ->addColumn('status', function ($sliders) {
                    if($sliders->end_at >= Carbon::now())
                        return '<span class="badge badge-primary">متاح</span>';
                    else
                        return '<span class="badge badge-danger">انتهي</span>';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('admin.sliders.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(){
        $products = Product::select('id','title_ar')->get();
        return view('admin.sliders.parts.create',compact('products'));
    }

    public function store(request $request)
    {
        $inputs = $request->validate([
            'image'      => 'required|mimes:jpeg,jpg,png,gif,webp|image',
            'end_at'     => 'required|date_format:Y-m-d|after:yesterday',
            'product_id' => 'required|exists:products,id',
        ],[
            'image.required'     => 'يرجي رفع صورة',
            'end_at.required'    => 'يرجي اختيار تاريخ',
            'end_at.after'       => 'لا يمكن ادخال تاريخ ماضي',
            'product_id.required'=> 'يجب تحديد منتج'
        ]);
        if($request->has('image')){
            $inputs['image'] = $this->saveImage($request->image,'assets/uploads/sliders');
        }
        if(Slider::create($inputs))
            return response()->json(['status'=>200]);
        else
            return response()->json(['status'=>405]);
    }

    public function edit(Slider $slider){
        $products = Product::select('id','title_ar')->get();
        return view('admin.sliders.parts.edit',compact('slider','products'));
    }



    public function show($id)
    {
        //
    }

    public function update(request $request)
    {
        $inputs = $request->validate([
            'id'         => 'required',
            'image'      => 'nullable|mimes:jpeg,jpg,png,gif,webp|image',
            'end_at'     => 'required|date_format:Y-m-d|after:yesterday',
            'product_id' => 'required|exists:products,id',
        ]);

        $inputs = $request->except('id','_token');
        $slider = Slider::findOrFail($request->id);
        if($request->has('image')){
            if (file_exists($slider->getAttributes()['image'])) {
                unlink($slider->getAttributes()['image']);
            }
            $inputs['image'] = $this->saveImage($request->image,'assets/uploads/sliders');
        }
        if ($slider->update($inputs))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function delete(Request $request)
    {
        $slider = Slider::findOrFail($request->id);
        if (file_exists($slider->getAttributes()['image'])) {
            unlink($slider->getAttributes()['image']);
        }
        $slider->delete();
        return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
    }
}
