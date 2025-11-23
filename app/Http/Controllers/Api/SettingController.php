<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\DeliveryTime;
use App\Models\ProductSuggestion;
use App\Models\Setting;
use App\Traits\GeneralTrait;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    use GeneralTrait,PhotoTrait;
    public function index(){
        
        $setting =  Setting::first();
        return $this->returnData('data', $setting, 'get data successfully');
    }

    public function contact_us(request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $inputs = $request->all();
        ContactUs::create($inputs);
        return $this->returnSuccessMessage('Done successfully');
    }//end fun
    public function delivery_times(request $request)
    {
        return $this->returnData('data', DeliveryTime::latest()->get(), 'get data successfully');
    }//end fun

}//end class
