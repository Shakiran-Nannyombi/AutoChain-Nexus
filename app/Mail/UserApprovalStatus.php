<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserApprovalStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $approved;
    public $rejectionReason;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, bool $approved, ?string $rejectionReason = null)
    {
        $this->user = $user;
        $this->approved = $approved;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->approved ? 'Registration Approved' : 'Registration Rejected',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->approved ? 'emails.registration-approved' : 'emails.registration-rejected',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
} 