<div
    class="special-offer-card d-flex flex-column flex-md-row align-items-center justify-content-between rounded-lg shadow-xs bg-white p-15 p-md-30">
    <div class="d-flex flex-column">
        <strong
            class="special-offer-title font-18 text-dark-blue font-weight-bold">{{ trans('panel.special_offer') }} 🎉</strong>
        <span class="font-16 text-gray mt-2">{{trans('public.surprise')}}</span>
    </div>

    <div class="mt-20 mt-md-0 mb-30 mb-md-0">
        <div id="homeOfferCountDown" class="d-flex time-counter-down" data-day="12" data-hour="18" data-minute="12"
            data-second="12">

            <div class="d-flex align-items-center flex-column mr-10">
                <span class="bg-gray300 rounded p-10 font-16 font-weight-bold text-dark time-item days"></span>
                <span class="font-12 mt-1 text-gray">{{ trans('public.day') }}</span>
            </div>
            <div class="d-flex align-items-center flex-column mr-10">
                <span class="bg-gray300 rounded p-10 font-16 font-weight-bold text-dark time-item hours"></span>
                <span class="font-12 mt-1 text-gray">{{ trans('public.hr') }}</span>
            </div>
            <div class="d-flex align-items-center flex-column mr-10">
                <span class="bg-gray300 rounded p-10 font-16 font-weight-bold text-dark time-item minutes"></span>
                <span class="font-12 mt-1 text-gray">{{ trans('public.min') }}</span>
            </div>
            <div class="d-flex align-items-center flex-column">
                <span class="bg-gray300 rounded p-10 font-16 font-weight-bold text-dark time-item seconds"></span>
                <span class="font-12 mt-1 text-gray">{{ trans('public.sec') }}</span>
            </div>
        </div>
    </div>

    <div class="offer-percent-box d-flex flex-column align-items-center justify-content-center">
        <span class="percent text-white">15%</span>
        <span class="off text-white">{{ trans('public.off') }}</span>
    </div>
</div>
