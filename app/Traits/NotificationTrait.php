<?php

namespace App\Traits;

use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderRepresentative;
use App\Models\Provider;
use App\Models\Representative;
use App\Models\RoomMessages;
use App\Models\User;
use App\Models\Token;
use App\Models\Notification as NotificationModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

//Trait  NotificationTrait
//{
//    private $serverKey = 'AAAAzsc5O8E:APA91bH5bELhMTm9ru_lVm_GrFfq0jjIakeGXuF8UtvhGkgtSAZpTIqBM4lCAL5H-Vue5y8bcOHvMlY932uiIGwFytg0VBG99n5w9g91A_WPPB7TbwWJ9ZmFR5DC1L8j-8FE5FJ46EQo';
//
//    public function sendBasicNotification($title,$body,$order_id, $user_id=null,$provider_id = null,$type = null,$representative_id = null,$rev_id = null)
//    {
//        $url = 'https://fcm.googleapis.com/fcm/send';
//        $order = Order::find($order_id);
//
//        $storeData = [
//            'title' => $title,
//            'body' => $body,
//            'order_id' => $order_id,
//            'status' => $order->status??'',
//            'provider_id' => ($provider_id) ?? $type,
//            'user_id' => $user_id,
//            'representative_id' => $rev_id,
//        ];
//
//        NotificationModel::create($storeData);
//
//        if ($provider_id == null)
//        {
//            if ($order->status == 'new' || $order->status == 'offered'){
//                $provider = Provider::find($type);
//            }
//            else
//                $provider = Provider::find($order->provider_id);
//        }else{
//            $provider = Provider::find($provider_id);
//        }
//
//
//
//        $data = [
//            'title' => $title,
//            'body' => $body,
//            'status' => $order->status??'',
//            'order_id' => $order_id,
//            'provider' => $provider,
//            'user' => User::find($order->user_id),
//            'representative' => Representative::find($rev_id),
//            'notification_type'=>'basic',
//        ];
////        return $data;
//
//
////        $query['user_id'] = $user_id;
////        $query['provider_id'] = $provider_id;
////        $query['representative_id'] = $representative_id;
//
//        $tokens = [];
//        if($user_id)
//            $tokens = Token::where('user_id',$user_id)->pluck('token')->toArray();
//        if($provider_id)
//            $tokens = array_merge($tokens,Token::where('provider_id',$provider_id)->pluck('token')->toArray());
//        if($representative_id)
//            $tokens = array_merge($tokens,Token::where('representative_id',$representative_id)->pluck('token')->toArray());
//
////        return Token::where('provider_id',$provider_id)->pluck('token')->toArray();
//        $fields = array(
//            'registration_ids' => $tokens,
//            'data' => $data,
//            'notification' =>$data,
//        );
//        $headers = array(
//            'Authorization: key=' . $this->serverKey,
//            'Content-Type: application/json'
//        );
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//        $result = curl_exec($ch);
//        if ($result === FALSE) {
//            die('Curl failed: ' . curl_error($ch));
//        }
//        curl_close($ch);
//        return $result;
//    }
//
//    public function sendChatNotification($data, $user_id=null,$provider_id = null,$representative_id = null)
//    {
//        $url = 'https://fcm.googleapis.com/fcm/send';
//
//        $query['user_id'] = $user_id;
//        $query['provider_id'] = $provider_id;
//        $query['representative_id'] = $representative_id;
//
////        return $query;
//        $tokens = Token::where($query)->pluck('token')->toArray();
//        $fields = array(
//            'registration_ids' => $tokens,
//            'data' => $data,
//            'notification' =>$data,
//        );
//        $fields = json_encode($fields);
//
//        $headers = array(
//            'Authorization: key=' . $this->serverKey,
//            'Content-Type: application/json'
//        );
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//        $result = curl_exec($ch);
//        if ($result === FALSE) {
//            die('Curl failed: ' . curl_error($ch));
//        }
//        curl_close($ch);
//        return $result;
//    }
//
//}//end trait




