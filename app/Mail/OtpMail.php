<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $user;

    public function __construct($otp, $user)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.otp')
                    ->subject('Your Password Reset Verification Code - ' . config('app.name'))
                    ->from(config('mail.from.address'), config('app.name') . ' Security Team')
                    ->with([
                        'otp' => $this->otp,
                        'user' => $this->user,
                        'app_name' => config('app.name')
                    ]);
    }
}
