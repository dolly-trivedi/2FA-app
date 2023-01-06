<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Exception;
use Twilio\Rest\Client;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateCode()
    {
        $code = rand(1000, 9999);
        // dd(auth()->user()->phone);
        $data = array(
            'user_id' => auth()->user()->id,
            'phone' => auth()->user()->phone,
            'code' => $code
        );
        UserCode::updateOrCreate($data);
  
        $receiverNumber = "+91 ".auth()->user()->phone;
        $message = "2FA login code is ". $code;
    
        try {
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");
    
            $client = new Client($account_sid,$auth_token);
            $client->messages->create($receiverNumber,array(  
                "messagingServiceSid" => "MG26cad69ee26ecb5e9df1d61790a935e0",      
                "body" => $message
             ));
    
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
        }
    }
}
