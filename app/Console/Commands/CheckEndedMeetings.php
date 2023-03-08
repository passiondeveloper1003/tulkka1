<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lesson;
use App\UserSubscription;
use App\User;

class CheckEndedMeetings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:ended:meetings';

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
        //ended meetings
        $endedLessons = Lesson::whereDate('meeting_end', '<', \Carbon\Carbon::now())->where('status','!=','canceled')->get();
        foreach ($endedLessons as $lesson) {
            $lesson->status = 'ended';
            $lesson->save();
            $subs = UserSubscription::where('user_id', $lesson->student_id)->get()->first();
            if ($lesson->is_trial) {
                $user = User::where('id', $lesson->student_id)->get()->first();
                $user->trial_expired = 1;
                $user->save();
            }
            $user = User::where('id', $lesson->student_id)->get();
            if ($subs) {
                $subs->weekly_comp_class++;
                $subs->save();
            }
        }
    }
}
