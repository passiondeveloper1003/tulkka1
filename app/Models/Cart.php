<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Cart extends Model
{
    protected $table = "cart";

    public $timestamps = false;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\Bundle', 'bundle_id', 'id');
    }

    public function reserveMeeting()
    {
        return $this->belongsTo('App\Models\ReserveMeeting', 'reserve_meeting_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket', 'ticket_id', 'id');
    }

    public function productOrder()
    {
        return $this->belongsTo('App\Models\ProductOrder', 'product_order_id', 'id');
    }

    public static function emptyCart($userId)
    {
        Cart::where('creator_id', $userId)->delete();
    }

    public static function getCartsTotalPrice($carts)
    {
        $totalPrice = 0;

        if (!empty($carts) and count($carts)) {
            foreach ($carts as $cart) {
                if ((!empty($cart->ticket_id) or !empty($cart->special_offer_id)) and !empty($cart->webinar)) {
                    $totalPrice += $cart->webinar->price - $cart->webinar->getDiscount($cart->ticket);
                } else if (!empty($cart->webinar_id) and !empty($cart->webinar)) {
                    $totalPrice += $cart->webinar->price;
                } else if (!empty($cart->bundle_id) and !empty($cart->bundle)) {
                    $totalPrice += $cart->bundle->price;
                } else if (!empty($cart->reserve_meeting_id) and !empty($cart->reserveMeeting)) {
                    $totalPrice += $cart->reserveMeeting->paid_amount;
                } else if (!empty($cart->product_order_id) and !empty($cart->productOrder) and !empty($cart->productOrder->product)) {
                    $product = $cart->productOrder->product;

                    $totalPrice += (($product->price * $cart->productOrder->quantity) - $product->getDiscountPrice());
                }
            }
        }

        return $totalPrice;
    }

    public function getItemInfo()
    {
        if (empty($this->itemInfo)) {
            $info = [];

            if (!empty($this->webinar_id)) {
                $webinar = $this->webinar;

                $info['imgPath'] = $webinar->getImage();
                $info['itemUrl'] = $webinar->getUrl();
                $info['title'] = $webinar->title;
                $info['profileUrl'] = $webinar->teacher->getProfileUrl();
                $info['teacherName'] = $webinar->teacher->full_name;
                $info['rate'] = $webinar->getRate();
                $info['price'] = $webinar->price;
                $info['discountPrice'] = $webinar->getDiscount($this->ticket) ? ($webinar->price - $webinar->getDiscount($this->ticket)) : null;
            } elseif (!empty($this->bundle_id)) {
                $bundle = $this->bundle;

                $info['imgPath'] = $bundle->getImage();
                $info['itemUrl'] = $bundle->getUrl();
                $info['title'] = $bundle->title;
                $info['profileUrl'] = $bundle->teacher->getProfileUrl();
                $info['teacherName'] = $bundle->teacher->full_name;
                $info['rate'] = $bundle->getRate();
                $info['price'] = $bundle->price;
                $info['discountPrice'] = $bundle->getDiscount($this->ticket) ? ($bundle->price - $bundle->getDiscount($this->ticket)) : null;
            } elseif (!empty($this->productOrder) and !empty($this->productOrder->product)) {
                $product = $this->productOrder->product;

                $info['imgPath'] = $product->thumbnail;
                $info['itemUrl'] = $product->getUrl();
                $info['title'] = $product->title;
                $info['profileUrl'] = $product->creator->getProfileUrl();
                $info['teacherName'] = $product->creator->full_name;
                $info['rate'] = $product->getRate();
                $info['quantity'] = $this->productOrder->quantity;
                $info['price'] = $product->price;
                $info['discountPrice'] = ($product->getPriceWithActiveDiscountPrice() < $product->price) ? $product->getPriceWithActiveDiscountPrice() : null;
            } elseif (!empty($this->reserve_meeting_id)) {
                $creator = $this->reserveMeeting->meeting->creator;

                $info['imgPath'] = $creator->getAvatar(150);
                $info['itemUrl'] = null;
                $info['title'] = trans('meeting.reservation_appointment') . ' ' . ((!empty($this->reserveMeeting->student_count) and $this->reserveMeeting->student_count > 1) ? '(' . trans('update.reservation_appointment_student_count', ['count' => $this->reserveMeeting->student_count]) . ')' : '');
                $info['profileUrl'] = $creator->getProfileUrl();
                $info['teacherName'] = $creator->full_name;
                $info['rate'] = $creator->rates();
                $info['price'] = $this->reserveMeeting->paid_amount;
            }

            $this->itemInfo = $info;
        }

        return $this->itemInfo;
    }
}