trait NotificationTrait
{
    public function sendBasicNotification($title, $body, $order_id, $user_id = null, $provider_id = null, $type = null, $representative_id = null, $rev_id = null)
    {
        // Store notification in database
        $order=Order::where('id',$order_id)->first();
        $storeData = [
            'title' => $title,
            'body' => $body,
            'order_id' => $order_id,
            'status' => $order->status ?? '',
            'provider_id' => ($provider_id) ?? $type,
            'user_id' => $user_id,
            'representative_id' => $rev_id,
        ];

        NotificationModel::create($storeData);

        // Get provider information
        if ($provider_id == null) {
            if ($order->status == 'new' || $order->status == 'offered') {
                $provider = Provider::find($type);
            } else {
                $provider = Provider::find($order->provider_id);
            }
        } else {
            $provider = Provider::find($provider_id);
        }

        // Prepare notification data
        $data = [
            'title' => $title,
            'body' => $body,
            'status' => $order->status ?? '',
            'order_id' => (string)$order_id,
            'provider_id' => $provider ? (string)$provider->id : '',
            'user_id' => $user_id ? (string)$user_id : '',
            'representative_id' => $rev_id ? (string)$rev_id : '',
            'notification_type' => 'basic',
        ];

        // Get device tokens based on recipients
        $tokens = [];

        if ($user_id) {
            $userTokens = Token::where('user_id', $user_id)->pluck('token')->toArray();
            $tokens = array_merge($tokens, $userTokens);
        }

        if ($provider_id) {
            $providerTokens = Token::where('provider_id', $provider_id)->pluck('token')->toArray();
            $tokens = array_merge($tokens, $providerTokens);
        }

        if ($representative_id) {
            $representativeTokens = Token::where('representative_id', $representative_id)->pluck('token')->toArray();
            $tokens = array_merge($tokens, $representativeTokens);
        }

        // Remove duplicate tokens
        $tokens = array_unique($tokens);

        if (empty($tokens)) {
            return ['error' => 'No device tokens found'];
        }

        // Use the new FCM v1 API
        $apiUrl = $this->fcmUrl();
        $accessToken = $this->getAccessToken();

        $responses = [];
        foreach ($tokens as $token) {
            $payload = $this->preparePayload([
                'title' => $title,
                'body' => $body
            ], $token, $data);

            $responses[] = $this->sendNotification($apiUrl, $accessToken, $payload);
        }

        return ['responses' => $responses, 'tokens_sent' => count($tokens)];
    }
//    public function sendChatNotification($data, $user_id = null, $provider_id = null, $representative_id = null)
    public function sendChatNotification($data, $user_id=null, $provider_id = null, $representative_id = null)
    {
        // Start with a base query
        $tokenQuery = Token::query();

        // Add OR conditions for each non-null parameter
        $tokenQuery->where(function ($query) use ($user_id, $provider_id, $representative_id) {
            if ($user_id !== null) {
                $query->orWhere('user_id', $user_id);
                Log::info("Added user_id condition: " . $user_id);
            }

            if ($provider_id !== null) {
                $query->orWhere('provider_id', $provider_id);
                Log::info("Added provider_id condition: " . $provider_id);
            }

            if ($representative_id !== null) {
                $query->orWhere('representative_id', $representative_id);
                Log::info("Added representative_id condition: " . $representative_id);
            }
        });

        // If all parameters are null, return early
        if ($user_id === null && $provider_id === null && $representative_id === null) {
            return ['error' => 'No search criteria provided'];
        }

        Log::info("Final query: " . $tokenQuery->toSql());
        Log::info("Query bindings: " . json_encode($tokenQuery->getBindings()));

        $tokens = $tokenQuery->pluck('token')->toArray();
        Log::info("Found tokens: " . count($tokens));



//        $records = $tokenQuery->get();
//        Log::info("Records found: " . $records->count());
//        Log::info("Records data: " . json_encode($records->toArray()));
//
//// Check specifically the token column values
//        foreach ($records as $record) {
//            Log::info("Record ID: {$record->id}, Token: " . ($record->token ?? 'NULL'));
//        }



//dd($tokens);
        if (empty($tokens)) {
            return ['error' => 'No device tokens found for the specified criteria'];
        }



//        Log::info("Final query: " . $tokenQuery->toSql());
//        Log::info("Query bindings: " . json_encode($tokenQuery->getBindings()));
//
//        $tokens = $tokenQuery->pluck('token')->toArray();
//        Log::info("Found tokens: " . count($tokens));
//
//        if (empty($tokens)) {
//            return ['error' => 'No device tokens found for the specified criteria'];
//        }

        // Ensure data is properly formatted for FCM v1 API
        $notificationData = $data;

        // Ensure all values are strings (FCM requires string values)
        if (is_array($notificationData)) {
            array_walk_recursive($notificationData, function (&$item) {
                $item = (string)$item;
            });
        }

        // Fix reserved keywords (like 'from')
        if (isset($notificationData['from'])) {
            $notificationData['from_custom'] = $notificationData['from'];
            unset($notificationData['from']);
        }

        // Use the new FCM v1 API
        $apiUrl = $this->fcmUrl();
        $accessToken = $this->getAccessToken();

        $responses = [];
        foreach ($tokens as $token) {
            // Prepare notification payload
            $notificationPayload = [
                'title' => $data['title'] ?? 'New Message',
                'body' => $data['body'] ?? $data['message'] ?? 'You have a new message',
            ];

            $payload = $this->preparePayload($notificationPayload, $token, $notificationData);
            $responses[] = $this->sendNotification($apiUrl, $accessToken, $payload);
        }
//        dd($responses);

        //        dd($notificationData,$query,$tokens,$responses,$apiUrl,$accessToken);

        return [
            'responses' => $responses,
            'tokens_sent' => count($tokens),
            'query_conditions' => $tokenQuery
        ];

    }

// Make sure you have these methods from the FirebaseNotification trait
    protected function fcmUrl()
    {
        return "https://fcm.googleapis.com/v1/projects/dbrah-2c2fc/messages:send";
    }

