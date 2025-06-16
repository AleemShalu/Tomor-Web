<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $bodyAr;
    public $bodyEn;

    /**
     * Create a new message instance.
     */
    public function __construct($title, $bodyAr, $bodyEn)
    {
        $this->title = $title;
        $this->bodyAr = $bodyAr;
        $this->bodyEn = $bodyEn;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title,
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('emails.custom_email') // Replace 'emails' with the appropriate directory if needed
            ->with([
                'title' => $this->title,
                'bodyAr' => $this->bodyAr,
                'bodyEn' => $this->bodyEn,
            ]);
    }

}
