<div class="w-100">
  <div class="time-picker-container border-top border-gray300 ">
      <div class="border-bottom border-gray300 d-flex flex-row align-items-center  pt-2 pb-2">
          <p class="pb-2 pt-2">{{trans('update.select_meeting_time')}}</p>
          @if (isset($authUser) && $authUser->isPaidUser() && count($selectedTimes) > 0)
              <button id="book-now" wire:click.prevent='handleBookNow()'
                  class="btn btn-sm btn-primary ml-4 animate__animatedbook animate__heartBeat">{{trans('update.book_now')}}</button>
          @endif
          @if (isset($authUser) && !$authUser->trial_expired & count($selectedTimes) > 0)
              <button id="trial-now" wire:click.prevent='handleTrial()'
                  class="btn btn-sm btn-primary ml-4 animate__animatedtrial animate__heartBeat">{{trans('update.book_for_trial')}}</button>
          @endif
      </div>
      <h4 id="curr-month" class="pt-2 pb-2">{{ $currentMonth }} {{ $currentYear }}</h4>

      <div class="time-picker">
          <div class="time-picker-header">
              <button wire:click.prevent="beforeWeek()" class="arrow left inactive"></button>
              <ul class="date-slot d-flex">
                  @foreach ($daySlots as $daySlot)
                      <li class="date-slot-wrapper">
                          <div class="date-slot-item active rounded @if($loop->first)
                            today
                            @endif">
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
                          <li @if (!isset($authUser)) wire:click="redirectLogin()" @endif
                              @if (isset($authUser) && $authUser->trial_expired && !$authUser->isPaidUser() && !$authUser->isTeacher()) onclick='Livewire.emit("showModal","SomeData")' @endif
                              @if (!$timeslot['disabled']) wire:click.prevent="selectTime('{{ $timeslot['date'] }}','{{ $index }}') @endif "
                              class="time-slot-item @if (isset($selectedTimes[$index]) && $selectedTimes[$index] == $timeslot['date']) picked @endif    @if ($timeslot['disabled']) time-slot-item-disabled @endif ">
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

  <style>
      .today{
        background:#43d477 !important;
        color: white !important;
      }
      .time-slot-item-disabled:hover,
      .time-slot-item-disabled {
          cursor: not-allowed ! important;
          color: #cddadf !important;
      }

      .time-picker {
          margin: 0 -5px;
      }

      .time-picker-header {
          position: relative;
      }

      .date-slot {
          list-style: none;
          padding: 0;
      }

      .date-slot-wrapper {
          width: 14.285%;
          text-align: center;
          float: left;
          font-size: 12px;
      }

      .date-slot-item {
          margin: 0 6px 10px;
          border: 1px solid transparent;
      }

      .date-slot-item.active {
          //border-bottom: 1px solid #0279b3;
          background-color: #f2f6f8;
      }

      .date-slot-item.no-free-slot>* {
          color: #666;
      }

      .date-slot-day,
      .date-slot-date {
          display: block;
      }

      .date-slot-day {
          line-height: 24px;
          color: #666;
      }

      .date-slot-date {
          color: #000;
      }

      .arrow {
          position: absolute;
          top: 15px;
          height: 20px;
          width: 20px;
          background: #c0bebe;
          border-radius: 50%;
          border: none;
          -webkit-appearance: none;
      }

      .arrow.left {
          left: -20px;

      }

      .arrow.left:after {
          content: '\f104';
          left: -1px;
          top: -3px;
      }

      .arrow.right {
          right: -20px;
      }

      .arrow.right:after {
          content: '\f105';
          right: -1px;
          top: -3px;
      }

      .arrow:after {
          font-family: fontawesome;
          font-size: 14px;
          position: relative;
      }

      .arrow.inactive {
          opacity: 0.4;
      }

      .arrow:focus {
          outline: none;
      }

      .time-slot {
          list-style: none;
          padding: 0;
          float: left;
          display: inline-block;
          width: 14.285%;
          font-size: 12px;
      }

      .time-slot-item {
          font-size: 12px;
          line-height: 22px;
          color: #0279b3;
          border-radius: 4px;
          padding: 5px;
          margin: 5px 12px;
          text-align: center;
          cursor: pointer;
      }

      .time-slot-item:hover,
      .time-slot-item.picked {
          background: #0279b3;
          border-color: #0279b3;
          color: #FFF;
      }

      .time-picker-container {
          background: #FFF;
          padding: 10px 40px 0px 40px;
          width: 100%;
      }
  </style>

</div>
