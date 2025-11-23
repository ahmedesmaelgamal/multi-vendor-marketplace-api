<?php

namespace App\Traits;

use App\Models\RoomMessages;
use App\Models\Token;
use App\Models\Notification as NotificationModel;

Trait  Notification
{
    private $serverKey;

    public function __construct(){
        $this->serverKey = config("firebase.firebase_server_key");
    }

    public function sendBasicNotification($title, $body, $user_id=null,$provider_id = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data = [
            'title' => $title,
            'body' => $body,
            // 'type'=>'basic notification',
        ];
        NotificationModel::create($data);



        $query['user_id'] = $user_id;
        $query['provider_id'] = $provider_id;

        $tokens = Token::where($query)->pluck('token')->toArray();

        $fields = array(
            'to' => $tokens,
            'notification' =>$data,
            'data' => $data
        );
        $headers = array(
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function sendChatNotification($data, $user_id=null,$provider_id = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $query['user_id'] = $user_id;
        $query['provider_id'] = $provider_id;

        $tokens = Token::where($query)->pluck('token')->toArray();

        $fields = array(
            'to' => $tokens,
            'notification' =>$data,
            'data' => $data
        );
        $headers = array(
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

}//end trait
