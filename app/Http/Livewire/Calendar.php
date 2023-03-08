<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Livewire\Component;
use App\TeacherDisabledDate;
use Illuminate\Support\Facades\Log;
use App\Lesson;
use App\Jobs\SendNotificationJob;
use Spatie\IcalendarGenerator\Components\Calendar as SpatieCalendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\CalendarLinks\Link as CalendarLink;

class Calendar extends Component
{
    public $calendarHourMinutesInterval = 30;
    public $minimumPackageMinutes = 25;
    public $showCalendar = true;
    public $currentMonth;
    public $currentYear;
    public $daySlots;
    public $timeSlots;
    public $collapsed = 0;
    public $disabledDates;
    public $weekCounter = 0;
    public $selectedTimes = [];
    public $userSubscription;
    public $authUser;
    public $instructor;
    public $meetingDetails = [];
    public $lessonType = 25;
    public $reservedDates;
    public $student_id;
    public $teacher_id;
    public $lesson_id;
    public $grammar;
    public $pronunciation;
    public $comment;
    public $speaking;
    public $notes;
    public $goals;
    public $dashboard;
    public $isTrial;
    public $booking;
    public $loopkey;
    public $isProfile;
    public $reschedule;

    protected $listeners = ['showDateCalendar' => 'changeShowCalendar', 'sendGoalData' => 'getGoalData','refreshComponent' => '$refresh','bookingCompleted' => 'getDaySlotsForAccount'];
    protected $queryString = [
      'booking'
  ];

    public function changeShowCalendar($key)
    {
        if ($this->loopkey == $key) {
            $this->showCalendar = !$this->showCalendar;
        }
    }

    public function getGoalData($goals = null, $notes = null, $instructor_id = null, $trial = 0)
    {
        if ($instructor_id == $this->instructor->id) {
            $this->goals = $goals;
            $this->notes = $notes;
            $this->handleBookNow($trial);
        }
    }

