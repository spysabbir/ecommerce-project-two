<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewsletterSendJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $newsletter_id;

    public function __construct($newsletter_id)
    {
        $this->newsletter_id = $newsletter_id;
    }

    public function handle()
    {
        $newsletter = Newsletter::find($this->newsletter_id);
        if ($newsletter->received_by == "All Subscriber") {
            foreach(Subscriber::where('status', 'Yes')->get() as $subscriber){
                Mail::to($subscriber->subscriber_email)
                ->send(new NewsletterMail($newsletter));
            }
        } else {
            foreach(User::where('status', 'Yes')->get() as $user){
                Mail::to($user->email)
                ->send(new NewsletterMail($newsletter));
            }
        }
    }
}
