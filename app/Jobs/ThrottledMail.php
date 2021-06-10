<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

// * php artisan make:job ThrottledMail
// https://laravel.com/docs/8.x/queues#max-exceptions
// episode 224 Rate limit queue, see .env QUEUE_CONNECTION=redis
// * run "php artisan queue:failed" see failed_job table list if don't have one
class ThrottledMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 15;
    public $timeout = 10;

    public $user;
    public $mail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mailable $mail, User $user)
    {
        $this->mail = $mail; // what class files in Mail/* to send
        $this->user = $user; // which user to send
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // allow(1) mean 1 time for every(12) mean 12 seconds
        // https://laravel.com/docs/8.x/queues#max-exceptions
        // mailtrap from https://mailtrap.io/inboxes/1342722/messages
        // * remember change QUEUE_CONNETION=redis inside .env
        Redis::throttle('mailtrap')->allow(1)->every(12)->then(function () {
            Mail::to($this->user)->send($this->mail);
        }, function () {
            // Could not obtain lock...
            return $this->release(5); // delay parameter for retry every 5 seconds
        });
    }
}
