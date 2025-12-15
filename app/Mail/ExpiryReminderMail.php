<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiryReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $record;
    public $module;
    public $mailType;

    public function __construct($record, $module, $mailType)
    {
        $this->record   = $record;
        $this->module   = $module;
        $this->mailType = $mailType;
    }

    public function build()
    {
        $subjectMap = [
            'one_month' => "{$this->module} Expiry Reminder – 1 Month Remaining",
            'one_week'  => "{$this->module} Expiry Reminder – 1 Week Remaining",
            'last_day'  => "{$this->module} Expiring Today",
        ];

        return $this->subject($subjectMap[$this->mailType] ?? 'Expiry Reminder')
            ->view('emails.expiry-reminder');
    }
}
