<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    protected $table = "accounting";

    public static $addiction = 'addiction';
    public static $deduction = 'deduction';

    public static $asset = 'asset';
    public static $income = 'income';
    public static $subscribe = 'subscribe';
    public static $promotion = 'promotion';
    public static $storeManual = 'manual';
    public static $storeAutomatic = 'automatic';
    public static $registrationPackage = 'registration_package';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\Bundle', 'bundle_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function promotion()
    {
        return $this->belongsTo('App\Models\Promotion', 'promotion_id', 'id');
    }

    public function registrationPackage()
    {
        return $this->belongsTo('App\Models\RegistrationPackage', 'registration_package_id', 'id');
    }

    public function subscribe()
    {
        return $this->belongsTo('App\Models\Subscribe', 'subscribe_id', 'id');
    }

    public function meetingTime()
    {
        return $this->belongsTo('App\Models\MeetingTime', 'meeting_time_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }


    public static function createAccounting($orderItem, $type = null)
    {
        self::createAccountingBuyer($orderItem, $type);

        if ($orderItem->tax_price) {
            self::createAccountingTax($orderItem);
        }

        self::createAccountingSeller($orderItem);

        if ($orderItem->commission_price) {
            self::createAccountingCommission($orderItem);
        }
    }

    public static function createAccountingBuyer($orderItem, $type = null)
    {
        if ($type !== 'credit') {
            Accounting::create([
                'user_id' => $orderItem->user_id,
                'amount' => $orderItem->total_amount,
                'webinar_id' => !empty($orderItem->webinar_id) ? $orderItem->webinar_id : null,
                'bundle_id' => !empty($orderItem->bundle_id) ? $orderItem->bundle_id : null,
                'meeting_time_id' => $orderItem->reserveMeeting ? $orderItem->reserveMeeting->meeting_time_id : null,
                'subscribe_id' => $orderItem->subscribe_id ?? null,
                'promotion_id' => $orderItem->promotion_id ?? null,
                'registration_package_id' => $orderItem->registration_package_id ?? null,
                'product_id' => $orderItem->product_id ?? null,
                'type' => Accounting::$addiction,
                'type_account' => Accounting::$asset,
                'description' => trans('public.paid_for_sale'),
                'created_at' => time()
            ]);
        }

        $deductionDescription = trans('public.paid_form_online_payment');
        if (!empty($orderItem->reserveMeeting)) {
            $time = $orderItem->reserveMeeting->meetingTime->time;
            $explodeTime = explode('-', $time);
            $minute = (strtotime($explodeTime[1]) - strtotime($explodeTime[0])) / 60;

            $deductionDescription = trans('meeting.paid_for_x_hour', ['hours' => convertMinutesToHourAndMinute($minute)]);
        } elseif ($type == 'credit') {
            $deductionDescription = trans('public.paid_form_credit');
        }

        Accounting::create([
            'user_id' => $orderItem->user_id,
            'amount' => $orderItem->total_amount,
            'webinar_id' => !empty($orderItem->webinar_id) ? $orderItem->webinar_id : null,
            'bundle_id' => !empty($orderItem->bundle_id) ? $orderItem->bundle_id : null,
            'meeting_time_id' => $orderItem->reserveMeeting ? $orderItem->reserveMeeting->meeting_time_id : null,
            'subscribe_id' => $orderItem->subscribe_id ?? null,
            'promotion_id' => $orderItem->promotion_id ?? null,
            'registration_package_id' => $orderItem->registration_package_id ?? null,
            'product_id' => $orderItem->product_id ?? null,
            'type' => Accounting::$deduction,
            'type_account' => Accounting::$asset,
            'description' => $deductionDescription,
            'created_at' => time()
        ]);

        $notifyOptions = [
            '[f.d.type]' => Accounting::$deduction,
            '[amount]' => $orderItem->total_amount,
        ];

        if (!empty($orderItem->webinar_id)) {
            $notifyOptions['[c.title]'] = $orderItem->webinar->title;
        } elseif (!empty($orderItem->bundle_id)) {
            $notifyOptions['[c.title]'] = $orderItem->bundle->title;
        } elseif (!empty($orderItem->reserve_meeting_id)) {
            $notifyOptions['[c.title]'] = trans('meeting.reservation_appointment');
        } elseif (!empty($orderItem->product_id)) {
            $notifyOptions['[c.title]'] = $orderItem->product->title;
        }

        sendNotification('new_financial_document', $notifyOptions, $orderItem->user_id);
    }

    public static function createAccountingTax($orderItem)
    {
        Accounting::create([
            'user_id' => $orderItem->user_id,
            'tax' => true,
            'amount' => $orderItem->tax_price,
            'webinar_id' => $orderItem->webinar_id,
            'bundle_id' => $orderItem->bundle_id,
            'meeting_time_id' => $orderItem->reserveMeeting ? $orderItem->reserveMeeting->meeting_time_id : null,
            'subscribe_id' => $orderItem->subscribe_id ?? null,
            'promotion_id' => $orderItem->promotion_id ?? null,
            'registration_package_id' => $orderItem->registration_package_id ?? null,
            'product_id' => $orderItem->product_id ?? null,
            'type_account' => Accounting::$asset,
            'type' => Accounting::$addiction,
            'description' => trans('public.tax_get_form_buyer'),
            'created_at' => time()
        ]);

        return true;
    }

    public static function createAccountingSeller($orderItem)
    {
        $sellerId = OrderItem::getSeller($orderItem);

        Accounting::create([
            'user_id' => $sellerId,
            'amount' => $orderItem->total_amount - $orderItem->tax_price - $orderItem->commission_price,
            'webinar_id' => $orderItem->webinar_id,
            'bundle_id' => $orderItem->bundle_id,
            'meeting_time_id' => $orderItem->reserveMeeting ? $orderItem->reserveMeeting->meeting_time_id : null,
            'subscribe_id' => $orderItem->subscribe_id ?? null,
            'promotion_id' => $orderItem->promotion_id ?? null,
            'product_id' => $orderItem->product_id ?? null,
            'type_account' => Accounting::$income,
            'type' => Accounting::$addiction,
            'description' => trans('public.income_sale'),
            'created_at' => time()
        ]);

        return true;
    }

    public static function createAccountingSystemForSubscribe($orderItem)
    {
        $sellerId = OrderItem::getSeller($orderItem);

        Accounting::create([
            'user_id' => $sellerId,
            'system' => true,
            'amount' => $orderItem->total_amount - $orderItem->tax_price,
            'subscribe_id' => $orderItem->subscribe_id,
            'type_account' => Accounting::$subscribe,
            'type' => Accounting::$addiction,
            'description' => trans('public.income_for_subscribe'),
            'created_at' => time()
        ]);

        return true;
    }

    public static function createAccountingCommission($orderItem)
    {
        $authId = auth()->id();
        $sellerId = OrderItem::getSeller($orderItem);

        $commissionPrice = $orderItem->commission_price;
        $affiliateCommissionPrice = 0;


        $referralSettings = getReferralSettings();
        $affiliateStatus = (!empty($referralSettings) and !empty($referralSettings['status']));
        $affiliateUser = null;

        if ($affiliateStatus) {
            $affiliate = Affiliate::where('referred_user_id', $authId)->first();

            if (!empty($affiliate)) {
                $affiliateUser = $affiliate->affiliateUser;

                if (!empty($affiliateUser) and $affiliateUser->affiliate) {

                    if (!empty($affiliate)) {
                        if (!empty($orderItem->product_id) and !empty($referralSettings['store_affiliate_user_commission']) and $referralSettings['store_affiliate_user_commission'] > 0) {
                            $affiliateCommission = $referralSettings['store_affiliate_user_commission'];

                            $commission = $affiliateUser->getCommission();

                            if ($commission > 0) {
                                $affiliateCommissionPrice = ($affiliateCommission * $commissionPrice) / $commission;
                                $commissionPrice = $commissionPrice - $affiliateCommissionPrice;
                            }
                        } elseif (empty($orderItem->product_id) and !empty($referralSettings['affiliate_user_commission']) and $referralSettings['affiliate_user_commission'] > 0) {
                            $affiliateCommission = $referralSettings['affiliate_user_commission'];

                            $commission = $affiliateUser->getCommission();

                            if ($commission > 0) {
                                $affiliateCommissionPrice = ($affiliateCommission * $commissionPrice) / $commission;
                                $commissionPrice = $commissionPrice - $affiliateCommissionPrice;
                            }
                        }
                    }
                }
            }
        }

        Accounting::create([
            'user_id' => !empty($sellerId) ? $sellerId : 1,
            'system' => true,
            'amount' => $commissionPrice,
            'webinar_id' => $orderItem->webinar_id ?? null,
            'bundle_id' => $orderItem->bundle_id ?? null,
            'product_id' => $orderItem->product_id ?? null,
            'meeting_time_id' => $orderItem->reserveMeeting ? $orderItem->reserveMeeting->meeting_time_id : null,
            'subscribe_id' => $orderItem->subscribe_id ?? null,
            'promotion_id' => $orderItem->promotion_id ?? null,
            'type_account' => Accounting::$income,
            'type' => Accounting::$addiction,
            'description' => trans('public.get_commission_from_seller'),
            'created_at' => time()
        ]);

        if (!empty($affiliateUser) and $affiliateCommissionPrice > 0) {
            Accounting::create([
                'user_id' => $affiliateUser->id,
                'system' => false,
                'referred_user_id' => $authId,
                'is_affiliate_commission' => true,
                'amount' => $affiliateCommissionPrice,
                'webinar_id' => $orderItem->webinar_id ?? null,
                'bundle_id' => $orderItem->bundle_id ?? null,
                'product_id' => $orderItem->product_id ?? null,
                'meeting_time_id' => $orderItem->reserveMeeting ? $orderItem->reserveMeeting->meeting_time_id : null,
                'subscribe_id' => null,
                'promotion_id' => null,
                'type_account' => Accounting::$income,
                'type' => Accounting::$addiction,
                'description' => trans('public.get_commission_from_referral'),
                'created_at' => time()
            ]);
        }

        return true;
    }

    public static function createAffiliateUserAmountAccounting($userId, $referredUserId, $amount)
    {
        if ($amount) {
            Accounting::create([
                'user_id' => $userId,
                'referred_user_id' => $referredUserId,
                'is_affiliate_amount' => true,
                'system' => false,
                'amount' => $amount,
                'webinar_id' => null,
                'bundle_id' => null,
                'meeting_time_id' => null,
                'subscribe_id' => null,
                'promotion_id' => null,
                'type_account' => Accounting::$income,
                'type' => Accounting::$addiction,
                'description' => trans('public.get_amount_from_referral'),
                'created_at' => time()
            ]);

            Accounting::create([
                'user_id' => $userId,
                'referred_user_id' => $referredUserId,
                'is_affiliate_amount' => true,
                'system' => true,
                'amount' => $amount,
                'webinar_id' => null,
                'bundle_id' => null,
                'meeting_time_id' => null,
                'subscribe_id' => null,
                'promotion_id' => null,
                'type_account' => Accounting::$income,
                'type' => Accounting::$deduction,
                'description' => trans('public.get_amount_from_referral'),
                'created_at' => time()
            ]);
        }
    }


    public static function refundAccounting($sale, $productOrderId = null)
    {
        self::refundAccountingBuyer($sale);

        if ($sale->tax) {
            self::refundAccountingTax($sale);
        }

        self::refundAccountingSeller($sale);

        if ($sale->commission) {
            self::refundAccountingCommission($sale);
        }
    }

    public static function refundAccountingBuyer($sale)
    {
        Accounting::create([
            'user_id' => $sale->buyer_id,
            'amount' => $sale->total_amount,
            'webinar_id' => $sale->webinar_id,
            'bundle_id' => $sale->bundle_id,
            'meeting_time_id' => $sale->meeting_time_id,
            'subscribe_id' => $sale->subscribe_id ?? null,
            'promotion_id' => $sale->promotion_id ?? null,
            'product_id' => !empty($sale->productOrder) ? $sale->productOrder->product_id : null,
            'type' => Accounting::$addiction,
            'type_account' => Accounting::$asset,
            'description' => trans('public.refund_money_to_buyer'),
            'created_at' => time()
        ]);

        return true;
    }

    public static function refundAccountingTax($sale)
    {
        Accounting::create([
            'tax' => true,
            'amount' => $sale->tax,
            'webinar_id' => $sale->webinar_id,
            'bundle_id' => $sale->bundle_id,
            'meeting_time_id' => $sale->meeting_time_id,
            'subscribe_id' => $sale->subscribe_id ?? null,
            'promotion_id' => $sale->promotion_id ?? null,
            'product_id' => !empty($sale->productOrder) ? $sale->productOrder->product_id : null,
            'type_account' => Accounting::$asset,
            'type' => Accounting::$deduction,
            'description' => trans('public.refund_tax'),
            'created_at' => time()
        ]);

        return true;
    }

    public static function refundAccountingCommission($sale)
    {
        if (!empty($sale->commission)) {
            Accounting::create([
                'user_id' => $sale->seller_id,
                'amount' => $sale->commission,
                'webinar_id' => $sale->webinar_id,
                'bundle_id' => $sale->bundle_id,
                'meeting_time_id' => $sale->meeting_time_id,
                'subscribe_id' => $sale->subscribe_id ?? null,
                'promotion_id' => $sale->promotion_id ?? null,
                'product_id' => !empty($sale->productOrder) ? $sale->productOrder->product_id : null,

                'type_account' => Accounting::$income,
                'type' => Accounting::$addiction,
                'description' => trans('public.refund_commission_to_seller'),
                'created_at' => time()
            ]);

            Accounting::create([
                'system' => true,
                'user_id' => $sale->seller_id,
                'amount' => $sale->total_amount,
                'webinar_id' => $sale->webinar_id,
                'bundle_id' => $sale->bundle_id,
                'meeting_time_id' => $sale->meeting_time_id,
                'subscribe_id' => $sale->subscribe_id ?? null,
                'promotion_id' => $sale->promotion_id ?? null,
                'product_id' => !empty($sale->productOrder) ? $sale->productOrder->product_id : null,
                'type_account' => Accounting::$income,
                'type' => Accounting::$deduction,
                'description' => trans('public.refund_commission'),
                'created_at' => time()
            ]);
        }

        return true;
    }

    public static function refundAccountingSeller($sale)
    {
        Accounting::create([
            'user_id' => $sale->seller_id,
            'amount' => $sale->total_amount,
            'webinar_id' => $sale->webinar_id,
            'bundle_id' => $sale->bundle_id,
            'meeting_time_id' => $sale->meeting_time_id,
            'subscribe_id' => $sale->subscribe_id ?? null,
            'promotion_id' => $sale->promotion_id ?? null,
            'product_id' => !empty($sale->productOrder) ? $sale->productOrder->product_id : null,
            'type_account' => Accounting::$income,
            'type' => Accounting::$deduction,
            'description' => trans('public.refund_income'),
            'created_at' => time()
        ]);

        return true;
    }

    public static function charge($order)
    {
        Accounting::create([
            'user_id' => $order->user_id,
            'amount' => $order->total_amount,
            'type_account' => Order::$asset,
            'type' => Order::$addiction,
            'description' => trans('public.charge_account'),
            'created_at' => time()
        ]);

        $accountChargeReward = RewardAccounting::calculateScore(Reward::ACCOUNT_CHARGE, $order->total_amount);
        RewardAccounting::makeRewardAccounting($order->user_id, $accountChargeReward, Reward::ACCOUNT_CHARGE);

        $chargeWalletReward = RewardAccounting::calculateScore(Reward::CHARGE_WALLET, $order->total_amount);
        RewardAccounting::makeRewardAccounting($order->user_id, $chargeWalletReward, Reward::CHARGE_WALLET);

        return true;
    }


    public static function createAccountingForSubscribe($orderItem, $type = null)
    {
        self::createAccountingBuyer($orderItem, $type);
        if ($orderItem->tax_price) {
            self::createAccountingTax($orderItem);
        }

        self::createAccountingSystemForSubscribe($orderItem);

        $notifyOptions = [
            '[u.name]' => $orderItem->user->full_name,
            '[s.p.name]' => $orderItem->subscribe->title,
        ];

        sendNotification('new_subscribe_plan', $notifyOptions, $orderItem->user_id);
    }

    public static function createAccountingForPromotion($orderItem, $type = null)
    {
        self::createAccountingBuyer($orderItem, $type);

        if ($orderItem->tax_price) {
            self::createAccountingTax($orderItem);
        }

        self::createAccountingSystemForPromotion($orderItem);

        $notifyOptions = [
            '[c.title]' => $orderItem->webinar->title,
            '[p.p.name]' => $orderItem->promotion->title,
        ];

        sendNotification('promotion_plan', $notifyOptions, $orderItem->user_id);
    }


    public static function createAccountingSystemForPromotion($orderItem)
    {
        Accounting::create([
            'user_id' => $orderItem->webinar_id ? $orderItem->webinar->creator_id : (!empty($orderItem->reserve_meeting_id) ? $orderItem->reserveMeeting->meeting->creator_id : 1),
            'system' => true,
            'amount' => $orderItem->total_amount - $orderItem->tax_price,
            'promotion_id' => $orderItem->promotion_id,
            'type_account' => Accounting::$promotion,
            'type' => Accounting::$addiction,
            'description' => trans('public.income_for_promotion'),
            'created_at' => time()
        ]);
    }

    public static function createAccountingForRegistrationPackage($orderItem, $type = null)
    {
        self::createAccountingBuyer($orderItem, $type);

        if ($orderItem->tax_price) {
            self::createAccountingTax($orderItem);
        }

        self::createAccountingSystemForRegistrationPackage($orderItem);
    }

    public static function createAccountingSystemForRegistrationPackage($orderItem)
    {
        Accounting::create([
            'user_id' => 1,
            'system' => true,
            'amount' => $orderItem->total_amount - $orderItem->tax_price,
            'registration_package_id' => $orderItem->registration_package_id,
            'type_account' => Accounting::$registrationPackage,
            'type' => Accounting::$addiction,
            'description' => trans('update.paid_for_registration_package'),
            'created_at' => time()
        ]);
    }

    public static function createAccountingForSaleWithSubscribe($item, $subscribe,$itemName)
    {
        $admin = User::getAdmin();

        $commission = $item->creator->getCommission();

        $pricePerSubscribe = $subscribe->price / $subscribe->usable_count;
        $commissionPrice = $commission ? ($pricePerSubscribe * $commission / 100) : 0;

        $totalAmount = $pricePerSubscribe - $commissionPrice;

        Accounting::create([
            'user_id' => $item->creator_id,
            'amount' => $totalAmount,
            $itemName => $item->id,
            'type' => Accounting::$addiction,
            'type_account' => Accounting::$income,
            'description' => trans('public.paid_form_subscribe'),
            'created_at' => time()
        ]);

        Accounting::create([
            'system' => true,
            'user_id' => $admin->id,
            'amount' => $totalAmount,
            $itemName => $item->id,
            'type' => Accounting::$deduction,
            'type_account' => Accounting::$asset,
            'description' => trans('public.paid_form_subscribe'),
            'created_at' => time()
        ]);
    }

    public static function refundAccountingForSaleWithSubscribe($webinar, $subscribe)
    {
        $admin = User::getAdmin();

        $financialSettings = getFinancialSettings();
        $commission = $financialSettings['commission'] ?? 0;

        $pricePerSubscribe = $subscribe->price / $subscribe->usable_count;
        $commissionPrice = $commission ? $pricePerSubscribe * $commission / 100 : 0;

        $totalAmount = $pricePerSubscribe - $commissionPrice;

        Accounting::create([
            'user_id' => $webinar->creator_id,
            'amount' => $totalAmount,
            'webinar_id' => $webinar->id,
            'type' => Accounting::$deduction,
            'type_account' => Accounting::$income,
            'description' => trans('public.paid_form_subscribe'),
            'created_at' => time()
        ]);

        Accounting::create([
            'system' => true,
            'user_id' => $admin->id,
            'amount' => $totalAmount,
            'webinar_id' => $webinar->id,
            'type' => Accounting::$addiction,
            'type_account' => Accounting::$asset,
            'description' => trans('public.paid_form_subscribe'),
            'created_at' => time()
        ]);
    }
}
