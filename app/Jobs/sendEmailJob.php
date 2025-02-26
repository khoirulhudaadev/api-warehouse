<?php

namespace App\Jobs;

use App\Mail\PasswordResetMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Request;

class sendEmailJob implements ShouldQueue
{
    use Queueable;

    protected $email;
    protected $token;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new PasswordResetMail($this->email, $this->token));
    }
}
