<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Models\Api\Payout;
use Illuminate\Http\Request;

class PayoutsController extends Controller
{
    public function index(Request $request)
    {
        $user = apiAuth();
        $payouts = Payout::where('user_id', $user->id)
            ->where('status',Payout::$done)
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

            $getFinancialSettings = getFinancialSettings();

        return apiResponse2(1, 'retrieved', trans('public.retrieved'), [
            'payouts' => $payouts->map(function($payout){
                return $payout->details ;
            }),
            'current_payout' => [
                'amount'=> $user->getPayout() ,
                'account_type'=> $user->account_type ,
                'account_id'=> $user->account_id ,
                'iban'=> $user->iban ,
                'minimum_payout'=>$getFinancialSettings['minimum_payout'] ,
                'identity'=>($user->iban && $user->account_type ) ,

                'account_charge' => $user->getAccountingCharge(),
                'total_income' => $user->getIncome(),
               // 'card_id'=> $user->card_id ,
            ],

        ]);

        }

    public function requestPayout()
    {
        $user = apiAuth();
        $getUserPayout = $user->getPayout();
        $getFinancialSettings = getFinancialSettings();

        if ($getUserPayout < $getFinancialSettings['minimum_payout']) {
            return apiResponse2(0, 'minimum_payout',
                trans('public.income_los_then_minimum_payout'),
            null,
                trans('public.request_failed')
            );
        }

        if (!empty($user->iban) and !empty($user->account_type)) {
            Payout::create([
                'user_id' => $user->id,
                'amount' => $getUserPayout,
                'account_name' => $user->full_name,
                'account_number' => $user->iban,
                'account_bank_name' => $user->account_type,
                'status' => Payout::$waiting,
                'created_at' => time(),
            ]);

            $notifyOptions = [
                '[payout.amount]' => $getUserPayout,
                '[u.name]' => $user->full_name
            ];

            sendNotification('payout_request', $notifyOptions, $user->id);
            sendNotification('payout_request_admin', $notifyOptions, 1); // for admin

            return apiResponse2(1, 'stored', trans('api.public.stored'));

        }


        return apiResponse2(0, 'identity_settings',
            trans('site.check_identity_settings'),
        null,
            trans('public.request_failed')

        );
    }
}