    protected function getAccessToken()
    {
        // Move this file outside public directory (e.g., storage/app/firebase.json)
        $credentialsFilePath = storage_path('app/firebase.json');

        if (!file_exists($credentialsFilePath)) {
            throw new \Exception('Firebase credentials file not found');
        }

        $credentials = json_decode(file_get_contents($credentialsFilePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON in Firebase credentials');
        }

        $now = time();
        $jwtHeader = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $jwtPayload = json_encode([
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]);

        $jwtHeaderBase64 = $this->base64UrlEncode($jwtHeader);
        $jwtPayloadBase64 = $this->base64UrlEncode($jwtPayload);

        $signature = '';
        $privateKey = $credentials['private_key'];

        openssl_sign(
            "$jwtHeaderBase64.$jwtPayloadBase64",
            $signature,
            $privateKey,
            'sha256'
        );

        $jwtSignatureBase64 = $this->base64UrlEncode($signature);

        $jwt = "$jwtHeaderBase64.$jwtPayloadBase64.$jwtSignatureBase64";

        // Exchange JWT for access token
        $client = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ],
        ]);

        $tokenData = json_decode($response->getBody(), true);

        return $tokenData['access_token'];
    }

    protected function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function preparePayload($data, $token, $additionalData = [])
    {
        // Ensure additionalData is an array (not an object)
        if (is_object($additionalData) && method_exists($additionalData, 'toArray')) {
            $additionalData = $additionalData->toArray();
        } elseif (!is_array($additionalData)) {
            $additionalData = [];
        }

        // Flatten and filter the data properly
        $filteredData = $this->flattenAndFilterFcmData($additionalData);

        // Build the correct FCM payload structure
        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $data['title'] ?? 'New Message',
                    'body' => $data['body'] ?? $data['message'] ?? 'You have a new message',
                ],
                'data' => $filteredData,
            ],
        ];

        return $payload;
    }

    /**
     * Flatten and filter data for FCM - only include simple key-value pairs
     */
    private function flattenAndFilterFcmData($data, $prefix = '')
    {
        $filteredData = [];
        $reservedKeywords = [
            'id', 'from', 'to', 'data', 'notification', 'android', 'webpush',
            'apns', 'fcm_options', 'token', 'topic', 'condition', 'message'
        ];

        foreach ($data as $key => $value) {
            $fullKey = $prefix ? $prefix . '_' . $key : $key;

            // Skip reserved keywords
            if (in_array($key, $reservedKeywords)) {
                $fullKey = "custom_" . $fullKey;
            }

            if (is_array($value) || is_object($value)) {
                // If it's a nested array/object, either flatten it or skip it
                // Option 1: Flatten nested structures
                $nestedData = $this->flattenAndFilterFcmData((array)$value, $fullKey);
                $filteredData = array_merge($filteredData, $nestedData);

                // Option 2: Skip nested structures and just include a flag
                // $filteredData[$fullKey] = 'has_data';
            } else {
                // For simple values, ensure they're strings and add to filtered data
                $filteredData[$fullKey] = (string)$value;
            }
        }

        return $filteredData;
    }

    protected function sendNotification($url, $accessToken, $payload)
    {
        $client = new Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    "Authorization" => "Bearer " . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($payload), // Manually encode to JSON
            ]);

            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('FCM Error: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = $response->getBody()->getContents();
                Log::error('FCM Response Body: ' . $body);
                return ['error' => $body];
            }

            return ['error' => $e->getMessage()];
        }
    }
}






