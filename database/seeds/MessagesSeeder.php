<?php

use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i=0; $i<100; $i++){
            factory(App\Message::class, 100)->create()
            ->each(function ($message){
                $message->timestart()->saveMany(
                    factory(App\MessageTime::class, rand(1,5))->make()
                );
            });
        }
    }
}
