<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Livewire\Component;
use App\TeacherDisabledDate;
use App\Lesson;

class DashboardCalendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $daySlots;
    public $timeSlots;
    public $collapsed = false;
    public $disabledDates;
    public $weekCounter = 0;
    public $selectedTimes = [];
    public $userSubscription;
    public $authUser;
    public $showIsEvery = false;
    public $instructor;
    public $showEnableTime = false;
    public $showDisableButton = false;
    public $reservedDates;
    public $lessonType = 15;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($instructor)
    {
        $this->authUser = auth()->user();
        $this->disabledDates = TeacherDisabledDate::where('teacher_id', $instructor->id)->get();
        $this->reservedDates = Lesson::with('student')->where('teacher_id', $instructor->id)->get();
        $this->daySlots = $this->getDaySlotsForAccount(true);


        $this->instructor = $instructor;
    }

    public function roundToNearestInterval(\DateTime $dateTime, $minuteInterval = 10)
    {
        return $dateTime->setTime(
            $dateTime->format('H'),
            ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval,
            0
        );
    }

    public function weekDaysBetween($requiredDays, $start, $end)
    {
        $startTime = $start;
        $endTime = $end;

        $result = [];

        while ($startTime->lt($endTime)) {
            if (in_array($startTime->dayOfWeek, $requiredDays)) {
                array_push($result, $startTime->copy());
            }

            $startTime->addDay();
        }

        return $result;
    }

    public function getDaySlotsForAccount($filtered = false)
    {
        $this->disabledDates = TeacherDisabledDate::where('teacher_id', $this->instructor->id)->get();
        $this->reservedDates = Lesson::with('student')->where('teacher_id', $this->instructor->id)->get();
        $user = $this->authUser;
        $lesson_type = '';
        if ($user) {
            $this->userSubscription = \App\UserSubscription::where('user_id', $user->id)->get()->first();
            if ($this->userSubscription) {
                $lesson_type = $this->userSubscription->how_often;
            }
        }
        $slots = [];
        $startDay = Carbon::now();
        if ($this->weekCounter > 0) {
            $startDay = $startDay->addWeeks($this->weekCounter);
        }
        $this->currentMonth = $startDay->format('F');
        $this->currentYear = $startDay->format('Y');
        $endDay = Carbon::now()->addDays(6);
        if ($this->weekCounter > 0) {
            $endDay = $endDay->addWeeks($this->weekCounter);
        }
        $days = $this->weekDaysBetween([0,1,2,3,4,5,6], $startDay, $endDay);
        foreach ($days as $day) {
            $slots[$day->format('d-M-Y')]['datename'] = $day->format('D');
            $slots[$day->format('d-M-Y')]['date'] = $day->format('d');
            $nearestInterval = 25;
            if ($lesson_type == '25 min/lesson') {
                $nearestInterval = 25;
            }
            if ($lesson_type == '40 min/lesson') {
                $nearestInterval = 40;
            }
            if ($lesson_type == '55 min/lesson') {
                $nearestInterval = 55;
            }
            $nearestInterval = 15;
            $start = $day->startOfDay()->addHours(10);
            $end = $day->copy()->endOfDay();
            $intervals = CarbonInterval::minutes($nearestInterval)->toPeriod($start, $end);
            $counter = 0;
            foreach ($intervals as $index => $date) {
                if ($filtered && $counter == 5) {
                    continue;
                }
                $slots[$day->format('d-M-Y')]['timeslots'][] = ['date' => $date->format('H:i'),'disabled' => false];
                foreach ($this->disabledDates as $disabledDateTable) {
                    $createdHour = $date->format('H');
                    $disabledStart = Carbon::parse($disabledDateTable->time_start)->setTimeZone($this->authUser->timezone);
                    $disabledEnd = Carbon::parse($disabledDateTable->time_end)->setTimeZone($this->authUser->timezone);

                    $disabledDates = CarbonPeriod::create($disabledDateTable->date_start, $disabledDateTable->date_end);
                    $disabledHours = CarbonInterval::minutes($nearestInterval)->toPeriod($disabledStart, $disabledEnd);
                    foreach ($disabledDates as $disabledDate) {
                        foreach ($disabledHours as $disabledHour) {
                            if ($disabledHour->format('H') == $createdHour && $disabledDateTable->is_every) {
                                $slots[$day->format('d-M-Y')]['timeslots'][$index] = ['date' => $date->format('H:i'),'disabled' => true];
                            }
                            if ($day->format('d-M-Y') == $disabledDate->format('d-M-Y') && $disabledHour->format('H') == $createdHour) {
                                $slots[$day->format('d-M-Y')]['timeslots'][$index] = ['date' => $date->format('H:i'),'disabled' => true];
                            }
                        }
                    }
                }
                foreach ($this->reservedDates as $reservedDate) {
                    $reservedStart = Carbon::parse($reservedDate->meeting_start)->setTimeZone($this->authUser->timezone);
                    $reservedEnd = Carbon::parse($reservedDate->meeting_end)->setTimeZone($this->authUser->timezone);
                    $reservedHours = CarbonInterval::minutes($this->lessonType)->toPeriod($reservedStart->format('H:i:s'), $reservedEnd->format('H:i:s'));
                    foreach ($reservedHours as $reservedHour) {
                        if ($reservedHour->format('H:i') == $reservedEnd->format('H:i')) {
                            continue;
                        }
                        if (isset($slots[$reservedStart->format('d-M-Y')]['timeslots'][$index]) && $reservedHour->format('H:i') == $date->format('H:i')) {
                            $slots[$reservedStart->format('d-M-Y')]['timeslots'][$index]['reserved'] = true;
                            $slots[$reservedStart->format('d-M-Y')]['timeslots'][$index]['disabled'] = true;
                            $slots[$reservedStart->format('d-M-Y')]['timeslots'][$index]['student'] = $reservedDate->student->full_name ;
                        }
                    }
                }
                $counter++;
            }
        }
        return $slots;
    }

    public function setCollapsed()
    {
        $this->collapsed = !$this->collapsed;
        if ($this->collapsed) {
            $this->daySlots = $this->getDaySlotsForAccount();
            return;
        }
        $this->daySlots = $this->getDaySlotsForAccount(true);
    }

    public function nextWeek()
    {
        $this->weekCounter++;
        if ($this->collapsed) {
            $this->daySlots = $this->getDaySlotsForAccount();
            return;
        }
        $this->daySlots = $this->getDaySlotsForAccount(true);
    }
    public function beforeWeek()
    {
        if ($this->weekCounter == 0) {
            return;
        }
        $this->weekCounter--;
        if ($this->collapsed) {
            $this->daySlots = $this->getDaySlotsForAccount();
            return;
        }
        $this->daySlots = $this->getDaySlotsForAccount(true);
    }

    public function selectTime($time, $date, $isDisabled = false, $isReserved = false)
    {
        if ($isReserved) {
            return;
        }
        if (isset($this->selectedTimes[$date]) && $this->selectedTimes[$date] == $time) {
            unset($this->selectedTimes[$date]);
            $this->showEnableTime = false;
            $this->showIsEvery = false;
            $this->showDisableButton = false;
            return;
        }
        if (count($this->selectedTimes) > 0) {
            return;
        }
        $founded = false;
        if ($isDisabled != '' || $isDisabled) {
            foreach ($this->daySlots[$date]['timeslots'] as $timeslot) {
                if ($timeslot['date'] == $time) {
                    $this->showEnableTime = true;
                    $founded = true;
                }
            }
        }
        if ($founded == false) {
            $this->showEnableTime = false;
        }
        $this->selectedTimes[$date] = $time;
        if (count($this->selectedTimes) == 1 && !$this->showEnableTime) {
            $this->showIsEvery = true;
            $this->showDisableButton = true;
        }
        if (count($this->selectedTimes) == 0 && !$this->showEnableTime) {
            $this->showIsEvery = false;
            $this->showDisableButton = false;
        }
    }



    public function disableSelected($isEvery = 0)
    {
        foreach ($this->selectedTimes as $date => $selectedTime) {
            $date_end = \Carbon\Carbon::parse($date, $this->authUser->timezone);
            if ($isEvery) {
                $date_end = $date_end->addYears(1)->setTimeZone(config('app.timezone'));
            }
            $disabled_dates = TeacherDisabledDate::create([
              'teacher_id' => $this->instructor->id,
              'date_start' => \Carbon\Carbon::parse($date),
              'date_end' => $date_end,
              'time_start' => \Carbon\Carbon::parse($selectedTime, $this->authUser->timezone)->setTimeZone(config('app.timezone'))->format("H:i"),
              'time_end' => \Carbon\Carbon::parse($selectedTime, $this->authUser->timezone)->setTimeZone(config('app.timezone'))->format("H:i"),
              'is_every' => $isEvery
            ]);
            $this->emit('timeDisabled');
        }
        $this->selectedTimes = [];
        $this->showDisableButton = false;
        $this->showIsEvery = false;
        $this->showEnableTime = false;
        $this->daySlots = $this->getDaySlotsForAccount(!$this->collapsed);
    }

    public function enableSelected($isEvery = 0)
    {
        foreach ($this->selectedTimes as $date => $selectedTime) {
            $date_start = \Carbon\Carbon::parse($date)->setTimeZone(config('app.timezone'))->format('Y-m-d');
            $time_start = \Carbon\Carbon::parse($selectedTime, $this->authUser->timezone)->setTimeZone(config('app.timezone'))->format('H:i');
            $disabled_dates = null;
            if ($isEvery) {
                $disabled_dates = TeacherDisabledDate::where('is_every', 1)->get();
                if (!empty($disabled_dates)) {
                    foreach ($disabled_dates as $disableddate) {
                        if (\Carbon\Carbon::parse($disableddate->time_start)->format('H') == (\Carbon\Carbon::parse($selectedTime, $this->authUser->timezone)->setTimeZone(config('app.timezone')))->format('H')) {
                            $disableddate->delete();
                        }
                    }
                }
                if ($disabled_dates) {
                    $this->emit('timeEnabled');
                }
            } else {
                $disabled_dates = TeacherDisabledDate::where('is_every', 0)
                ->where('date_start', $date_start)
                ->where('time_start', $time_start)
                ->delete();
                $this->emit('timeEnabled');
            }
        }

        $this->selectedTimes = [];
        $this->showDisableButton = false;
        $this->showIsEvery = false;
        $this->showEnableTime = false;
        $this->daySlots = $this->getDaySlotsForAccount(!$this->collapsed);
    }



    public function render()
    {
        return view('livewire.dashboard-calendar');
    }
}
