<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Nationality;
use App\Models\Town;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TownController extends Controller
{
    use PhotoTrait;
    public function index(request $request)
    {
        if ($request->ajax()) {
            $towns = Town::latest()->get();
            return Datatables::of($towns)
                ->addColumn('action', function ($town) {
                    return '
                            <button type="button" data-id="' . $town->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $town->id . '" data-title="' . $town->title . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })->editColumn('nationality_id', function ($town) {
                    return $town->nationality->title_ar ?? '';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.towns.index');
        }
    }


    public function create()
    {
        $Nationalities = Nationality::all();
        return view('admin.towns.parts.create', compact(var_name: 'Nationalities'));
    }

    public function store(request $request): \Illuminate\Http\JsonResponse
    {

        $inputs = $request->validate([
            'nationality_id'   => 'required',
            'title_ar'   => 'required',
            'title_en'   => 'required',
        ], [
            'title_ar.required' => 'الاسم بالعربيه مطلوب',
            'title_en.required' => 'الاسم بالانجليزيه مطلوب',
            'nationality_id.required' => 'البلد مطلوبه',
        ]);

        if (Town::create([
            'nationality_id' => $inputs['nationality_id'],
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


    public function edit(Town $town)
    {
        $Nationalities = Nationality::all();
        return view('admin.towns.parts.edit', compact('town', 'Nationalities'));
    }



    public function update(Request $request, $id)
    {
        $inputs = $request->validate([
            'title_ar'   => 'required',
            'title_en'   => 'required',
            'nationality_id'   => 'required',
        ], [
            'title_ar.required' => 'الاسم بالعربيه مطلوب',
            'title_en.required' => 'الاسم بالانجليزيه مطلوب',
            'nationality_id.required' => 'البلد مطلوبه',
        ]);

        $town = Town::findOrFail($id);
        if ($town->update($inputs))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
    }


    public function delete(Request $request)
    {
        $town = Town::findOrFail($request->id);
        $town->delete();
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }
}