//    public function sendBasicNotification($title,$body,$order_id, $user_id=null,$provider_id = null,$type = null,$representative_id = null,$rev_id = null)
//    {
//        $url = 'https://fcm.googleapis.com/fcm/send';
//        $order = Order::find($order_id);
//
//        if ($result === FALSE) {
//            die('Curl failed: ' . curl_error($ch));
//        }
//        $storeData = [
//            'title' => $title,
//            'body' => $body,
//            'order_id' => $order_id,
//            'status' => $order->status??'',
//            'provider_id' => ($provider_id) ?? $type,
//            'user_id' => $user_id,
//            'representative_id' => $rev_id,
//        ];
//
//        NotificationModel::create($storeData);
//
//        if ($provider_id == null)
//        {
//            if ($order->status == 'new' || $order->status == 'offered'){
//                $provider = Provider::find($type);
//            }
//            else
//                $provider = Provider::find($order->provider_id);
//        }else{
//            $provider = Provider::find($provider_id);
//        }
//
//
//
//        $data = [
//            'title' => $title,
//            'body' => $body,
//            'status' => $order->status??'',
//            'order_id' => $order_id,
//            'provider' => $provider,
//            'user' => User::find($order->user_id),
//            'representative' => Representative::find($rev_id),
//            'notification_type'=>'basic',
//        ];
////        return $data;
//
//
////        $query['user_id'] = $user_id;
////        $query['provider_id'] = $provider_id;
////        $query['representative_id'] = $representative_id;
//
//        $tokens = [];
//        if($user_id)
//            $tokens = Token::where('user_id',$user_id)->pluck('token')->toArray();
//        if($provider_id)
//            $tokens = array_merge($tokens,Token::where('provider_id',$provider_id)->pluck('token')->toArray());
//        if($representative_id)
//            $tokens = array_merge($tokens,Token::where('representative_id',$representative_id)->pluck('token')->toArray());
//
////        return Token::where('provider_id',$provider_id)->pluck('token')->toArray();
//        $fields = array(
//            'registration_ids' => $tokens,
//            'data' => $data,
//            'notification' =>$data,
//        );
//        $headers = array(
//            'Authorization: key=' . $this->serverKey,
//            'Content-Type: application/json'
//        );
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//        $result = curl_exec($ch);
//        curl_close($ch);
//        return $result;
//    }
//
//    public function sendChatNotification($data, $user_id=null,$provider_id = null,$representative_id = null)
//    {
//        $url = 'https://fcm.googleapis.com/fcm/send';
//
//        $query['user_id'] = $user_id;
//        $query['provider_id'] = $provider_id;
//        $query['representative_id'] = $representative_id;
//
////        return $query;
//        $tokens = Token::where($query)->pluck('token')->toArray();
//        $fields = array(
//            'registration_ids' => $tokens,
//            'data' => $data,
//            'notification' =>$data,
//        );
//        $fields = json_encode($fields);
//
//        $headers = array(
//            'Authorization: key=' . $this->serverKey,
//            'Content-Type: application/json'
//        );
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//        $result = curl_exec($ch);
//        if ($result === FALSE) {
//            die('Curl failed: ' . curl_error($ch));
//        }
//        curl_close($ch);
//        return $result;
//    }


//}//end trait
