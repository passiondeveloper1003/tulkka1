<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserSubscription;
use App\Payment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Log;

class PaypalPaymentController extends Controller
{
  public $paypalClient;
  public function __construct()
  {

    $this->paypalClient = new PayPalClient;
    $this->paypalClient->setApiCredentials(config('paypal'));
      $token = $this->paypalClient->getAccessToken();
      $this->paypalClient->setAccessToken($token);

  }

  public function create(Request $request)
  {
      $data = json_decode($request->getContent(), true);



      $order = $this->paypalClient->createOrder([
          "intent"=> "CAPTURE",
          "purchase_units"=> [
               [
                  "amount"=> [
                      "currency_code"=> "ILS",
                      "value"=> $data['amount']
                  ],
                   'description' => 'test'
              ]
          ],
      ]);

      return response()->json($order);


      //return redirect($order['links'][1]['href'])->send();
     // echo('Create working');
  }


  public function capture(Request $request)
    {
        $data = json_decode($request->getContent());
        $this->paypalClient->setApiCredentials(config('paypal'));
        $orderid = $data->vendor_order_id;
        $token = $this->paypalClient->getAccessToken();
        $this->paypalClient->setAccessToken($token);
        $result = $this->paypalClient->capturePaymentOrder($orderid);

//            $result = $result->purchase_units[0]->payments->captures[0];
        try {
            DB::beginTransaction();
            if($result['status'] === "COMPLETED"){
              $user = User::where('id',$data->user_id)->get()->first();
              if($user->subscription_type){
                return;
              }
              $user->subscription_type = $data->selectedPlan;
              $user->trial_expired = 1;
              $user->save();
              $user_subscription = UserSubscription::create([
                'user_id' => $user->id,
                'type' => $data->selectedPlan,
                'each_lesson' => $data->weeklyLesson,
                'renew_date' => $data->renewDate,
                'weekly_comp_class' => '0',
                'how_often' => $data->weeklyHour,
              ]);
              $data = (array) $data;
              $mergeData = array_merge($data,['subscription_id' => $user_subscription->id,'payment_channel'=>'paypal' ,'status' => 'completed', 'vendor_order_id' => $data['vendor_order_id'],'created_at' => Carbon::now() ]);
              Payment::create($mergeData);
                DB::commit();
            }
        } catch (Exception $e) {
            Log::debug(json_encode($e));
            DB::rollBack();
        }
        return response()->json($result);
    }

}
