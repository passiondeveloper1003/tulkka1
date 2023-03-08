<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserSubscription;
use App\User;

class SubscriptionControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'control:subscriptions';

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
        $subscriptions = UserSubscription::where('renew_date', '<=', \Carbon\Carbon::now())->get();

        foreach ($subscriptions as $subscription) {
            $user = User::where('id', $subscription->user_id)->get()->first();
            $user->subscription_type = '';
            $user->save();
            $subscription->delete();
        }
        return 0;
    }
}
