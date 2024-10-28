<?php

namespace App\Services;
use Illuminate\Support\Facades\Mail;
use App\Mail\HelloMail;
class MailService
{
    public function sendHelloMail($email,$name)
    {
        Mail::to($email)->send(new HelloMail($name));
    }
}
