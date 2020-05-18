<?php

namespace App\Console\Commands;

use App\Client;
use App\Message;
use App\Notifications\ClientNotify;
use Notification;
use Cache;
use Illuminate\Console\Command;

class SendMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:message {message} {timeshift}';
    private $clients;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command run in paralell processes';

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
     * @return mixed
     */
    public function handle()
    {
        //
        $message = Message::find($this->argument('message'));
        $timeShift = $this->argument('timeshift');
        $dateStart = now();
        $notification = new ClientNotify($message);
        echo "start" . $timeShift;
        Notification::send($this->getClients($timeShift), $notification->delay($dateStart->addHours($timeShift)));
    }

    protected function getClients($shift = 0)
    {
        if (Cache::has('client'.$shift)){
            $this->clients = \Cache::get('client'.$shift);
        }else {
            $this->clients = Client::whereTimeshift($shift)->get();
            Cache::put('client'.$shift,$this->clients,3600);
        }
        return $this->clients;
    }

}
