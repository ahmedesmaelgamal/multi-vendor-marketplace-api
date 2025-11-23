<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Notification;
use App\Models\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\Address;
use App\Traits\PhotoTrait;



class AuthController extends Controller
{
    use GeneralTrait, PhotoTrait;
    public function login(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users,phone',
            'phone_code' => 'required|exists:users,phone_code',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $data = User::where($request->only(['phone', 'phone_code']))->first();
        return $this->returnData('data', $data , "login successfully");
    }//end fun
    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:users,phone',
            'phone_code' => 'required',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 406);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|unique:users,email',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 407);
        }
        $validator = Validator::make($request->all(), [
            'vat_number' => 'nullable|unique:users,vat_number',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 408);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            //            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 400);
        }

        $data = $request->all();

        if ($request->has('image')) {
            $file_name = $this->saveImage($request->image, 'assets/uploads/users');
            $data['image'] = 'assets/uploads/users/' . $file_name;
        }
        $store = User::create($data);

        return $this->returnData('data', User::find($store->id) , "register successfully");
    }//end fun
    /**
     * @param Request $request
     * @return mixed
     */
    public function getProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 406);
        }

        $data = User::where('id', $request->user_id)->first();
        $data['notification_count'] = Notification::where("user_id" , $request->user_id)->where("status" , 0)->count();
        
        return $this->returnData('data', $data);
    }//end fun
    /**
     * @param Request $request
     * @return mixed
     */
    public function address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 406);
        }
        $data = Address::where($request->only(['user_id']))->get();
        return $this->returnData('data', $data, 'done');
    } //end fun

    public function editProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'email' => "required|email|unique:users,email,{$request->user_id}",
            'image' => "nullable|image|mimes:jpeg,jpg,png,gif",
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 406);
        }
        $data = $request->except('user_id');

        if ($request->hasFile('image')) {
            $file_name = $this->saveImage($request->image, 'assets/uploads/users');
            $data['image'] = 'assets/uploads/users/' . $file_name;
        } //end fun

        User::where('id', $request->user_id)->update($data);
        return $this->returnData('data', User::where('id', $request->user_id)->first(), 'done');
    }//end fun
    /**
     * @param Request $request
     * @return mixed
     */
    public function storeAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 406);
        }

        $validator = Validator::make($request->all(), [
            'time_id' => 'required|exists:delivery_times,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 405);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'admin_name' => 'required',
            'phone' => 'required',
            'phone_code' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 400);
        }

        $data = $request->all();
        $store = Address::create($data);
        return $this->returnData('data', $store, 'done');
    } //end fun

    public function storeToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'provider_id' => 'required_if:user_id,=,null|exists:providers,id',
            'token' => 'required',
            'type' => 'required|in:android,ios',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 406);
        }
        Token::updateORcreate($request->all());
        return $this->returnData('data', null, 'done');
    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'provider_id' => 'required_if:user_id,=,null|exists:providers,id',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 406);
        }

        Token::where('token', $request->token)->delete();
        return $this->returnData('data', null, 'done');
    } //end fun

    public function getNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'provider_id' => 'required_without:user_id|exists:providers,id',
        ]);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator, 406);
        }

        $query = Notification::with(['order.user', 'representative', 'provider'])
            ->orderBy('id', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        $data = $query->get();

        Notification::whereIn('id', $data->pluck('id'))->update(['status' => 1]);
        

        return $this->returnData('data', $data, 'done');
    }
}//end class