    public function mount($instructor, $dashboard = false, $loopkey = null, $calledFrom = null, $reschedule = null)
    {
        $this->isProfile = $calledFrom;
        $this->authUser = auth()->user();
        $this->instructor = $instructor;
        $this->daySlots = $this->getDaySlotsForAccount(true);
        $this->dashboard = $dashboard;
        $this->reschedule = $reschedule;
        $this->loopkey = $loopkey;
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
        $this->reservedDates = Lesson::where('teacher_id', $this->instructor->id)->get();
        $user = $this->authUser;
        $lesson_type = '';
        if ($user) {
            $this->userSubscription = \App\UserSubscription::where('user_id', $user->id)->get()->first();
            if ($this->userSubscription) {
                $lesson_type = $this->userSubscription->how_often;
                $this->lessonType = intval(substr($lesson_type, 0, 2));
            }
        }
        $slots = [];
        $startDay = Carbon::now()->setTimeZone($user->timezone ?? 'UTC');
        if ($this->weekCounter > 0) {
            $startDay = $startDay->addWeeks($this->weekCounter)->setTimeZone($user->timezone ?? 'UTC');
        }
        $this->currentMonth = $startDay->format('F');
        $this->currentYear = $startDay->format('Y');
        $endDay = Carbon::now()->addDays(6)->setTimeZone($user->timezone ?? 'UTC');
        if ($this->weekCounter > 0) {
            $endDay = $endDay->addWeeks($this->weekCounter);
        }
        $days = $this->weekDaysBetween([0,1,2,3,4,5,6], $startDay, $endDay);

        foreach ($days as $day) {
            $counter = 0;
            $slots[$day->format('d-M-Y')]['datename'] = $day->format('D');
            $slots[$day->format('d-M-Y')]['date'] = $day->format('d');
            $start = $day->startOfDay()->addHours(8);
            $end = $day->copy()->endOfDay();


            $intervals = CarbonInterval::minutes($this->calendarHourMinutesInterval)->toPeriod($start, $end);
            foreach ($intervals as $index => $date) {
                if ($date->diffInDays(\Carbon\Carbon::now()) == 0 && $date->lt(\Carbon\Carbon::now())) {
                    continue;
                }
                $slots[$day->format('d-M-Y')]['timeslots'][] = ['date' => $date->format('H:i'),'disabled' => false];
                foreach ($this->disabledDates as $disabledDateTable) {
                    $createdHour = $date->format('H');
                    $disabledStart = Carbon::parse($disabledDateTable->time_start)->setTimeZone($user->timezone ?? 'UTC');
                    $disabledEnd = Carbon::parse($disabledDateTable->time_end)->setTimeZone($user->timezone ?? 'UTC');
                    $disabledDates = CarbonPeriod::create($disabledDateTable->date_start, $disabledDateTable->date_end);
                    $disabledHours = CarbonInterval::minutes($this->minimumPackageMinutes)->toPeriod($disabledStart, $disabledEnd);
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
                    $reservedStart = Carbon::parse($reservedDate->meeting_start)->setTimeZone($user->timezone ?? 'UTC');
                    $reservedEnd = Carbon::parse($reservedDate->meeting_end)->setTimeZone($user->timezone ?? 'UTC');
                    $reservedHours = CarbonInterval::minutes($this->minimumPackageMinutes)->toPeriod($reservedStart->format('H:i:s'), $reservedEnd->format('H:i:s'));
                    foreach ($reservedHours as $reservedHour) {
                        if ($reservedHour->format('H:i') == $reservedEnd->format('H:i')) {
                            continue;
                        }
                        if (isset($slots[$reservedStart->format('d-M-Y')]['timeslots'][$index]) && $date->between($reservedStart, $reservedEnd)) {
                            $slots[$reservedStart->format('d-M-Y')]['timeslots'][$index]['disabled'] = true;
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
        if ($this->collapsed == 1) {
            $this->collapsed = 0;
        } else {
            $this->collapsed = 1;
        }
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

    public function selectTime($time, $date)
    {
        if (isset($this->selectedTimes[$date]) && $this->selectedTimes[$date] == $time) {
            unset($this->selectedTimes[$date]);
            return;
        }
        $this->selectedTimes = [];
        $this->selectedTimes[$date] = $time;
    }

    public function redirectLogin()
    {
        return redirect()->to('/login');
    }

    public function handleBookNow($trial=0)
    {
        $this->isTrial = $trial;
        $user = $this->authUser;
        $lessonsCount = $user->lessonsForStudent()->where('status', '!=', 'canceled')->where('is_trial', 1)->count();

        if ($this->isTrial && $lessonsCount > 0) {
            $this->emit('trialUsed');
            return;
        }

        if ($user->firstLessonWithTeacher($this->instructor->id) && (!$this->goals)) {
            $this->emit('showGoalModal', $this->instructor->id, $trial);
            return;
        }

        $goals = null;
        if (isset($this->goals) && count($this->goals) > 0) {
            $goals = implode(',', $this->goals);
        }

        foreach ($this->selectedTimes as $date => $selectedTime) {
            $topic = $this->instructor->full_name . ' Lesson';
            $meetingTime = Carbon::createFromFormat('d-M-Y H:i', $date . ' ' . $selectedTime, $user->timezone ?? 'UTC')->setTimeZone(config('app.timezone'));


            if (!$this->isTrial && $user->isLongerThanSubscription($meetingTime->copy())) {
                $this->selectedTimes = [];
                $this->emit('longerThanSubscription');
                return;
            }
            if (!$this->isTrial && $user->isAllWeeklyReserved($meetingTime->copy())) {
                $this->selectedTimes = [];
                $this->emit('weeklyCompleted');
                return;
            }



            $meetingStartForDB = $meetingTime->format("Y-m-d\TH:i:s");
            $meetingEndForDB = $meetingTime->addMinutes($this->lessonType)->format("Y-m-d\TH:i:s");
            $meetingTime = $meetingTime->setTimeZone($user->timezone ?? 'UTC')
            ->format("Y-m-d\TH:i:s");
            try {
                $meetingDetails = ['topic' => $topic, 'start_time' => $meetingTime, 'agenda' => ''];
                $meetingDetailsForDB = ['meeting_start' => $meetingStartForDB,'meeting_end' => $meetingEndForDB,'teacher_id' => $this->instructor->id,'student_id' => $this->authUser->id];

                if ($this->isTrial) {
                    $meetingDetailsForDB['is_trial'] = 1;
                } else {
                    $meetingDetailsForDB['is_trial'] = 0;
                }
                $meetingDetailsForDB['student_goal'] = $goals;
                $meetingDetailsForDB['student_goal_note'] = $this->notes;
                $lesson = Lesson::create($meetingDetailsForDB);
                $classes_url = url('panel/meetings/reservation');
                $notifyOptions = ['instructor.name' => $this->instructor->full_name,'time.date' => Carbon::parse($meetingStartForDB)->setTimeZone($user->timezone ?? 'UTC')->format("d-M-Y H:i"),'link' => $classes_url,'student.name' => $this->authUser->full_name];
                $notifyOptionsTeacher = ['student.name' => $this->authUser->full_name,'time.date' => Carbon::parse($meetingStartForDB)->setTimeZone($user->timezone ?? 'UTC')->format("d-M-Y H:i"),'link' => $classes_url];
                dispatch(new SendNotificationJob('lesson_booked', $notifyOptionsTeacher, $this->instructor->id));
                dispatch(new SendNotificationJob('lesson_booked_student', $notifyOptions, $this->authUser->id));
                $from = Carbon::parse($meetingStartForDB);
                $to = Carbon::parse($meetingEndForDB);

                $link = CalendarLink::create('Tulkka Lesson with ' . $this->instructor->full_name, $from, $to)
                    ->description('Tulkka Lesson')
                    ->address('Zoom');

                $google = $link->google();
                $outlook = $link->webOutlook();
                $this->emit('openAddToCalendar', $google, $outlook);
            } catch(\Exception $err) {
                Log::error($err);
                $this->emit('bookingError');
                $this->selectedTimes = [];
            }
        }
    }


    public function sendFeedback()
    {
        $feedback = [
        'teacher_id' => $this->authUser->id,
        'student_id' => $this->student_id,
        'lesson_id' => $this->lesson_id,
        'grammar' => $this->grammar,
        'pronunciation' => $this->pronunciation,
        'speaking' => $this->speaking,
        'comment' => $this->comment,
        'grammar_rate' => $this->grammarRate,
        'pronunciation_rate' => $this->pronunciationRate,
        'speaking_rate' => $this->speakingRate,
    ];
        $lesson_feedback = LessonFeedback::create($feedback);
        $notifyOptions = [

        ];
        dispatch(new SendNotificationJob('feedback_recieved', $notifyOptions, $this->student_id));

        $this->emit('feedbackSent');
        $this->doClose();
    }
    public function render()
    {
        return view('livewire.calendar');
    }
}
