<div class="plan-modal modal-content animate__bounceIn subscription-step">
    <div class="modal-header border-0 position-absolute right-0">
        <button class="close mx-2 z-index" type="button" aria-label="Close" wire:click.prevent="doClose()">
            <img src="{{ url('/assets/default/img/close.png') }}">
        </button>
    </div>
    <div class="modal-body d-flex flex-column align-items-md-center overflow-hidden text-center pl-lg-40 pr-lg-40 pb-60">
        @if (isset($authUser) && !$authUser->isPaidUser() && $authUser->trial_expired)
            <div class="font-36">%15 {{ trans('public.hot_deal') }}</div>
            <div class="font-36">🔥</div>
            <div class="font-36">{{ trans('public.earned_discount') }}</div>
        @endif

        @if (!empty($generalSettings['logo']))
            <img src="{{ url('/store/1/comp-logo-footer.png') }}" class="subs-logo mt-2" alt="site logo">
        @endif
        <h2 class="subs-modal-header mt-4">{{ trans('update.select_plan') }}</h2>
        <div class="d-flex mt-40 flex-md-row flex-column">
            <div
                class="bg-tgray p-20 p-md-20 p-lg-40 border border-5 rounded mr-md-20 mt-md-5 mr-lg-40 cursor-pointer subs-card">
                <h3 class="subs-modal-title">{{ trans('public.monthly') }}</h3>
                {{-- <img class="w-10 mt-2" src="{{ url('/store/1/default_images/subscribe_packages/bronze.png') }}"
                    alt=""> --}}
                <div style="height: 21px"></div>
            
                <p class="mt-20"><span class="subs-modal-price">₪ {{ $MonthlyPricePerLesson }}</span>
                    {{ trans('public.price_per') }}</p>
                <button class="btn btn-sm bg-primary mt-2 rounded px-60 font-12 text-white"
                    wire:click="firstSubmit('Monthly')">
                    {{ trans('panel.continue') }}</button>
                <p class="mt-2 font-10">{{ trans('public.renew_month') }}</p>
                {{-- <p class="mt-4">{{ trans('public.total') }} ₪ {{ $MonthlyTotalPrice }}</p> --}}
                {{-- <p class="mt-2">{{ trans('public.per_month') }} ₪ {{ $MonthlyPrice }} --}}

                <div class="d-flex flex-column mt-4 border-top border-gray">
                    <div class="dropdown mt-2">
                        <p class="subs-howoften text-tblack">{{ trans('public.how_often_week') }}</p>
                        <button class="btn btn-sm btn-white dropdown-toggle mt-2 rounded w-100 font-10" type="button"
                            id="dropdownMenuButtonLesson2" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            {{ trans("public.$mWeeklyLesson") }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a wire:click="setWeeklyLesson('1 Lesson/week','Monthly')"
                                class="dropdown-item">{{ trans('public.1 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('2 Lessons/week','Monthly')"
                                class="dropdown-item">{{ trans('public.2 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('3 Lessons/week','Monthly')"
                                class="dropdown-item">{{ trans('public.3 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('4 Lessons/week','Monthly')"
                                class="dropdown-item">{{ trans('public.4 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('5 Lessons/week','Monthly')"
                                class="dropdown-item">{{ trans('public.5 Lessons/week') }}</a>
                        </div>
                    </div>
                    <div class="dropdown mt-2">
                        <p class="subs-howlong text-tblack">{{ trans('public.how_often_you_ready') }}</p>
                        <button class="btn btn-sm btn-white dropdown-toggle mt-2 rounded w-100 font-10" type="button"
                            id="dropdownMenuButtonHour" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            {{ trans("public.$weeklyHour") }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a wire:click="setWeeklyHour('25 min/lesson','Monthly')"
                                class="dropdown-item ">{{ trans('public.25 min/lesson') }}</a>
                            <a wire:click="setWeeklyHour('40 min/lesson','Monthly')"
                                class="dropdown-item">{{ trans('public.40 min/lesson') }}</a>
                            <a wire:click="setWeeklyHour('55 min/lesson','Monthly')"
                                class="dropdown-item">{{ trans('public.55 min/lesson') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="bg-tgray p-20 p-md-20 p-lg-40 border border-5 rounded mr-md-20 mr-lg-40 mt-20 mt-md-5 cursor-pointer subs-card position-relative">
                <div class="mt-2 text-white bg-primary rounded-pill p-10 font-12 popular-badge px-40">
                    {{ trans('update.most_popular') }}
                </div>
                <h3 class="subs-modal-title">{{ trans('public.quarterly') }}</h3>
                <div>
                    <div class="mt-2 text-white bg-primary rounded-pill p-1 d-inline font-10">
                        {{ trans('public.yousave_10') }}
                    </div>
                </div>
                {{-- <img class="w-10 mt-2" src="{{ url('/store/1/default_images/subscribe_packages/bronze.png') }}"
                  alt=""> --}}

                <p class="mt-20"><span class="subs-modal-price">₪ {{ $QuarterlyPricePerLesson }}</span>
                    {{ trans('public.price_per') }}</p>


                <button class="btn btn-sm bg-primary mt-2 rounded px-60 font-12 text-white"
                    wire:click="firstSubmit('Quarterly')">
                    {{ trans('panel.continue') }}</button>
                <p class="mt-2 font-10">{{ trans('public.renew_quarter') }}</p>
                {{-- <p class="mt-4">{{ trans('public.total') }} ₪ {{ $MonthlyTotalPrice }}</p> --}}
                {{-- <p class="mt-2">{{ trans('public.per_month') }} ₪ {{ $MonthlyPrice }} --}}

                <div class="d-flex flex-column mt-4 border-top border-gray">
                    <div class="dropdown mt-2">
                        <p class="subs-howoften text-tblack">{{ trans('public.how_often_week') }}</p>
                        <button class="btn btn-sm btn-white dropdown-toggle mt-2 rounded w-100 font-10" type="button"
                            id="dropdownMenuButtonLesson2" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            {{ trans("public.$qWeeklyLesson") }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a wire:click="setWeeklyLesson('1 Lesson/week','Quarterly')"
                                class="dropdown-item">{{ trans('public.1 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('2 Lessons/week','Quarterly')"
                                class="dropdown-item">{{ trans('public.2 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('3 Lessons/week','Quarterly')"
                                class="dropdown-item">{{ trans('public.3 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('4 Lessons/week','Quarterly')"
                                class="dropdown-item">{{ trans('public.4 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('5 Lessons/week','Quarterly')"
                                class="dropdown-item">{{ trans('public.5 Lessons/week') }}</a>
                        </div>
                    </div>
                    <div class="dropdown mt-2">
                        <p class="subs-howlong text-tblack">{{ trans('public.how_often_you_ready') }}</p>
                        <button class="btn btn-sm btn-white dropdown-toggle mt-2 rounded w-100 font-10" type="button"
                            id="dropdownMenuButtonHour" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            {{ trans("public.$qWeeklyHour") }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a wire:click="setWeeklyHour('25 min/lesson','Quarterly')"
                                class="dropdown-item ">{{ trans('public.25 min/lesson') }}</a>
                            <a wire:click="setWeeklyHour('40 min/lesson','Quarterly')"
                                class="dropdown-item">{{ trans('public.40 min/lesson') }}</a>
                            <a wire:click="setWeeklyHour('55 min/lesson','Quarterly')"
                                class="dropdown-item">{{ trans('public.55 min/lesson') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-tgray p-20 p-md-20 p-lg-40 border border-5 rounded mt-20 mt-md-5 cursor-pointer subs-card">
                <h3 class="subs-modal-title">{{ trans('public.yearly') }}</h3>
                <div>
                    <div class="mt-2 text-white bg-primary rounded-pill p-1 d-inline font-10">
                        {{ trans('public.yousave_20') }}
                    </div>
                </div>
                {{-- <img class="w-10 mt-2" src="{{ url('/store/1/default_images/subscribe_packages/bronze.png') }}"
                alt=""> --}}
                <p class="mt-20"><span class="subs-modal-price">₪ {{ $YearlyPricePerLesson }}</span>
                    {{ trans('public.price_per') }}</p>
                <button class="btn btn-sm bg-primary mt-2 rounded px-60 font-12 text-white"
                    wire:click="firstSubmit('Yearly')">
                    {{ trans('panel.continue') }}</button>
                <p class="mt-2 font-10">{{ trans('public.renew_year') }}</p>
                {{-- <p class="mt-4">{{ trans('public.total') }} ₪ {{ $MonthlyTotalPrice }}</p> --}}
                {{-- <p class="mt-2">{{ trans('public.per_month') }} ₪ {{ $MonthlyPrice }} --}}

                <div class="d-flex flex-column mt-4 border-top border-gray">
                    <div class="dropdown mt-2">
                        <p class="subs-howoften text-tblack">{{ trans('public.how_often_week') }}</p>
                        <button class="btn btn-sm btn-white dropdown-toggle mt-2 rounded w-100 font-10" type="button"
                            id="dropdownMenuButtonLesson2" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            {{ trans("public.$yWeeklyLesson") }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a wire:click="setWeeklyLesson('1 Lesson/week','Yearly')"
                                class="dropdown-item">{{ trans('public.1 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('2 Lessons/week','Yearly')"
                                class="dropdown-item">{{ trans('public.2 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('3 Lessons/week','Yearly')"
                                class="dropdown-item">{{ trans('public.3 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('4 Lessons/week','Yearly')"
                                class="dropdown-item">{{ trans('public.4 Lessons/week') }}</a>
                            <a wire:click="setWeeklyLesson('5 Lessons/week','Yearly')"
                                class="dropdown-item">{{ trans('public.5 Lessons/week') }}</a>
                        </div>
                    </div>
                    <div class="dropdown mt-2">
                        <p class="subs-howlong text-tblack">{{ trans('public.how_often_you_ready') }}</p>
                        <button class="btn btn-sm btn-white dropdown-toggle mt-2 rounded w-100 font-10" type="button"
                            id="dropdownMenuButtonHour" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            {{ trans("public.$yWeeklyHour") }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a wire:click="setWeeklyHour('25 min/lesson','Yearly')"
                                class="dropdown-item ">{{ trans('public.25 min/lesson') }}</a>
                            <a wire:click="setWeeklyHour('40 min/lesson','Yearly')"
                                class="dropdown-item">{{ trans('public.40 min/lesson') }}</a>
                            <a wire:click="setWeeklyHour('55 min/lesson','Yearly')"
                                class="dropdown-item">{{ trans('public.55 min/lesson') }}</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div>
            @error('selectedPlan')
                <div class="alert text-danger" role="alert">
                    {{ $message }}
                </div>
            @enderror

        </div>

    </div>
</div>
