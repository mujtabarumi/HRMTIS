<?php

namespace App\Mail;


use App\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use DB;

class LeaveApplied extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $emp;

    public function __construct($emp)
    {
        $this->emp = $emp;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('Leave Applied in HRMTIS notification!')->view('Email.LeaveApplied')->with(['emp' => $this->emp]);
    }
}