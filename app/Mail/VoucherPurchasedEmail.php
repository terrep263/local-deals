<?php

namespace App\Mail;

use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class VoucherPurchasedEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    public Voucher $voucher;
    
    /**
     * Create a new message instance.
     */
    public function __construct(Voucher $voucher)
    {
        $this->voucher = $voucher;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Voucher: ' . $this->voucher->deal->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.voucher-purchased',
            with: [
                'voucher' => $this->voucher,
                'deal' => $this->voucher->deal,
                'vendor' => $this->voucher->deal->vendor,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        // Attach PDF voucher
        return [
            Attachment::fromStorageDisk('public', $this->voucher->pdf_path)
                ->as('voucher-' . $this->voucher->voucher_code . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

        return [];
    }
}
