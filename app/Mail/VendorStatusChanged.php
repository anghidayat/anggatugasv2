<?php

namespace App\Mail;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Vendor $vendor,
        public string $newStatus, // 'approved' or 'rejected'
        public ?string $reason = null,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->newStatus === 'approved'
            ? "🎉 Lapak \"{$this->vendor->name}\" Telah Disetujui!"
            : "📋 Status Lapak \"{$this->vendor->name}\" — Ditolak";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.vendor-status-changed',
            with: [
                'vendor'     => $this->vendor,
                'newStatus'  => $this->newStatus,
                'reason'     => $this->reason,
                'vendorName' => $this->vendor->user->name ?? 'Pedagang',
            ],
        );
    }
}
