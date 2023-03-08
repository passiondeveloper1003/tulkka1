<div class="modal-content animate__bounceIn">
    <div class="modal-header">
        <h5 class="modal-title mx-2">Payment</h5>
        <button class="close" type="button" aria-label="Close" wire:click.prevent="doClose()">
          <img src="{{ url('/assets/default/img/close.png') }}">
        </button>
    </div>
    <div class="modal-body d-flex flex-md-row flex-column  p-40">
        <div class="p-20">
            <h5 class="modal-title">Payment Method</h5>
            <p class="mt-4"><i class="fa-solid fa-shield mr-2 text-secondary"></i>Data is not stored, sending
                encrypted and securely.</p>
            <div wire:click="setPaymentMethod('cc')" class="custom-control custom-radio border rounded py-20 mt-4 d-flex ">
                <div>
                    <input type="radio" class="custom-control-input"
                        @if ($selectedPaymentMethod == 'cc') checked @endif>
                    <label class="custom-control-label font-16 cursor-pointer">{{ trans('payment.cc') }}</label>
                </div>
                <div>
                    <i class="fa-brands fa-cc-visa"></i>
                    <i class="fa-brands fa-cc-mastercard"></i>
                    <i class="fa-brands fa-cc-amex"></i>
                    <i class="fa-brands fa-cc-discover"></i>
                </div>
            </div>
            <div wire:click="setPaymentMethod('paypal')" class="custom-control custom-radio border rounded py-20 mt-4 d-flex">
                <div>
                    <input type="radio" class="custom-control-input"
                        @if ($selectedPaymentMethod == 'paypal') checked @endif>
                    <label class="custom-control-label font-16 cursor-pointer">{{ trans('payment.paypal') }}</label>
                </div>
            </div>

            <div class="bg-gray200 p-20 mt-20 rounded">
                <p class="text-warning"><i class="fa-regular fa-circle-check mr-2 text-warning"></i>100% lesson
                    guarantee</p>
                <p>If the lesson doesn't take place, we'll offer you another one for free.</p>
            </div>
        </div>
        <div class="border-left p-20 text-center">
            <h5 class="modal-title font-20">You are buying</h5>
            <div wire:click="setSelectedPlan('Monthly')"
                class="bg-success p-40 border border-5 border-success rounded mr-md-5 mt-10 cursor-pointer">
                <i class="fa-solid fa-graduation-cap font-36"></i>
                <h3>{{ $selectedPlan }} Subscription</h3>
                <p class="mt-2 text-primary font-weight-bold">₪ {{ $totalPrice }}</p>
                <p class="mt-2"> <i class="fa-solid fa-graduation-cap"></i>Lessons: {{ $weeklyLesson }}</p>
                <p class="mt-2"> <i class="fa-solid fa-clock"></i>Duration: {{ $weeklyHour }}</p>
            </div>
            <hr />

            <div class="d-flex justify-content-between"><span>Price</span> <span>$
              {{ $totalPrice }}</span></div>
            <div class="d-flex justify-content-between"><span>Processing fee</span><span>{{ $processFee }}</span>
            </div>
            @if ($discount)
                <div class="d-flex justify-content-between"><span>Hot Deal Discount 🔥</span>
                    <span>{{ $discount }}</span>
                </div>
            @endif
            <div class="d-flex justify-content-between"><span>Total</span> <span></span>{{ $totalPrice }}</div>

            <hr />
            <p>
                Subscription starts immediately and will renew automatically. Cancel anytime.
            </p>
            <div><button wire:click="nextStep()" class="btn btn-primary mt-4">Go To Payment</button></div>
        </div>
    </div>
</div>
