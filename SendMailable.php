<?php

namespace App\Http\Controllers;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $forgotpassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($forgotpassword)
    {
        $this->forgotpassword = $forgotpassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('forgotpassword');
    }
}