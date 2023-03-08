<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lesson;
use Carbon\Carbon;

class MeetingReminderBefore1Hour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:lesson';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $upcomingLessons = Lesson::with('teacher')->where('status','!=','ended')
      ->where('status','!=','canceled')
      ->where('status','!=','started')
      ->whereDate('meeting_start', \Carbon\Carbon::today())
      ->whereDate('meeting_start', '>=', \Carbon\Carbon::now())->get();

      foreach ($upcomingLessons as $upcomingLesson) {
        if(\Carbon\Carbon::parse($upcomingLesson->meeting_start)->diffInHours(\Carbon\Carbon::now()) <= 1){
          $notifyOptions = ['time.date' => Carbon::parse($upcomingLesson['meeting_start'])->setTimeZone($upcomingLesson->teacher->timezone ?? 'UTC')->format("H:i"),'instructor.name' => $upcomingLesson->teacher->full_name,'student.name' => $upcomingLesson->student->full_name];
          $notifyOptionsForStudent = ['time.date' => Carbon::parse($upcomingLesson['meeting_start'])->setTimeZone($upcomingLesson->student->timezone ?? 'UTC')->format("H:i"),'instructor.name' => $upcomingLesson->student->full_name, 'student.name' => $upcomingLesson->student->full_name ];
          sendNotification('meeting_reserve_reminder', $notifyOptionsForStudent, $upcomingLesson->student_id);
          sendNotification('meeting_reserve_reminder_teacher', $notifyOptions, $upcomingLesson->teacher_id);
        }
      }
    }
}
