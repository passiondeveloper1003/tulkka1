<div class="plan-modal-2 modal-content animate__bounceIn">
    <div class="modal-header">
        <h5 class="modal-title mx-2">{{ trans('update.card_details') }}</h5>
        <button class="close z-index" type="button" aria-label="Close" wire:click.prevent="doClose()">
            <img src="{{ url('/assets/default/img/close.png') }}">
        </button>
    </div>
    <div class="modal-body d-flex flex-md-row flex-column p-md-40">
        <div class="container mt-5 mb-5">
            <div class="row">
                @if (!$showLoading)
                    <div class="col-md-6">

                        <span class="font-18">{{ trans('update.payment_method') }}</span>
                        <div class="card mt-4 payment-method-item">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-header p-0">
                                        <h2 class="mb-0">
                                            @if ($selectedPaymentMethod == 'cc')
                                                <button class="btn btn-light btn-block text-left p-3 rounded-0"
                                                    data-toggle="collapse" data-target="#collapseOne"
                                                    aria-expanded="true" aria-controls="collapseOne">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span>Credit card</span>
                                                        <div class="icons">
                                                            <img src="https://i.imgur.com/2ISgYja.png" width="30">
                                                            <img src="https://i.imgur.com/W1vtnOV.png" width="30">
                                                            <img src="https://i.imgur.com/35tC99g.png" width="30">
                                                            <img src="https://i.imgur.com/2ISgYja.png" width="30">
                                                        </div>
                                                    </div>
                                                </button>
                                            @endif
                                            @if ($selectedPaymentMethod == 'paypal')
                                                <div id="paypal-button" wire:ignore>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="py-2 pl-2"><img
                                                                src="/assets/default/img/paypaypal.png"
                                                                alt=""></span>
                                                    </div>


                                                </div>
                                            @endif
                                        </h2>
                                    </div>
                                    @if ($selectedPaymentMethod == 'cc')
                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                            data-parent="#accordionExample">
                                            <div class="card-body payment-card-body">

                                                <span class="font-weight-normal card-text">Fullname</span>
                                                <div class="input-group">
                                                    <input wire:model="cardOwner" class="form-control py-2 "
                                                        type="search">
                                                </div>
                                                @error('cardOwner')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                                <span class="font-weight-normal card-text">Card Number</span>
                                                <div class="input-group">
                                                    <span class="input-group-prepend">
                                                        <div class="input-group-text bg-transparent border-right-0"><i
                                                                class="text-white fa fa-credit-card"></i></div>
                                                    </span>
                                                    <input wire:model="cardNumber"
                                                        class="form-control py-2 border-left-0 border" type="search"
                                                        placeholder="0000 0000 0000 0000">
                                                </div>
                                                @error('cardNumber')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror


                                                <div class="row mt-3 mb-3">

                                                    <div class="col-md-6">

                                                        <span class="font-weight-normal card-text">Expiry Date</span>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend">
                                                                <div
                                                                    class="input-group-text bg-transparent border-right-0">
                                                                    <i class="fa fa-calendar text-white"></i>
                                                                </div>
                                                            </span>
                                                            <input wire:model="expiryDate"
                                                                class="form-control py-2 border-left-0 border"
                                                                type="search" id="example-search-input"
                                                                placeholder="MM/YY">
                                                        </div>
                                                        @error('expiryDate')
                                                            <div class="error text-danger">{{ $message }}</div>
                                                        @enderror


                                                    </div>


                                                    <div class="col-md-6">

                                                        <span class="font-weight-normal card-text">CVC/CVV</span>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend">
                                                                <div
                                                                    class="input-group-text bg-transparent border-right-0">
                                                                    <i class="fa fa-lock text-white"></i>
                                                                </div>
                                                            </span>
                                                            <input wire:model="cvv"
                                                                class="form-control py-2 border-left-0 border"
                                                                type="search" placeholder="123">
                                                        </div>
                                                        @error('cvv')
                                                            <div class="error text-danger">{{ $message }}</div>
                                                        @enderror

                                                    </div>


                                                </div>

                                                <span class="text-muted certificate-text"><i class="fa fa-lock"></i>
                                                    Your
                                                    transaction is secured with 256 bit SSL certificate</span>

                                            </div>
                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>

                    </div>
                @endif
                @if ($showLoading)
                    <div class=" col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="mt-4">We are processing your payment...</div>
                    </div>
                @endif

                <div class="mt-10 mt-md-0 col-md-6">
                    <div class="buy-card p-20 text-center rounded">
                        <!-- <h5 class="font-16">{{ trans('update.buying') }}</h5> -->
                        <div wire:click="setSelectedPlan('Monthly')"
                            class="buy-card-item p-40 border border-5 border-success rounded mr-md-5 mt-10 cursor-pointer">
                            <i class="fa-solid fa-graduation-cap font-36 mx-2"></i>
                            <h3 class="subs-modal-package">{{ $selectedPlan }} Subscription</h3>
                            <p class="mt-2"> <i class="fa-solid fa-graduation-cap mx-2"></i>{{trans('update.lessons')}}: {{ $weeklyLesson }}
                            </p>
                            <p class="mt-2"> <i class="fa-solid fa-clock mx-2"></i>{{trans('update.duration')}}: {{ $weeklyHour }}</p>
                        </div>
                        <div class="d-flex justify-content-between mt-4"><span>{{trans('update.price')}}</span> <span>₪
                                {{ ${$selectedPlan . 'PricePerLesson'} }} / {{trans('update.lesson')}}</span></div>
                        <div class="d-flex justify-content-between mt-4"><span>{{trans('update.lesson')}}</span> <span>
                                {{ $totalLessonCount }} {{trans('update.lessons')}}</span></div>
                        @if ($discountAmount)
                            <div class="d-flex justify-content-between"><span>Hot Deal Discount 🔥</span>
                                <span>₪ {{ $discountAmount }}</span>
                            </div>
                        @endif
                        <hr />
                        <div class="d-flex justify-content-between mt-2"><span>{{ trans('update.total') }} </span>
                            <span></span>₪ {{ $totalPrice }}

                        </div>

                        @if (!$couponDiscount && !$hideDiscount)
                            <div class="form-group">
                                <label class="input-label font-weight-500 mt-4">{{ trans('update.discount') }}</label>
                                <input type="text" wire:model="couponCode" class="form-control"
                                    placeholder="{{ trans('update.discount') }}">
                                <button wire:click="applyCouponCode()" class="btn btn-primary btn-sm mt-2">{{trans('update.apply_discount')}}</button>
                                @error('coupon')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                        @elseif($couponDiscount)
                            <div class="text-primary mt-2">
                                <i class="fa-solid fa-check"></i>
                                Discount Successfully Applied
                            </div>
                        @endif

                        <p class="font-10">
                            <i class="fa-solid fa-envelope-open-text text-primary"></i>
                            {{ trans('update.subs_info') }}
                        </p>

                        <div>
                            @error('already_subs')
                                <div class="error text-danger mt-4">{{ $message }}</div>
                            @enderror

                            @if (!$paymentLoading && $selectedPaymentMethod != 'paypal')
                                <button wire:click="finalStep()" class="btn btn-primary mt-4">
                                    @if ($paymentLoading)
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border" role="status">
                                            </div>
                                        </div>
                                    @endif

                                    Pay ₪ {{ $totalPrice }} <i class="ml-2 fa-solid fa-arrow-right"></i>

                                </button>
                            @endif
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>



