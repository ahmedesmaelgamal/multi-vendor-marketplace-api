<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategorySubCategories;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubCategoryController extends Controller
{
    use PhotoTrait;
    public function index(request $request)
    {
        if($request->ajax()) {
            $subcategories = CategorySubCategories::all();
            return Datatables::of($subcategories)
                ->addColumn('action', function ($categories) {
                    return '
                            <button type="button" data-id="' . $categories->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $categories->id . '" data-title="' . @$categories->subCategory->title_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->addColumn('title', function ($categories) {
                    return @$categories->subCategory->title_ar;
                })
                ->addColumn('level', function ($categories) {
                    return @$categories->mainCategory->title_ar;
                })
                ->addColumn('image', function ($categories) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar-md rounded-circle" src="'.@$categories->subCategory->image.'">
                    ';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('admin.subcategory.index');
        }
    }



    public function create()
    {
        $categories = Category::MainCategory()->get();
        return view('admin.subcategory.parts.create',compact('categories'));
    }



    public function store(request $request): \Illuminate\Http\JsonResponse
    {
        $inputs = $request->validate([
            'title_ar'     => 'required|max:255',
            'title_en'     => 'required|max:255',
            'category_id'  => 'required|max:255|exists:categories,id',
            'image'        => 'required|mimes:jpeg,jpg,png,gif,svg',
        ]);
        if($request->has('image')){
            $file_name = $this->saveImage($request->image,'assets/uploads/subcategories');
            $inputs['image'] = 'assets/uploads/subcategories/'.$file_name;
        }
        $inputs['level'] = 2;
        unset($inputs['category_id']);
        $newMain = Category::create($inputs);
        $newSub  = CategorySubCategories::create([
            'category_id' => $request->category_id,
            'sub_category_id' => $newMain->id
        ]);
        if($newSub)
            return response()->json(['status'=>200]);
        else
            return response()->json(['status'=>405]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category   = CategorySubCategories::findOrFail($id);
        $categories = Category::MainCategory()->get();
        return view('admin.subcategory.parts.edit',compact('categories','category'));
    }



    public function update(Request $request, $id)
    {
        $inputs = $request->validate([
            'title_ar'      => 'required|max:255',
            'title_en'      => 'required|max:255',
            'image'         => 'sometimes|mimes:jpeg,jpg,png,gif,svg',
        ]);
        $category = Category::findOrFail($id);
        if ($request->has('image')) {
            $file_name = $this->saveImage($request->image, 'assets/uploads/categories');
            $inputs['image'] = 'assets/uploads/categories/' . $file_name;
        }
        unset($inputs['category_id']);
        $category->update($inputs);
        $sub = CategorySubCategories::findOrFail($request->id);
        $sub->update([
            'category_id' => $request->category_id
        ]);
        return response()->json(['status' => 200]);
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
        $subCategory = CategorySubCategories::findOrFail($request->id);
        if (file_exists($subCategory->image)) {
            unlink($subCategory->image);
        }
        $subCategory->subCategory->delete();
        $subCategory->delete();
        return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
    }
}
