<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public static $webinar = 'webinar';
    public static $meeting = 'meeting';
    public static $subscribe = 'subscribe';
    public static $promotion = 'promotion';
    public static $registrationPackage = 'registration_package';
    public static $product = 'product';
    public static $bundle = 'bundle';

    public static $credit = 'credit';
    public static $paymentChannel = 'payment_channel';

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

    public function buyer()
    {
        return $this->belongsTo('App\User', 'buyer_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo('App\User', 'seller_id', 'id');
    }

    public function meeting()
    {
        return $this->belongsTo('App\Models\Meeting', 'meeting_id', 'id');
    }

    public function subscribe()
    {
        return $this->belongsTo('App\Models\Subscribe', 'subscribe_id', 'id');
    }

    public function promotion()
    {
        return $this->belongsTo('App\Models\Promotion', 'promotion_id', 'id');
    }

    public function registrationPackage()
    {
        return $this->belongsTo('App\Models\RegistrationPackage', 'registration_package_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket', 'ticket_id', 'id');
    }

    public function saleLog()
    {
        return $this->hasOne('App\Models\SaleLog', 'sale_id', 'id');
    }

    public function productOrder()
    {
        return $this->belongsTo('App\Models\ProductOrder', 'product_order_id', 'id');
    }

    public static function createSales($orderItem, $payment_method)
    {
        $orderType = Order::$webinar;
        if (!empty($orderItem->reserve_meeting_id)) {
            $orderType = Order::$meeting;
        } elseif (!empty($orderItem->subscribe_id)) {
            $orderType = Order::$subscribe;
        } elseif (!empty($orderItem->promotion_id)) {
            $orderType = Order::$promotion;
        } elseif (!empty($orderItem->registration_package_id)) {
            $orderType = Order::$registrationPackage;
        } elseif (!empty($orderItem->product_id)) {
            $orderType = Order::$product;
        } elseif (!empty($orderItem->bundle_id)) {
            $orderType = Order::$bundle;
        }

        $seller_id = OrderItem::getSeller($orderItem);

        $sale = Sale::create([
            'buyer_id' => $orderItem->user_id,
            'seller_id' => $seller_id,
            'order_id' => $orderItem->order_id,
            'webinar_id' => $orderItem->webinar_id,
            'bundle_id' => $orderItem->bundle_id,
            'meeting_id' => !empty($orderItem->reserve_meeting_id) ? $orderItem->reserveMeeting->meeting_id : null,
            'meeting_time_id' => !empty($orderItem->reserveMeeting) ? $orderItem->reserveMeeting->meeting_time_id : null,
            'subscribe_id' => $orderItem->subscribe_id,
            'promotion_id' => $orderItem->promotion_id,
            'registration_package_id' => $orderItem->registration_package_id,
            'product_order_id' => $orderItem->product_order_id ?? null,
            'type' => $orderType,
            'payment_method' => $payment_method,
            'amount' => $orderItem->amount,
            'tax' => $orderItem->tax_price,
            'commission' => $orderItem->commission_price,
            'discount' => $orderItem->discount,
            'total_amount' => $orderItem->total_amount,
            'product_delivery_fee' => $orderItem->product_delivery_fee,
            'created_at' => time(),
        ]);

        $title = '';
        if (!empty($orderItem->webinar_id)) {
            $title = $orderItem->webinar->title;
        } elseif (!empty($orderItem->bundle_id)) {
            $title = $orderItem->bundle->title;
        } else if (!empty($orderItem->meeting_id)) {
            $title = trans('meeting.reservation_appointment');
        } else if (!empty($orderItem->subscribe_id)) {
            $title = $orderItem->subscribe->title . ' ' . trans('financial.subscribe');
        } else if (!empty($orderItem->promotion_id)) {
            $title = $orderItem->promotion->title . ' ' . trans('panel.promotion');
        } else if (!empty($orderItem->registration_package_id)) {
            $title = $orderItem->registrationPackage->title . ' ' . trans('update.registration_package');
        } else if (!empty($orderItem->product_id)) {
            $title = $orderItem->product->title;

            $buyStoreReward = RewardAccounting::calculateScore(Reward::BUY_STORE_PRODUCT, $orderItem->total_amount);
            RewardAccounting::makeRewardAccounting($orderItem->user_id, $buyStoreReward, Reward::BUY_STORE_PRODUCT, $orderItem->product_id);
        }

        $buyReward = RewardAccounting::calculateScore(Reward::BUY, $orderItem->total_amount);
        RewardAccounting::makeRewardAccounting($orderItem->user_id, $buyReward, Reward::BUY);

        if ($orderItem->reserve_meeting_id) {
            $reserveMeeting = $orderItem->reserveMeeting;

            $notifyOptions = [
                '[amount]' => $orderItem->amount,
                '[u.name]' => $orderItem->user->full_name,
                '[time.date]' => $reserveMeeting->day . ' ' . $reserveMeeting->time,
            ];
            sendNotification('new_appointment', $notifyOptions, $orderItem->user_id);
            sendNotification('new_appointment', $notifyOptions, $reserveMeeting->meeting->creator_id);
        } elseif (!empty($orderItem->product_id)) {
            $notifyOptions = [
                '[p.title]' => $title,
            ];

            sendNotification('product_new_sale', $notifyOptions, $seller_id);
            sendNotification('product_new_purchase', $notifyOptions, $orderItem->user_id);
        } else {
            $notifyOptions = [
                '[c.title]' => $title,
            ];

            sendNotification('new_sales', $notifyOptions, $seller_id);
            sendNotification('new_purchase', $notifyOptions, $orderItem->user_id);
        }


        return $sale;
    }

    public function getIncomeItem()
    {
        if ($this->payment_method == self::$subscribe) {
            $used = SubscribeUse::where('webinar_id', $this->webinar_id)
                ->where('sale_id', $this->id)
                ->first();

            if (!empty($used)) {
                $subscribe = $used->subscribe;

                $financialSettings = getFinancialSettings();
                $commission = $financialSettings['commission'] ?? 0;

                $pricePerSubscribe = $subscribe->price / $subscribe->usable_count;
                $commissionPrice = $commission ? $pricePerSubscribe * $commission / 100 : 0;

                return round($pricePerSubscribe - $commissionPrice, 2);
            }
        }

        $income = $this->total_amount - $this->tax - $this->commission;
        return round($income, 2);
    }

    public function getUsedSubscribe($user_id, $itemId, $itemName = 'webinar_id')
    {
        $subscribe = null;
        $use = SubscribeUse::where('sale_id', $this->id)
            ->where($itemName, $itemId)
            ->where('user_id', $user_id)
            ->first();

        if (!empty($use)) {
            $subscribe = Subscribe::where('id', $use->subscribe_id)->first();
        }

        return $subscribe;
    }

    public function checkExpiredPurchaseWithSubscribe($user_id, $itemId, $itemName = 'webinar_id')
    {
        $result = true;

        $subscribe = $this->getUsedSubscribe($user_id, $itemId, $itemName);

        if (!empty($subscribe)) {
            $subscribeSale = self::where('buyer_id', $user_id)
                ->where('type', self::$subscribe)
                ->where('subscribe_id', $subscribe->id)
                ->whereNull('refund_at')
                ->latest('created_at')
                ->first();

            if (!empty($subscribeSale)) {
                $usedDays = (int)diffTimestampDay(time(), $subscribeSale->created_at);

                if ($usedDays <= $subscribe->days) {
                    $result = false;
                }
            }
        }

        return $result;
    }
}
