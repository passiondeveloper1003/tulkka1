@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];

    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
@endphp

<div class="w-100 border-top @if (!$showCalendar) d-none @endif">
    <div class="time-picker-container br-10 @if($isProfile) p-0 @endif">
        <div class="d-flex flex-row align-items-center justify-content-between pt-2 pb-2">

            <!-- <p class="pb-2 pt-2"><i class="fa-solid fa-clock mx-2 text-primary"></i>
                {{ trans('update.select_meeting_time') }}</p> -->

            <!-- @if (isset($authUser) && !$authUser->isTeacher() && !$authUser->trial_expired && count($selectedTimes) > 0)
                <button wire:click.prevent='handleBookNow(1)'
                    class="btn btn-sm rounded bg-white text-primary border-primary mx-4 animate__animatedtrial animate__heartBeat "><i
                        class="fa-regular fa-calendar-days mx-2"></i> {{ trans('update.book_for_trial') }}</button>
            @endif -->
            <!-- @if ($dashboard)
                <div class="d-flex align-items-center flex-column">
                    <img class="rounded-circle" style="width: 60px;" src="{{ $instructor->getAvatar(20) }}">
                    <span class="mt-2">{{ $instructor->full_name }}</span>
                </div>
            @endif -->
        </div>
        <!-- @php
            var_dump($currentMonth);
        @endphp -->
        <div class="border border-primary calendar py-2 br-10">
            <div class="calendar-header">
                <div class="d-flex justify-content-between px-10 px-md-40 py-10">
                    <div class="d-flex align-items-center">
                        <img class="rounded-circle" style="width: 34px;" src="{{ $instructor->getAvatar(20) }}">
                        <span class="@if($isRtl) mr-1 mr-sm-2 @else ml-1 ml-sm-2 @endif font-weight-normal font-md-10">{{ $instructor->full_name }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <h4 class="font-weight-normal font-md-10 pr-1 pr-lg-10 d-none d-lg-block">{{ trans('panel.' . $currentMonth . '-l') }} {{ $currentYear }}
                        </h4>
                        <h4 class="font-weight-normal font-md-10 pr-1 pr-lg-10 d-lg-none">{{ trans('panel.' . $currentMonth) }} {{ $currentYear }}
                        </h4>
                        <div dir="ltr">
                            <span wire:click.prevent="beforeWeek()" class="bg-primary cursor-pointer rounded calendar-arrow"><i
                                    class="fa-solid fa-caret-left text-white"></i></span>
                            <span wire:click.prevent="nextWeek()" class="bg-primary cursor-pointer rounded calendar-arrow"> <i
                                    class="fa-solid fa-caret-right text-white"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            
            <div class="time-picker mt-25 px-15">
                <div class="time-picker-header mb-20">
                    <ul class="date-slot d-flex">
                   
                        @foreach ($daySlots as $daySlot)
                            <li wire:key="{{ $instructor->full_name . 'day-picker-' . $loop->index }}"
                                class="date-slot-wrapper">
                                <div
                                    class="date-slot-item active rounded bg-primary @if ($loop->first) today @endif">
                                    <span class="date-slot-day text-white d-none d-md-block">{{ $daySlot['date'] ?? '' }}</span>
                                    <span
                                        class="date-slot-date text-center d-none d-lg-block">{{ trans('panel.' . $daySlot['datename'] . '-l') ?? '' }}</span>
                                    <span
                                    class="date-slot-date text-center d-none d-md-block d-lg-none">{{ trans('panel.' . $daySlot['datename'] . '-m') ?? '' }}</span>
                                    <span
                                        class="date-slot-date text-center d-md-none">{{ trans('panel.' . $daySlot['datename'] . '-s') ?? '' }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>


                </div>

                <div @if (!$collapsed) style="height: 200px !important; overflow-y: auto;" @endif
                    class="row ml-0 mr-0 time-slot-cont">

                    @foreach ($daySlots as $index => $daySlot)
                        <ul wire:key="{{ $instructor->full_name . 'time-picker-' . $index }}" class="time-slot text-center">
                            @if (isset($daySlot['timeslots']))
                                @foreach ($daySlot['timeslots'] as $timeslot)
                                    <li wire:key="{{ $instructor->full_name . 'time-picker-' . $timeslot['date'] }}"
                                        @if (!isset($authUser)) wire:click="redirectLogin()" @endif
                                        @if (isset($authUser) && $authUser->trial_expired && !$authUser->isPaidUser() && !$authUser->isTeacher()) onclick='Livewire.emit("showModal","SomeData")' @endif
                                        @if (!$timeslot['disabled']) wire:click.prevent="selectTime('{{ $timeslot['date'] }}','{{ $index }}') @endif"
                                        class="time-slot-item @if (isset($selectedTimes[$index]) && $selectedTimes[$index] == $timeslot['date']) picked @endif @if ($timeslot['disabled']) time-slot-item-disabled @endif">
                                        {{ $timeslot['date'] }}</li>
                                @endforeach
                            @endif
                        </ul>
                    @endforeach
                </div>
            </div>
            <div class="d-flex flex-column mt-2 justify-content-center align-items-center w-100">
                <!-- <button wire:click.prevent='setCollapsed()' class="btn btn-sm btn-primary mt-2 rounded" style="width: 202px; height: 35px;">
                    @if ($collapsed)
                        {{ trans('public.show_less') }}
                    @else
                        {{ trans('public.show_more') }}
                    @endif
                </button> -->
                    @if (isset($authUser) && !$authUser->isTeacher() && $authUser->isPaidUser() && count($selectedTimes) > 0)
                        <button wire:click.prevent='handleBookNow()'
                            class="btn btn-sm rounded bg-white text-primary border-primary mx-4 animate__animatedbook animate__heartBeat">
                            <!-- <i class="fa-regular fa-calendar-days mx-2"></i> -->
                            {{ trans('update.reschedule') }}</button>
                    @else
                        <div>Choose a <span>date and time.</div>
                    @endif
            </div>
        </div>
    </div>



</div>
