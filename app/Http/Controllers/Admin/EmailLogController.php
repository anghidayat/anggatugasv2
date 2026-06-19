<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;

class EmailLogController extends Controller
{
    /**
     * List all email logs.
     */
    public function index()
    {
        $logs = EmailLog::with('user')
            ->latest('sent_at')
            ->paginate(20);

        $stats = [
            'sent'   => EmailLog::where('status', 'sent')->count(),
            'failed' => EmailLog::where('status', 'failed')->count(),
        ];

        return view('admin.email-logs.index', compact('logs', 'stats'));
    }
}
