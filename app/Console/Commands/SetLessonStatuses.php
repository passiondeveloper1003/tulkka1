<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lesson;
use App\UserSubscription;
use App\User;

class SetLessonStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:lessons';

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
      $lessons = Lesson::whereDate('meeting_start', \Carbon\Carbon::today())->where('status','!=','canceled')->where('status','!=','ended')->get();
      foreach($lessons as $lesson){
        if(\Carbon\Carbon::parse($lesson->meeting_start)->diffInHours(\Carbon\Carbon::now()) <= 0){
          $lesson->status = 'started';
          $lesson->save();
        }
        if(\Carbon\Carbon::parse($lesson->meeting_end)->diffInMinutes(\Carbon\Carbon::now()) <= 1){
             if ($lesson->is_trial) {
            $user = User::where('id', $lesson->student_id)->get()->first();
            $user->trial_expired = 1;
            $user->save();
        }
          $lesson->status = 'ended';
          $lesson->save();
        }
    }
  }
}
