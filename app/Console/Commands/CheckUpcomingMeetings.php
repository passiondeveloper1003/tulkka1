<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lesson;

class CheckUpcomingMeetings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:upcoming:meetings';

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
        //upcoming meeting notifications
        $upcomingLessons = Lesson::with('teacher')->where('status','!=','ended')
        ->where('status','!=','canceled')
        ->whereDate('meeting_start', \Carbon\Carbon::today())
        ->whereDate('meeting_start', '>=', \Carbon\Carbon::now())->get();
        foreach ($upcomingLessons as $upcomingLesson) {
            $notifyOptions = ['time.date' => $upcomingLesson['meeting_start'],'instructor.name' => $upcomingLesson->teacher->full_name];
            sendNotification('meeting_reserve_reminder', $notifyOptions, $upcomingLesson->student_id);
            sendNotification('meeting_reserve_reminder', $notifyOptions, $upcomingLesson->teacher_id);
        }
    }
}
