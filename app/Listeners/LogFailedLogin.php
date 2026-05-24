<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        try {
            // Record the failed login attempt to the database
            LoginLog::create([
                'user_id' => $event->user?->getAuthIdentifier(),
                'email' => $event->credentials['email'] ?? Request::input('email'),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'status' => 'failed',
            ]);
        } catch (\Exception $e) {
            // If an unexpected DB error happens, log it without breaking the login page
            Log::error('Could not save failed login log: ' . $e->getMessage());
        }
    }
}
