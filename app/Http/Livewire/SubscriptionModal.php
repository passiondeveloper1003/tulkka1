<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\UserSubscription;
use Illuminate\Support\Facades\Log;
use App\Models\Discount;

class SubscriptionModal extends Component
{
    public $data;
    public $show = false;
    public $weeklyLesson = '1 Lessons/week';
    public $weeklyHour = '25 min/lesson';
    public $isInstallmentNeeded;
    public $selectedPlan = 'Monthly';
    public $currentStep = 1;
    public $paymentMethod = 'paypal';
    public $processFee = 0;
    public $totalPrice = 0;
    public $cardNumber;
    public $expiryDate;
    public $cvv;
    public $cardOwner;
    public $showLoading = false;
    public $paymentLoading = false;
    public $renewDate;
    public $hasError;
    public $discount;
    public $authUser;
    public $selectedPaymentMethod = 'paypal';
    public $MonthlyPrice = 152;
    public $QuarterlyPrice = 137;
    public $HalflyPrice = 130;
    public $YearlyPrice = 122;
    public $discountAmount;
    public $generalSettings;
    public $MonthlyTotalPrice = 152;
    public $QuarterlyTotalPrice = 137;
    public $HalflyTotalPrice = 130;
    public $YearlyTotalPrice = 122;
    public $discountApplied = false;
    public $coupon;
    public $couponCode;
    public $couponDiscount;
    public $hideDiscount = false;
    public $MonthlyPricePerLesson = 38;
    public $QuarterlyPricePerLesson = 34.25;
    public $HalflyPricePerLesson = 32.5;
    public $YearlyPricePerLesson = 30.5;
    public $mWeeklyLesson = '1 Lessons/week';
    public $qWeeklyLesson = '1 Lessons/week';
    public $hWeeklyLesson = '1 Lessons/week';
    public $yWeeklyLesson = '1 Lessons/week';
    public $mWeeklyHour = '25 min/lesson';
    public $qWeeklyHour = '25 min/lesson';
    public $hWeeklyHour = '25 min/lesson';
    public $yWeeklyHour = '25 min/lesson';
    public $totalLessonCount;

    protected $listeners = ['showModal' => 'showModal','paymentSuccess' => 'doClose','orderCreated' => 'hideDiscount'];

    public function hideDiscount()
    {
        $this->hideDiscount = true;
    }
    public function mount($data = '')
    {
        $this->data = $data;
        //$this->show = false;
        $this->authUser = auth()->user();
        $this->calculateTotalPrice();
        $this->generalSettings =  getGeneralSettings();
        $this->calculateRenewDate();
        $this->applyCouponCode();
    }


    public function showModal($data)
    {
        $this->data = $data;
        $this->doShow();
    }

    public function doShow()
    {
        $this->show = true;
    }

    public function doClose()
    {
        $this->currentStep = 1;
        $this->reset();
        $this->show = false;
    }

    public function doSomething()
    {
        $this->doClose();
    }



