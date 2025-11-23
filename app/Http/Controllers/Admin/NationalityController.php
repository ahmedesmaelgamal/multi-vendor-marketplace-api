<?php

namespace App\Http\Controllers\Admin;

// use App\Models\Team;
use App\Models\DeliveryTime;
use App\Http\Controllers\Controller;
use App\Models\Nationality;
use App\Traits\PhotoTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NationalityController extends Controller
{
    use PhotoTrait;
    public function index(request $request)
    {
        // dd(DeliveryTime::latest()->get());
        if ($request->ajax()) {
            $nationalities = Nationality::latest()->get();
            return Datatables::of($nationalities)
                ->addColumn('action', function ($nationalities) {
                    return '
                            <button type="button" data-id="' . $nationalities->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $nationalities->id . '" data-title="' . $nationalities->title . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('image', function ($nationalities) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar-md rounded-circle" src="' . $nationalities->image . '">
                    ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.nationalities.index');
        }
    }



    public function create()
    {
        return view('admin.nationalities.parts.create');
    }

    public function store(request $request): \Illuminate\Http\JsonResponse
    {

        $inputs = $request->validate([
            'title_ar'   => 'required',
            'title_en'   => 'required',
            'image'   => 'required',
        ], [
            'title_ar.required' => 'الاسم بالعربيه مطلوب',
            'title_en.required' => 'الاسم بالانجليزيه مطلوب',
            'image.required' => 'الصوره مطلوبه',
        ]);

        if ($request->has(key: 'image')) {
            $file_name = $this->saveImage($request->image, 'assets/uploads/nationalities');
            $inputs['image'] = 'assets/uploads/nationalities/' . $file_name;
        }


        if (Nationality::create([
            'image' => $inputs['image'],
            'title_ar' => $inputs['title_ar'],
            'title_en' => $inputs['title_en'],
        ]))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
    }


    public function show(Nationality $nationality)
    {
        //
    }


    public function edit(Nationality $nationality)
    {
        return view('admin.nationalities.parts.edit', compact(var_name: 'nationality'));
    }



    public function update(Request $request, $id)
    {
        $inputs = $request->validate([
            'title_ar'   => 'required',
            'title_en'   => 'required',
            'photo'      => 'nullable|mimes:jpeg,jpg,png,gif,webp|image',
        ], [
            'title_ar.required' => 'الاسم بالعربيه مطلوب',
            'title_en.required' => 'الاسم بالانجليزيه مطلوب',
            'photo.mimes'       => 'صيغة الصورة غير مدعومة',
        ]);

        $nationality = Nationality::findOrFail($id);

        if ($request->has(key: 'image')) {
            if (file_exists($nationality->image)) {
                unlink($nationality->image);
            }
            $file_name = $this->saveImage($request->image, 'assets/uploads/nationalities');
            $inputs['image'] = 'assets/uploads/nationalities/' . $file_name;
        }
        if ($nationality->update($inputs))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
    }


    public function delete(Request $request)
    {
        $nationality = Nationality::findOrFail($request->id);
        if (file_exists($nationality->photo)) {
            unlink($nationality->photo);
        }
        $nationality->delete();
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }
}
