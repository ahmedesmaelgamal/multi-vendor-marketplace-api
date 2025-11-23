<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Http\Controllers\Controller;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TeamController extends Controller
{
    use PhotoTrait;
    public function index(request $request)
    {
        if($request->ajax()) {
            $teams = Team::latest()->get();
            return Datatables::of($teams)
                ->addColumn('action', function ($teams) {
                    return '
                            <button type="button" data-id="' . $teams->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $teams->id . '" data-title="' . $teams->title . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('facebook', function ($teams) {
//                    if ($teams->facebook != null){
                        return '
                    <div class="wideget-user-icons mb-4">
						<a href="'.$teams->facebook.'" class="bg-facebook text-white btn btn-circle"><i class="fab fa-facebook"></i></a>
						<a href="'.$teams->twitter.'" class="bg-info text-white btn btn-circle"><i class="fab fa-twitter"></i></a>
						<a href="'.$teams->gmail.'" class="bg-google text-white btn btn-circle"><i class="fab fa-google"></i></a>
					</div>
                    ';
//                    }
                })
                ->editColumn('photo', function ($teams) {
                    return '
                    <img alt="Slider" onclick="window.open(this.src)" style="cursor:pointer" class="avatar avatar-lg bradius cover-image" src="'.getFile($teams->photo).'">
                    ';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
//            return view('admin.teams.index');
        }
    }



    public function create(){
//        return view('admin.teams.parts.create');
    }

    public function store(request $request): \Illuminate\Http\JsonResponse
    {
        $inputs = $request->validate([
            'photo'      => 'required|mimes:jpeg,jpg,png,gif',
            'name'       => 'required|max:255',
            'job'        => 'required|max:255',
            'facebook'   => 'nullable|url|max:255',
            'twitter'    => 'nullable|url|max:255',
            'gmail'      => 'nullable|max:255',
        ]);
        if($request->has('photo')){
            $file_name = $this->saveImage($request->photo,'assets/uploads/teams');
            $inputs['photo'] = 'assets/uploads/teams/'.$file_name;
        }
        if(Team::create($inputs))
            return response()->json(['status'=>200]);
        else
            return response()->json(['status'=>405]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        //
    }


    public function edit(Team $team){
        return view('admin.teams.parts.edit',compact('team'));
    }



    public function update(Request $request)
    {
        $inputs = $request->validate([
            'id'         => 'required',
            'photo'      => 'nullable|mimes:jpeg,jpg,png,gif',
            'name'       => 'required|max:255',
            'job'        => 'required|max:255',
            'facebook'   => 'nullable|max:255',
            'twitter'    => 'nullable|max:255',
            'gmail'      => 'nullable|max:255',
        ]);
        $team = Team::findOrFail($request->id);
        if($request->has('photo')){
            if (file_exists($team->photo)) {
                unlink($team->photo);
            }
            $file_name = $this->saveImage($request->photo,'assets/uploads/teams');
            $inputs['photo'] = 'assets/uploads/teams/'.$file_name;
        }
        if ($team->update($inputs))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
    }



    public function delete(Request $request)
    {
        $team = Team::findOrFail($request->id);
        if (file_exists($team->photo)) {
            unlink($team->photo);
        }
        $team->delete();
        return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
    }
}
