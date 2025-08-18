<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EmailVerification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationToken;
    public $additionalParams;

    public function __construct(User $user, $verificationToken = null, $additionalParams = [])
    {
        $this->user = $user;
        if (isset($verificationToken)) {
            $this->verificationToken = $verificationToken;
        } else {
            $this->verificationToken = $user->email_verification_token;
        }
        $this->additionalParams = $additionalParams;
    }

    public function build()
    {
        $data = [
            'verificationToken' => $this->verificationToken,
            'user' => $this->user
        ];

        // Add any additional parameters
        foreach ($this->additionalParams as $key => $value) {
            $data[$key] = $value;
        }

        return $this->view('emails.verify-email')
                    ->subject('Verify Your Email Address')
                    ->with($data);
    }
}
