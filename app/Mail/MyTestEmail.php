<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class MyTestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $stockAlerts;

    public function __construct($stockAlerts)
    {
        $this->stockAlerts = $stockAlerts;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('user1@billseva.in', 'SIVAS'),
            subject: 'Stock Alert',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.stock-alert',
            with: ['stockAlerts' => $this->stockAlerts]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
