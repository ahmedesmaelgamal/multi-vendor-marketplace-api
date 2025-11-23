<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Models\Nationality;
use App\Models\Provider;
use App\Models\Representative;
use App\Models\Token;
use App\Traits\GeneralTrait;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    use GeneralTrait,PhotoTrait;
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'      => 'required|exists:representatives,phone',
            'phone_code' => 'required|exists:representatives,phone_code',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,400);
        }

        $data = Representative::where($request->only(['phone', 'phone_code']))->first();

        $data['provider_id'] = (int)$data['provider_id'];
        $data['provider_code'] = (string)($data['provider_id'] * 1515);

        return $this->returnData('data', $data , "login successfully ");
    }//end fun

    public function nationalities(){
        $data = Nationality::has('towns')->with('towns')->latest()->get();
        return $this->returnData('data', $data);
    }

    public function register(request $request){
        $validator = Validator::make($request->all(),[
            'phone'             => 'required|unique:representatives,phone',
            'phone_code'        => 'required',
            'name'              => 'required|max:255',
            'provider_code'     => 'nullable',
            'image'             => 'nullable|image',
            'nationality_id'    => 'required',
            'town_id'           => 'required',
            'residence_number'  => 'required',
            'delivery_range'    => 'required',
        ]);


        // dd($request->all());

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,406);
        }

        $validator = Validator::make($request->all(),[
            'nationality_id'    => 'exists:nationalities,id',
            'town_id'           => [Rule::exists('towns','id')
                ->where('nationality_id',$request->nationality_id)],
        ]);

        if ($validator->fails()) {
            return $this->returnError('E01','يرجي ادخال بيانات صحيحة لكل من البلد والمدينة',407);
        }

        $validator = Validator::make($request->all(),[
            'residence_number'  => 'max:255|unique:representatives,residence_number',
        ]);

        if ($validator->fails()) {
            return $this->returnError('E02','رقم الاقامة تم تسجيله من قبل',408);
        }

        $validator = Validator::make($request->all(),[
            'delivery_range'    => 'integer|between:1,100',
        ]);

        if ($validator->fails()) {
            return $this->returnError('E03','نطاق التوصيل يجب ان يكون من 1 الي 100 كيلو متر',409);
        }


        if($request->has('provider_code') && $request->provider_code != null){
            $provider = Provider::find($request->provider_code/1515);
            if(!$provider)
                return $this->returnError('E04','كود المندوب غير صحيح',410);

        }
        $data = $request->except('provider_code');
        $data['provider_id'] = ($provider->id) ?? null;
        $data['status'] = 1;

        if($request->has('image')){
            $file_name = $this->saveImage($request->image,'assets/uploads/representatives');
            $data['image'] = 'assets/uploads/representatives/'.$file_name;
        }
        $rev  = Representative::create($data);
        $data = Representative::find($rev->id);
        $data['provider_code'] = (string)($data['provider_id'] * 1515);
        $data['provider_id'] = (int)$data['provider_id'] ;

        
        return $this->returnData('data', $data , "registed successfully");
    }

    public function changeStatus(request $request){
        $valid = Validator::make($request->all(),[
           'representative_id' => 'required|exists:representatives,id'
        ]);
        if ($valid->fails()) {
            $code = $this->returnCodeAccordingToInput($valid);
            return $this->returnValidationError($code, $valid,406);
        }
        $rev = Representative::find($request->representative_id);
        ($rev->status == 1) ? $rev->status = 0 : $rev->status = 1;
        $rev->save();
        return $this->returnData('data',$rev,'تم تغيير الحالة بنجاح');
    }

    public function update_profile(request $request){
        $validator = Validator::make($request->all(),[
            'representative_id' => 'required|exists:representatives,id',
            'phone'             => 'required',
            'phone_code'        => 'required',
            'name'              => 'required|max:255',
            'image'             => 'nullable|image',
            'delivery_range'    => 'required',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,406);
        }

        $validator = Validator::make($request->all(),[
            'delivery_range'    => 'integer|between:1,100',
        ]);

        if ($validator->fails()) {
            return $this->returnError('E03','نطاق التوصيل يجب ان يكون من 1 الي 100 كيلو متر',409);
        }

        $validator = Validator::make($request->all(),[
            'phone'    => 'unique:representatives,phone,'.$request->representative_id,
        ]);

        if ($validator->fails()) {
            return $this->returnError('E10','الهاتف مسجل من قبل',407);
        }

        if($request->has('provider_code') && $request->provider_code != null){
            $provider = Provider::find($request->provider_code/1515);
            if(!$provider)
                return $this->returnError('E04','كود المندوب غير صحيح',410);
        }

        $data = $request->except('provider_code','representative_id');

        $data['provider_id'] = ($provider->id) ?? null;

        
        $rev = Representative::find($request->representative_id);
        if($request->hasFile('image')){
            $file_name = $this->saveImage($request->image,'assets/uploads/representatives');
            $data['image'] = 'assets/uploads/representatives/'.$file_name;
        }
        $rev->update($data);

        $data = $rev;

        $data['provider_code'] = (string)($provider->id * 1515) ?? null;
        $data['provider_id'] = (int)$data['provider_id'];
        

        return $this->returnData('data',$rev,'تم تحديث البيانات بنجاح');
    }

    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'representative_id' => 'required|exists:representatives,id',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,406);
        }

        Token::where('token',$request->token)->delete();
        return $this->returnData('data',null,'done');
    }//end fun

}