    public function calculateRenewDate()
    {
        $plan = $this->selectedPlan;

        if ($plan == 'Monthly') {
            $this->renewDate = \Carbon\Carbon::now()->addDays(30)->toDateTimeString();
        }
        if ($plan == 'Quarterly') {
            $this->renewDate = \Carbon\Carbon::now()->addDays(90)->toDateTimeString();
        }
        if ($plan == 'Halfly') {
            $this->renewDate = \Carbon\Carbon::now()->addDays(180)->toDateTimeString();
        }
        if ($plan == 'Yearly') {
            $this->renewDate = \Carbon\Carbon::now()->addYear()->toDateTimeString();
        }
    }
    public function calculateTotalPrice()
    {
        $basePackagePrice = 0;
        (int) $packageMultiplier = substr($this->weeklyLesson, 0, 1);
        $monthlyBasePrice = 0;
        $quarterlyBasePrice = 0;
        $halflyBasePrice = 0;
        $yearlyBasePrice = 0;



        //Monthly
        if ($this->weeklyHour == '25 min/lesson') {
            $monthlyBasePrice = 108;
        }
        if ($this->weeklyHour == '40 min/lesson') {
            $monthlyBasePrice = 152;
        }
        if ($this->weeklyHour == '55 min/lesson') {
            $monthlyBasePrice = 180;
        }
        //Quarter
        if ($this->weeklyHour == '25 min/lesson') {
            $quarterlyBasePrice = 97.2;
        }
        if ($this->weeklyHour == '40 min/lesson') {
            $quarterlyBasePrice = 136.8;
        }
        if ($this->weeklyHour == '55 min/lesson') {
            $quarterlyBasePrice = 162;
        }
        //Halfly
        if ($this->weeklyHour == '25 min/lesson') {
            $halflyBasePrice = 91.8;
        }
        if ($this->weeklyHour == '40 min/lesson') {
            $halflyBasePrice = 129.2;
        }
        if ($this->weeklyHour == '55 min/lesson') {
            $halflyBasePrice = 153;
        }
        //Yearly

        if ($this->weeklyHour == '25 min/lesson') {
            $yearlyBasePrice = 86.4;
        }
        if ($this->weeklyHour == '40 min/lesson') {
            $yearlyBasePrice = 121.6;
        }
        if ($this->weeklyHour == '55 min/lesson') {
            $yearlyBasePrice = 144;
        }


        if ($this->selectedPlan == 'Monthly' && $this->weeklyHour == '25 min/lesson') {
            $basePackagePrice = 108;
        }
        if ($this->selectedPlan == 'Monthly' && $this->weeklyHour == '40 min/lesson') {
            $basePackagePrice = 152;
        }
        if ($this->selectedPlan == 'Monthly' && $this->weeklyHour == '55 min/lesson') {
            $basePackagePrice = 180;
        }

        if ($this->selectedPlan == 'Quarterly' && $this->weeklyHour == '25 min/lesson') {
            $basePackagePrice = 97.2;
        }
        if ($this->selectedPlan == 'Quarterly' && $this->weeklyHour == '40 min/lesson') {
            $basePackagePrice = 136.8;
        }
        if ($this->selectedPlan == 'Quarterly' && $this->weeklyHour == '55 min/lesson') {
            $basePackagePrice = 162;
        }

        if ($this->selectedPlan == 'Halfly' && $this->weeklyHour == '25 min/lesson') {
            $basePackagePrice = 91.8;
        }
        if ($this->selectedPlan == 'Halfly' && $this->weeklyHour == '40 min/lesson') {
            $basePackagePrice = 129.2;
        }
        if ($this->selectedPlan == 'Halfly' && $this->weeklyHour == '55 min/lesson') {
            $basePackagePrice = 153;
        }

        if ($this->selectedPlan == 'Yearly' && $this->weeklyHour == '25 min/lesson') {
            $basePackagePrice = 86.4;
        }
        if ($this->selectedPlan == 'Yearly' && $this->weeklyHour == '40 min/lesson') {
            $basePackagePrice = 121.6;
        }
        if ($this->selectedPlan == 'Yearly' && $this->weeklyHour == '55 min/lesson') {
            $basePackagePrice = 144;
        }

        $totalPrice = $basePackagePrice * $packageMultiplier;

        $this->MonthlyPrice = ceil($monthlyBasePrice * $packageMultiplier);
        $this->QuarterlyPrice = ceil($quarterlyBasePrice * $packageMultiplier);
        $this->HalflyPrice = ceil($halflyBasePrice * $packageMultiplier);
        $this->YearlyPrice = ceil($yearlyBasePrice * $packageMultiplier);
        $this->totalPrice = ceil($totalPrice);

        if (isset($this->authUser) && !$this->authUser->isPaidUser() && $this->authUser->trial_expired) {
            $this->MonthlyPrice = $this->applyDiscount($this->MonthlyPrice);
            $this->QuarterlyPrice = $this->applyDiscount($this->QuarterlyPrice);
            $this->HalflyPrice = $this->applyDiscount($this->HalflyPrice);
            $this->YearlyPrice = $this->applyDiscount($this->YearlyPrice);
            $this->totalPrice = $this->{$this->selectedPlan.'TotalPrice'};
            $couponDiscountAmount = ($this->totalPrice / 100) * $this->couponDiscount;
            $this->totalPrice -= $couponDiscountAmount;
            $this->discountApplied = true;
        }

        /* $this->processFee = ceil($totalPrice * 0.015 + $totalPrice); */

        $this->MonthlyTotalPrice = $this->MonthlyPrice ;
        $this->QuarterlyTotalPrice = $this->QuarterlyPrice * 3;
        $this->HalflyTotalPrice = $this->HalflyPrice * 6;
        $this->YearlyTotalPrice = $this->YearlyPrice * 12;
        $this->totalPrice = $this->{$this->selectedPlan.'TotalPrice'};
        $couponDiscountAmount = ($this->totalPrice / 100) * $this->couponDiscount;
        $this->totalPrice -= $couponDiscountAmount;

        if ($this->weeklyLesson == '1 Lessons/week' && $this->selectedPlan == 'Monthly') {
            $this->MonthlyPricePerLesson = number_format((float) $this->MonthlyTotalPrice / 4, 1, '.', '');
        }
        if ($this->weeklyLesson == '1 Lessons/week' && $this->selectedPlan == 'Quarterly') {
            $this->QuarterlyPricePerLesson = number_format((float) $this->QuarterlyTotalPrice / 12, 2, '.', '');
        }
        if ($this->weeklyLesson == '1 Lessons/week' && $this->selectedPlan == 'Halfly') {
            $this->HalflyPricePerLesson =  number_format((float) $this->HalflyTotalPrice / 24, 2, '.', '');
        }
        if ($this->weeklyLesson == '1 Lessons/week' && $this->selectedPlan == 'Yearly') {
            $this->YearlyPricePerLesson = number_format((float) $this->YearlyTotalPrice / 48, 2, '.', '');
        }

        if ($this->weeklyLesson == '2 Lessons/week' && $this->selectedPlan == 'Monthly') {
            $this->MonthlyPricePerLesson = number_format((float) $this->MonthlyTotalPrice / 8, 2, '.', '');
            ;
        }
        if ($this->weeklyLesson == '2 Lessons/week' && $this->selectedPlan == 'Quarterly') {
            $this->QuarterlyPricePerLesson = number_format((float) $this->QuarterlyTotalPrice / 24, 2, '.', '');
        }
        if ($this->weeklyLesson == '2 Lessons/week' && $this->selectedPlan == 'Halfly') {
            $this->HalflyPricePerLesson = number_format((float) $this->HalflyTotalPrice / 48, 2, '.', '');
        }
        if ($this->weeklyLesson == '2 Lessons/week' && $this->selectedPlan == 'Yearly') {
            $this->YearlyPricePerLesson = number_format((float) $this->YearlyTotalPrice / 96, 2, '.', '');
        }


        if ($this->weeklyLesson == '3 Lessons/week' && $this->selectedPlan == 'Monthly') {
            $this->MonthlyPricePerLesson = number_format((float) $this->MonthlyTotalPrice / 12, 2, '.', '');
        }
        if ($this->weeklyLesson == '3 Lessons/week' && $this->selectedPlan == 'Quarterly') {
            $this->QuarterlyPricePerLesson = number_format((float) $this->QuarterlyTotalPrice / 36, 2, '.', '');
        }
        if ($this->weeklyLesson == '3 Lessons/week' && $this->selectedPlan == 'Halfly') {
            $this->HalflyPricePerLesson = number_format((float) $this->HalflyTotalPrice / 72, 2, '.', '');
        }
        if ($this->weeklyLesson == '3 Lessons/week' && $this->selectedPlan == 'Yearly') {
            $this->YearlyPricePerLesson = number_format((float) $this->YearlyTotalPrice / 144, 2, '.', '');
        }

        if ($this->weeklyLesson == '4 Lessons/week' && $this->selectedPlan == 'Monthly') {
            $this->MonthlyPricePerLesson = number_format((float) $this->MonthlyTotalPrice / 16, 2, '.', '');
        }
        if ($this->weeklyLesson == '4 Lessons/week' && $this->selectedPlan == 'Quarterly') {
            $this->QuarterlyPricePerLesson = number_format((float) $this->QuarterlyTotalPrice / 48, 2, '.', '');
        }
        if ($this->weeklyLesson == '4 Lessons/week' && $this->selectedPlan == 'Halfly') {
            $this->HalflyPricePerLesson = number_format((float) $this->HalflyTotalPrice / 96, 2, '.', '');
        }
        if ($this->weeklyLesson == '4 Lessons/week' && $this->selectedPlan == 'Yearly') {
            $this->YearlyPricePerLesson = number_format((float) $this->YearlyTotalPrice / 192, 2, '.', '');
        }

        if ($this->weeklyLesson == '5 Lessons/week' && $this->selectedPlan == 'Monthly') {
            $this->MonthlyPricePerLesson = number_format((float) $this->MonthlyTotalPrice / 20, 2, '.', '');
        }
        if ($this->weeklyLesson == '5 Lessons/week' && $this->selectedPlan == 'Quarterly') {
            $this->QuarterlyPricePerLesson = number_format((float) $this->QuarterlyTotalPrice / 60, 2, '.', '');
        }
        if ($this->weeklyLesson == '5 Lessons/week' && $this->selectedPlan == 'Halfly') {
            $this->HalflyPricePerLesson = number_format((float)$this->HalflyTotalPrice / 120, 2, '.', '');
        }
        if ($this->weeklyLesson == '5 Lessons/week' && $this->selectedPlan == 'Yearly') {
            $this->YearlyPricePerLesson = number_format((float) $this->YearlyTotalPrice / 240, 2, '.', '');
        }

        if ($this->selectedPlan == 'Monthly') {
            $this->totalLessonCount = $packageMultiplier * 1;
        }
        if ($this->selectedPlan == 'Quarterly') {
            $this->totalLessonCount = $packageMultiplier * 12;
        }
        if ($this->selectedPlan == 'Yearly') {
            $this->totalLessonCount = $packageMultiplier * 48;
        }
    }

