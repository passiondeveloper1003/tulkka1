<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $template;
    public $options;
    public $user_id = null;
    public $group_id = null;
    public $sender = 'system';
    public $type = 'single';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($template, $options, $user_id = null, $group_id = null, $sender = 'system', $type = 'single')
    {
        $this->template = $template;
        $this->options = $options;
        $this->user_id = $user_id;
        $this->group_id = $group_id;
        $this->sender = $sender;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Log::debug('first');
        sendNotification(
            $this->template,
            $this->options,
            $this->user_id,
            $this->group_id,
            $this->sender,
            $this->type
        );
    }

    /**
* The job failed to process.
*
* @param  Exception  $exception
* @return void
*/
public function failed(Exception $exception)
{
    Log::debug($exception);
}
}