@if ($selectedPaymentMethod == 'paypal')
    <script>
        Render the PayPal button into #paypal-button-container
        paypal.Buttons({
            // Call your server to set up the transaction
            createOrder: function(data, actions) {
                window.livewire.emit('orderCreated');
                return fetch('/api/paypal/order/create', {
                    method: 'POST',
                    body: JSON.stringify({
                        'user_id': "{{ auth()->user()->id }}",
                        'amount': {{ $totalPrice }},
                    })
                }).then(function(res) {
                    //res.json();
                    return res.json();
                }).then(function(orderData) {

                    return orderData.id;
                });
            },

            // Call your server to finalize the transaction
            onApprove: function(data, actions) {
                return fetch('/api/paypal/order/capture', {
                        method: 'POST',
                        body: JSON.stringify({
                            'user_id': "{{ auth()->user()->id }}",
                            'type': "{{ $selectedPlan }}",
                            'amount': {{ $totalPrice }},
                            'weeklyLesson': "{{ $weeklyLesson }}",
                            'renewDate': "{{ $renewDate }}",
                            'weeklyHour': "{{ $weeklyHour }}",
                            'selectedPlan': "{{ $selectedPlan }}",
                            'vendor_order_id': data.orderID
                        })
                    }).then(function(res) {
                        // console.log(res.json());
                        return res.json();
                    })
                    .catch(function(err) {
                        iziToast.error({
                            title: 'Error',
                            message: 'Error happened Please contact with Live Support',
                        });
                    })
                    .then(function(orderData) {
                        window.livewire.emit('paymentSuccess');
                        iziToast.success({
                            title: 'Success',
                            message: 'Your Subscription Successfully completed',
                            position: 'topRight'
                        });
                        setTimeout(function() {
                            window.location.reload()
                        }, 5000);


                        // Successful capture! For demo purposes:
                        //  console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                        var transaction = orderData.purchase_units[0].payments.captures[0];
                    });
            }

        }).render('#paypal-button');
    </script>
@endif
