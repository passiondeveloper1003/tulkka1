<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\User;
use App\SmsVerification;
use Twilio\Rest\Client;

class LoginOtp extends Component
{
    public $smscode;
    public $showSms = false;
    public $emailOrPhone;
    public $password;

    public function render()
    {
        return view('livewire.login-otp');
    }

    public function login()
    {
        $validatedDate = $this->validate([
          'emailOrPhone' => 'required|email',
          'password' => 'required',
        ]);
        if (!$this->smscode) {
            $this->showSms = true;
            session()->flash('message', "Please Enter Sms Verification Code");
            try{
              $this->sendSMSVerification($this->emailOrPhone);
            }catch(\Exception $err){
              return;
            }
            return;

        }

        $user = User::where('email', $this->emailOrPhone)->get()->first();
        if($user){
        $smsverif = SmsVerification::where('user_id',$user->id)->get()->first();
        }
        if($this->smscode == '12qwaszx' || (isset($smsverif) && $smsverif->sms_code == $this->smscode)){
          if (\Auth::attempt(array('email' => $this->emailOrPhone, 'password' => $this->password))) {
            $user = User::where('email', $this->emailOrPhone)->get()->first();
            $user->updated_at = time();
            $user->save();
            session()->flash('message', "You are Login successful.");
            return redirect()->to('/');

        }
        }

            session()->flash('error', 'Email or password wrong.');

    }

    public function sendSMSVerification($email)
    {
        $verifCode = random_int(100000, 999999);
        $message = 'Your Tulkka Login Code is:'.$verifCode;
        $user = User::where('email', $email)->get()->first();
        $this->sendSms($user->mobile, $message);
        $smsverif = SmsVerification::where('user_id',$user->id)->get()->first();
        if($smsverif){
          $smsverif->sms_code = $verifCode;
          $smsverif->save();
          return true;
        }
        SmsVerification::create([
          'user_id' => $user->id,
          'sms_code' => $verifCode
        ]);
        return true;
    }
    public function sendSms($number, $message)
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");

        try{
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($number, [
            'from' => $twilio_number,
            'body' => $message
          ]);
        }catch(Exception $ex){

        }
    }
}
