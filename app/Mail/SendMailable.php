<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($module, $errordesc, $level)
    {
        $this->module = $module;
        $this->errordesc = $errordesc;
        $this->level = $level;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $module = $this->module;
        $errordesc = $this->errordesc;
        $level = $this->level;

        return $this->view('mail.name')->with(compact('module', 'errordesc', 'level'));
    }
}
