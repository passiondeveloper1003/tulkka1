<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserSubscription;

class WeeklySubscriptionReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:reset';

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
      $subscriptions = UserSubscription::all();
      foreach($subscriptions as $subscription){
        $subscription->weekly_comp_class = 0;
        $subscription->save();
      }
        return 0;
    }
}
