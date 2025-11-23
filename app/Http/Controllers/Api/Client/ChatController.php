<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Representative;
use App\Models\Room;
use App\Models\RoomMessages;
use App\Models\Token;
use App\Traits\GeneralTrait;
use App\Traits\NotificationTrait;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    use GeneralTrait,PhotoTrait,NotificationTrait;
    public  function getChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'          => 'required|exists:orders,id',
            'user_id'           => 'nullable|exists:users,id',
            'representative_id' => 'nullable|exists:representatives,id',
            'provider_id'       => 'nullable|exists:providers,id',
        ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,405);
        }
        if($request->representative_id != null && $request->provider_id != null && $request->user_id != null ){
            return $this->returnError('ER','يرجي ارسال حقلين');
        }
        elseif($request->representative_id == null && $request->provider_id == null && $request->user_id == null ){
            return $this->returnError('ER','يرجي ارسال حقلين');
        }

        $data['data'] = Order::with('user','provider')->findOrFail($request->order_id);
        $data['messages'] = RoomMessages::where($request->only('order_id','provider_id','user_id','representative_id'))->get();
        return $this->returnData('data', $data,200);
    }//end fun

    public function storeMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'          => 'required|exists:orders,id',
            'type'              => 'required|in:file,text',
            'representative_id' => 'nullable|exists:representatives,id',
            'provider_id'       => 'nullable|exists:providers,id',
            'user_id'           => 'nullable|exists:users,id',
            'from_type'         => 'required|in:user,provider,representative',
            'message'           => 'required_if:type,text',
            'file'              => 'required_if:type,file',
            ]);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator,405);
        }
        if($request->representative_id != null && $request->provider_id != null && $request->user_id != null ){
            return $this->returnError('ER','يرجي ارسال حقلين');
        }
        elseif($request->representative_id == null && $request->provider_id == null && $request->user_id == null ){
            return $this->returnError('ER','يرجي ارسال حقلين');
        }
        $order = Order::find($request->order_id);
        $message = new RoomMessages();
        $message->order_id          = $order->id;  // room id
        $message->user_id           = $request->user_id;  // user id
        $message->provider_id       = $request->provider_id;  // provider id
        $message->representative_id = $request->representative_id;
        $message->from_type         = $request->from_type;  // from
        $message->type              = $request->type;  // type

        if ($request->type == 'text') {
            $message->message = $request->message;
        } else {
            $message->file = 'assets/uploads/chat/'.$this->saveImage($request->file,'assets/uploads/chat');
        }
        $message->save();


        $message = RoomMessages::findOrFail($message->id);
        $message['representative'] = Representative::find($request->representative_id);
        $message->notification_type = 'chat';

        if ($message->from_type == 'user'){
//            dd($message,$request->all());
            $this->sendChatNotification($message,null,$request->provider_id,$request->representative_id);
        }
        elseif ($message->from_type == 'provider'){
//            dd($message,$request->all());
            $this->sendChatNotification($message,$request->user_id,null,$request->representative_id);
        }
        elseif ($message->from_type == 'representative'){
//            dd($message,$request->all());
            $this->sendChatNotification($message,$request->user_id,$request->provider_id,null);
        }

        return $this->returnData('data', $message, 'تم ارسال الرسالة بنجاح', 200);


    }//end fun
}//end class
