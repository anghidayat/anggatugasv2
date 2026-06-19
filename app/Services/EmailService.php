<?php

namespace App\Services;

use App\Mail\VendorStatusChanged;
use App\Mail\WelcomeEmail;
use App\Models\EmailLog;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send welcome email to newly registered user.
     */
    public function sendWelcome(User $user): bool
    {
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));

            $this->log($user, 'welcome', 'Selamat Datang di StreetFoodies!', 'sent');

            return true;
        } catch (\Exception $e) {
            Log::error("Welcome email failed for user {$user->id}: " . $e->getMessage());
            $this->log($user, 'welcome', 'Selamat Datang di StreetFoodies!', 'failed', $e->getMessage());

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

        try {
            Mail::to($user->email)->send(new VendorStatusChanged($vendor, $status, $reason));

            $subject = $status === 'approved'
                ? "Lapak \"{$vendor->name}\" Telah Disetujui!"
                : "Lapak \"{$vendor->name}\" Ditolak";

            $this->log($user, "vendor_{$status}", $subject, 'sent');

            return true;
        } catch (\Exception $e) {
            Log::error("Vendor status email failed for vendor {$vendor->id}: " . $e->getMessage());

            $subject = $status === 'approved'
                ? "Lapak \"{$vendor->name}\" Telah Disetujui!"
                : "Lapak \"{$vendor->name}\" Ditolak";

            $this->log($user, "vendor_{$status}", $subject, 'failed', $e->getMessage());

            return false;
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
