<?php

namespace App\Console\Commands;

use App\Message;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class SendMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:send';

    /** @var int maimum count of pararell processes (use count cores on processor) */
    protected $maxProcesses = 4;


    private $clients = null;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send messages by schedule';

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
        $listOfMail = [];
        $date = now()->setTimezone('UTC');
        $timeStart = $date->format('H:i:00');
        $timeEnd = $date->add('5 minutes')->format('H:i:59');
        $messages = Message::all();
        foreach ($messages as $message) {
            $messageEl = $message->timestartAt($timeStart, $timeEnd);
            if ($messageEl->count() > 0) {
                $this->startProcesses($message->id);
            }
        }
    }

    private function startProcesses($messageID)
    {
        $processes = [];
        for ($i = 0; $i < 23; $i++) {

            if (count($processes) < $this->maxProcesses) {
                $process = new Process(['php', './artisan', 'send:message', $messageID, $i]);
                $process->setTimeout(0);
                $process->start();
                $processes[] = $process;
            }
            while (count($processes) >= $this->maxProcesses) {
                foreach ($processes as $i => $runningProcess) {
                    if (!$runningProcess->isRunning()) {
                        unset($processes[$i]);
                    }
                    sleep(1);
                }
            }
        }

        while (count($processes)) {
            foreach ($processes as $i => $runningProcess) {
                if (!$runningProcess->isRunning()) {
                    unset($processes[$i]);
                }
                sleep(1);
            }
        }


    }
}
