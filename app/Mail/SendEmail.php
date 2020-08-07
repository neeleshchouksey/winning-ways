<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $type;
    public $msg;
    public $sub;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type,$msg,$sub)
    {
        $this->type = $type;
        $this->msg = $msg;
        $this->sub = $sub;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //type=1 = registration
        //type=2 =

       // if($this->type == 1){
            return $this->subject($this->sub)->view('emails.registration');
        //}
    }
}