    public function applyDiscount($totalPrice)
    {
        $this->discount = 15;
        $discountAmount =  ceil($totalPrice - ($totalPrice * 0.15));
        $this->discountAmount = $totalPrice - $discountAmount;
        return $discountAmount;
    }
    public function addFee($totalPrice)
    {
        /*         $processFee = $totalPrice * 0.015;
                $this->processFee = ceil($processFee);
                return ceil($processFee + $totalPrice); */
        return ceil($totalPrice);
    }

    public function setSelectedPlan($plan)
    {
        $this->selectedPlan = $plan;
        $this->calculateTotalPrice();
        $this->calculateRenewDate();
    }
    public function setWeeklyLesson($lesson, $type)
    {
        $this->selectedPlan = $type;
        if ($type == 'Monthly') {
            $this->mWeeklyLesson = $lesson;
        }
        if ($type == 'Quarterly') {
            $this->qWeeklyLesson = $lesson;
        }
        if ($type == 'Yearly') {
            $this->yWeeklyLesson = $lesson;
        }
        $this->weeklyLesson = $lesson;
        $this->calculateTotalPrice();
    }
    public function setWeeklyHour($hour, $type)
    {
        $this->selectedPlan = $type;
        if ($type == 'Monthly') {
            $this->mWeeklyHour = $hour;
        }
        if ($type == 'Quarterly') {
            $this->qWeeklyHour = $hour;
        }
        if ($type == 'Yearly') {
            $this->yWeeklyHour = $hour;
        }
        $this->weeklyHour = $hour;
        $this->calculateTotalPrice();
    }

