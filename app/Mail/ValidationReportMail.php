<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ValidationReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reportPath;

    public function __construct($reportPath)
    {
        $this->reportPath = $reportPath;
    }

    public function build()
    {
        return $this->view('emails.validation_report')
                    ->subject('Validation Report')
                    ->attach($this->reportPath, [
                        'as' => 'validation_report.xlsx',
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
    }
}
