<?php

namespace App\Jobs;

use App\Client;
use App\Mail\ClientNotification;
use App\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail, Log;
use Illuminate\Database\Eloquent\Collection;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;
    public $timeStart = 0;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message, $timeStart)
    {
        //
        $this->message = $message;
        $this->timeStart = $timeStart;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Client::select('email')->chunk(1000, function ($clients) {
            foreach ($clients as $client) {
                $notification = new ClientNotification($this->message, $client);
                $time = now()->setTimezone('UTC')->timestamp-$this->timeStart;
                $client->notify($notification)
                    ->delay($time + $client->minutes);
            }
        });

    }
}