    public function firstSubmit($selectedPlan)
    {
        $this->selectedPlan = $selectedPlan;
        $this->currentStep++;
    }

    public function nextStep()
    {
        $this->currentStep++;
    }
    public function finalStep()
    {
        $validatedData = $this->validate([
          'cardOwner' => 'required',
          'expiryDate' => 'required',
          'cardNumber' => 'required',
          'cvv' => 'required'
  ]);

        $this->showLoading = true;
        try {
            $user = auth()->user();
            if ($user->subscription_type) {
                $this->addError('already_subs', 'You have already active subscription package');
                $this->showLoading = false;
                return;
            }
            $user_subscription = UserSubscription::create([
              'user_id' => $user->id,
              'type' => $this->selectedPlan,
              'each_lesson' => $this->weeklyLesson,
              'renew_date' => $this->renewDate,
              'weekly_comp_class' => '0',
              'how_often' => $this->weeklyHour,
            ]);
            $user->subscription_type = $this->selectedPlan;
            $user->trial_expired = 1;
            $user->save();
            //$payment_history = PaymentHistory::create();
        } catch(\Exception $err) {
            $this->hasError = $err;
            $this->currentStep++;
            $this->showLoading = false;
        }
        $this->currentStep++;
        $this->showLoading = false;
    }

    public function setPaymentMethod($method)
    {
        $this->selectedPaymentMethod = $method;
    }

    public function applyCouponCode()
    {
        if (!$this->couponCode) {
            return;
        }
        $this->coupon = Discount::with('discountUsers')->where('source', 'product')->where('product_type', 'virtual')->where('status', 'active')->where('count', '>', 0)->where('code', $this->couponCode)->first();

        if ($this->coupon && $this->couponCode == $this->coupon->code && $this->coupon->user_type != 'special_users') {
            $this->couponDiscount = $this->coupon->percent;
        }
        if ($this->coupon && $this->couponCode == $this->coupon->code && $this->coupon->user_type == 'special_users') {
            if ($this->authUser->id == $this->coupon->discountUsers->user_id) {
                $this->couponDiscount = $this->coupon->percent;
            }
        }
        if (!$this->couponDiscount) {
            $this->addError('coupon', 'Not Valid Coupon');
        }


        $this->calculateTotalPrice();
    }





      public function render()
      {
          $this->authUser = auth()->user();
          $this->calculateTotalPrice();
          $this->generalSettings =  getGeneralSettings();
          return view('livewire.subscription-modal');
      }
}
