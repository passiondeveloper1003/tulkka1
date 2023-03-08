<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Role;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    public function stepRegister(Request $request, $step)
    {
        if ($step == 1) {
            return $this->stepOne($request);

        } elseif ($step == 2) {
            return $this->stepTwo($request);

        } elseif ($step == 3) {
            return $this->stepThree($request);
        }
        abort(404);

    }

    private function stepOne(Request $request)
    {
        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
        $data = $request->all();
        $username = $this->username();

        if ($registerMethod !== $username && $username) {
            return apiResponse2(0, 'invalid_register_method', trans('api.auth.invalid_register_method'));
        }

        $rules = [
            'country_code' => ($username == 'mobile') ? 'required' : 'nullable',
            // if the username is unique check
            //   $username => ($username == 'mobile') ? 'required|numeric|unique:users' : 'required|string|email|max:255|unique:users',
            $username => ($username == 'mobile') ? 'required|numeric' : 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
        ];

        validateParam($data, $rules);
        if ($username == 'mobile') {
            $data[$username] = ltrim($data['country_code'], '+') . ltrim($data[$username], '0');

        }
        $userCase = User::where($username, $data[$username])->first();
        if ($userCase) {
            //  $userCase->update(['password' => Hash::make($data['password'])]);
            $verificationController = new VerificationController();
            $checkConfirmed = $verificationController->checkConfirmed($userCase, $username, $data[$username]);

            if ($checkConfirmed['status'] == 'verified') {
                if ($userCase->full_name) {
                    return apiResponse2(0, 'already_registered', trans('api.auth.already_registered'));
                } else {
                    $userCase->update(['password' => Hash::make($data['password'])]);
                    return apiResponse2(0, 'go_step_3', trans('api.auth.go_step_3'), [
                        'user_id' => $userCase->id
                    ]);
                }
            } else {
                $userCase->update(['password' => Hash::make($data['password'])]);
                return apiResponse2(0, 'go_step_2', trans('api.auth.go_step_2'), [
                    'user_id' => $userCase->id
                ]);
            }

        }


        $referralSettings = getReferralSettings();
        $usersAffiliateStatus = (!empty($referralSettings) and !empty($referralSettings['users_affiliate_status']));

        $user = User::create([
            'role_name' => Role::$user,
            'role_id' => Role::getUserRoleId(),
            $username => $data[$username],
            'status' => User::$pending,
            'password' => Hash::make($data['password']),
            'affiliate' => $usersAffiliateStatus,
            'created_at' => time()
        ]);

        $verificationController = new VerificationController();
        $verificationController->checkConfirmed($user, $username, $data[$username]);


        return apiResponse2('1', 'stored', trans('api.public.stored'), [
            'user_id' => $user->id
        ]);
    }

    private function stepTwo(Request $request)
    {
        $data = $request->all();
        validateParam($data, [
            'user_id' => 'required|exists:users,id',
            //  'code'=>
        ]);

        $user = User::find($data['user_id']);
        $verificationController = new VerificationController();
        $ee = $user->email ?? $user->mobile;
        return $verificationController->confirmCode($request, $ee);
    }

    private function stepThree(Request $request)
    {
        $data = $request->all();
        validateParam($request->all(), [
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|min:3',
            'referral_code' => 'nullable|exists:affiliates_codes,code'

        ]);

        $user = User::find($request->input('user_id'));
        $user->update([
            'full_name' => $data['full_name']
        ]);
        $referralCode = $request->input('referral_code', null);
        if (!empty($referralCode)) {
            Affiliate::storeReferral($user, $referralCode);
        }
        event(new Registered($user));
        $token = auth('api')->tokenById($user->id);
        $data['token'] = $token;
        $data['user_id'] = $user->id;
        return apiResponse2(1, 'login', trans('api.auth.login'), $data);

    }

    public function username()
    {
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        $data = request()->all();

        if (empty($this->username)) {
            if (in_array('mobile', array_keys($data))) {
                $this->username = 'mobile';
            } else if (in_array('email', array_keys($data))) {
                $this->username = 'email';
            }
        }

        return $this->username ?? '';
    }


}
