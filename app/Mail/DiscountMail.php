<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DiscountMail extends Mailable
{
    use SerializesModels;

    public function __construct()
    {
        //
    }

    public function build()
    {
        return $this->view('emails.discount')
            ->subject('Templet Studio Discount And Free Credit Code For You');
    }
}