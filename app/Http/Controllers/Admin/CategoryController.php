<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\PhotoTrait;
use App\Traits\WebpTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    use WebpTrait;
    public function index(request $request)
    {
        if($request->ajax()) {
            $categories = Category::MainCategory()->get();
            return Datatables::of($categories)
                ->addColumn('action', function ($categories) {
                    return '
                            <button type="button" data-id="' . $categories->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $categories->id . '" data-title="' . $categories->title_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('image', function ($categories) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar-md rounded-circle" src="'.$categories->image.'">
                    ';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('admin.category.index');
        }
    }


    public function create()
    {
        return view('admin.category.parts.create');
    }



    public function store(request $request): \Illuminate\Http\JsonResponse
    {
        $inputs = $request->validate([
            'title_ar'   => 'required|max:255|unique:categories',
            'title_en'   => 'required|max:255|unique:categories',
            'image'      => 'required|mimes:jpeg,jpg,png,gif,webp,svg',
        ],[
            'title_ar.unique'     => 'اسم التصنيف باللغة العربية مستخدم من قبل',
            'title_en.unique'     => 'اسم التصنيف باللغة الانجليزية مستخدم من قبل'
        ]);
        if($request->has('image'))
            $inputs['image']  = $this->saveImage($request->image, 'assets/uploads/categories');

        $inputs['level'] = 1;
        if(Category::create($inputs))
            return response()->json(['status'=>200]);
        else
            return response()->json(['status'=>405]);
    }


    public function show($id)
    {
        //
    }


    public function edit(Category $category)
    {
        return view('admin.category.parts.edit',compact('category'));
    }



    public function update(Request $request,$id)
    {
        $inputs = $request->validate([
            'title_ar'      => 'required|max:255|unique:categories,title_ar,'.$id,
            'title_en'      => 'required|max:255|unique:categories,title_en,'.$id,
            'image'         => 'sometimes|mimes:jpeg,jpg,png,gif,webp,svg',
        ],[
            'title_ar.unique'     => 'اسم التصنيف باللغة العربية مستخدم من قبل',
            'title_en.unique'     => 'اسم التصنيف باللغة الانجليزية مستخدم من قبل'
        ]);
        $category = Category::findOrFail($id);

        if ($request->has('image'))
            $inputs['image']  = $this->saveImage($request->image, 'assets/uploads/categories');

        if ($category->update($inputs))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
    }


    public function destroy($id)
    {
        //
    }

    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->id);
        if (file_exists($category->getAttributes()['image'])) {
            unlink($category->getAttributes()['image']);
        }
        $category->delete();
        return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
    }
}
