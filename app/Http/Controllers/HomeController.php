<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function savePushNotificationToken(Request $request)
    {
        dd($request->token);
        auth()->user()->update(['remember_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }
    
    public function sendPushNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('remember_token')->pluck('remember_token')->all();
          
        $SERVER_API_KEY = 'AAAAle_Izjg:APA91bFgnzVFaYkz_6wb5PxdCYqGHZ30i_jooEPkrX-egxqoacngwDmHwZSfDSTr_CDlQ_9AmxioNwjdwbbDNb0EZxdbgZEayHpdXbgghag__3zHuWPBhd54rgvmwcIXUqW__18d3j15';
  
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $dataString = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
  
        dd($response);
    }
}
