<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifySubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:usersAboutPost {data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Whenever a new post is added to any website notify its subscribers';

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
        $data = $this->argument('data');
        $NotifySubscribers = (new \App\Jobs\SendEmail($data))
        ->delay(now()->addSeconds(1));

        dispatch($NotifySubscribers);
    }
}
