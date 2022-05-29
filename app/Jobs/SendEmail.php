<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscription;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $notification;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \File::put(base_path('asif.txt'),"");

        $subscriptions = Subscription::where('web_id',$this->notification['web_id'])->with('subsscriber')->get();
        $input['subject'] = $this->notification['title'];
        $input['title'] = $this->notification['title'];
        $input['description'] = $this->notification['description'];
        $input['web'] = $this->notification['web_id'];

        foreach ($subscriptions as $key => $subscription) {
            $input['email'] = $subscription->subsscriber->email;
            $input['name'] = $subscription->subsscriber->name;

            \Mail::send('emails.notifysubscribers', $input, function($message) use($input){
                $message->to($input['email'], $input['name'])
                    ->subject($input['subject']);
            });
        }
    }
}
