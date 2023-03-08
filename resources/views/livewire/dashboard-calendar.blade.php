<div class="w-100">
    <div class="time-picker-container border-top border-gray300 ">
        <div class="border-bottom border-gray300 d-flex flex-row align-items-center  pt-2 pb-2">
            <p class="pb-2 pt-2">{{ trans('update.select_meeting_time') }}</p>
            @if ($showDisableButton)
                <button id="book-now" wire:click.prevent='disableSelected()'
                    class="btn btn-sm btn-primary ml-4 animate__animatedbook animate__heartBeat">Disable Selected
                    Time</button>
            @endif
            @if ($showIsEvery)
                <button id="book-now2" wire:click.prevent='disableSelected(1)'
                    class="btn btn-sm btn-primary ml-4 animate__animatedbook animate__heartBeat">Disable For Every Day
                    Now</button>
            @endif
            @if ($showEnableTime)
                <button id="book-now3" wire:click.prevent='enableSelected()'
                    class="btn btn-sm btn-primary ml-4 animate__animatedbook animate__heartBeat">Enable Time
                    Now</button>
            @endif
            @if ($showEnableTime)
                <button id="book-now4" wire:click.prevent='enableSelected(1)'
                    class="btn btn-sm btn-primary ml-4 animate__animatedbook animate__heartBeat">Enable Time
                    For Everyday</button>
            @endif

        </div>
        <h4 id="curr-month" class="pt-2 pb-2">{{ $currentMonth }} {{ $currentYear }}</h4>

        <div class="time-picker">
            <div class="time-picker-header">
                <button wire:click.prevent="beforeWeek()" class="arrow left inactive"></button>
                <ul class="date-slot d-flex">
                    @foreach ($daySlots as $daySlot)
                        <li class="date-slot-wrapper">
                            <div class="date-slot-item active rounded @if ($loop->first) today @endif">
                                <span class="date-slot-day">{{ $daySlot['date'] ?? '' }}</span>
                                <span class="date-slot-date">{{ $daySlot['datename'] ?? '' }}</span>

                            </div>
                        </li>
                    @endforeach
                </ul>
                <button wire:click.prevent="nextWeek()" class="arrow right"></button>

            </div>

            <div class="row ml-0 mr-0">
                @foreach ($daySlots as $index => $daySlot)
                    <ul class="time-slot">
                        @foreach ($daySlot['timeslots'] as $timeslot)
                            <li @if (isset($timeslot['student'])) data-toggle="tooltip" data-placement="right" title="{{ $timeslot['student'] }}" @endif
                                wire:click.prevent="selectTime('{{ $timeslot['date'] }}','{{ $index }}','{{ $timeslot['disabled'] }}','{{ isset($timeslot['reserved']) ? $timeslot['reserved'] : false }}') "
                                class="time-slot-item @if (isset($selectedTimes[$index]) && $selectedTimes[$index] == $timeslot['date']) picked @endif    @if ($timeslot['disabled']) time-slot-item-disabled @endif @if (isset($timeslot['reserved']) && $timeslot['reserved']) time-slot-item-reserved @endif  ">
                                {{ $timeslot['date'] }}</li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </div>
        <div class="d-flex flex-column mt-2 justify-content-center align-items-center w-100">
            <button wire:click.prevent='setCollapsed()' class="btn btn-sm btn-primary mt-2">Show @if ($collapsed)
                    Less
                @else
                    More
                @endif </button>
        </div>
    </div>



</div>
