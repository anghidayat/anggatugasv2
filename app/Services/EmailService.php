<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send welcome email to newly registered user.
     */
    public function sendWelcome(User $user): bool
    {
        $subject = 'Selamat Datang di StreetFoodies! 🍜';

        try {
            $html = view('emails.welcome', ['user' => $user])->render();

            $this->send([
                'to'      => $user->email,
                'subject' => $subject,
                'html'    => $html,
            ]);

            $this->log($user, 'welcome', $subject, 'sent');

            return true;
        } catch (\Exception $e) {
            Log::error("Welcome email failed for user {$user->id}: " . $e->getMessage());
            $this->log($user, 'welcome', $subject, 'failed', $e->getMessage());

            return false;
        }
    }

    /**
     * Send vendor status update email (approved or rejected).
     */
    public function sendVendorStatusUpdate(Vendor $vendor, string $status, ?string $reason = null): bool
    {
        $user = $vendor->user;
        if (!$user || !$user->email) {
            return false;
        }

        $subject = $status === 'approved'
            ? "🎉 Lapak \"{$vendor->name}\" Telah Disetujui!"
            : "📋 Status Lapak \"{$vendor->name}\" — Ditolak";

        try {
            $html = view('emails.vendor-status-changed', [
                'vendor'     => $vendor,
                'newStatus'  => $status,
                'reason'     => $reason,
                'vendorName' => $user->name,
            ])->render();

            $this->send([
                'to'      => $user->email,
                'subject' => $subject,
                'html'    => $html,
            ]);

            $this->log($user, "vendor_{$status}", $subject, 'sent');

            return true;
        } catch (\Exception $e) {
            Log::error("Vendor status email failed for vendor {$vendor->id}: " . $e->getMessage());
            $this->log($user, "vendor_{$status}", $subject, 'failed', $e->getMessage());

            return false;
        }
    }

    /**
     * Send email via Resend API.
     */
    private function send(array $payload): void
    {
        $apiKey = env('RESEND_API_KEY');

        if (!$apiKey) {
            throw new \RuntimeException('RESEND_API_KEY not configured in .env');
        }

        $from = env('RESEND_FROM_ADDRESS', 'StreetFoodies <onboarding@resend.dev>');

        $response = Http::timeout(15)
            ->withToken($apiKey)
            ->post('https://api.resend.com/emails', [
                'from'    => $from,
                'to'      => [$payload['to']],
                'subject' => $payload['subject'],
                'html'    => $payload['html'],
            ]);

        if (!$response->successful()) {
            $error = $response->json('message') ?? $response->body();
            throw new \RuntimeException("Resend API error: {$error}");
        }
    }

    /**
     * Log email activity to database.
     */
    private function log(User $user, string $type, string $subject, string $status, ?string $error = null): void
    {
        EmailLog::create([
            'user_id'        => $user->id,
            'recipient_email' => $user->email,
            'type'           => $type,
            'subject'        => $subject,
            'status'         => $status,
            'error_message'  => $error,
            'sent_at'        => $status === 'sent' ? now() : null,
        ]);
    }
}
