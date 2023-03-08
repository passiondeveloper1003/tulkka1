<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckEndedMeetings;
use App\Console\Commands\CheckUpcomingMeetings;
use App\Console\Commands\WeeklySubscriptionReset;
use App\Console\Commands\SubscriptionControl;
use App\Console\Commands\MeetingReminderBefore1Hour;
use App\Console\Commands\SetLessonStatuses;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      CheckEndedMeetings::class,
      CheckUpcomingMeetings::class,
      WeeklySubscriptionReset::class,
      SubscriptionControl::class,
      MeetingReminderBefore1Hour::class,
      SetLessonStatuses::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('check:ended:meetings')->everyFiveMinutes();
        $schedule->command('check:upcoming:meetings')->dailyAt('08:00');
        $schedule->command('subscription:reset')->weekly();
        $schedule->command('control:reset')->weekly();
        $schedule->command('reminder:lesson')->everyThirtyMinutes();
        $schedule->command('set:lessons')->everyFiveMinutes();
        $schedule->command('control:subscriptions')->weekly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
