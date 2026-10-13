<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\TicketPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TicketsMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing('event', 'tickets');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Vos billets pour '.$this->order->event->title);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.tickets');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => TicketPdf::make($this->order)->output(), 'billets-sunuticket.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
