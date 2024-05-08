<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $users = User::where('created_at', '>=', now()->subDays(7))->get();

        // Check if there are new users before sending the email
        if ($users->isEmpty()) {
            return $this->subject('No New Users')->view('emails.invitation');
        }

        return $this->subject('Welcome New Users')->view('emails.invitation')->with(['users' => $users]);
    }
}
