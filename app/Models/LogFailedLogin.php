<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        LoginLog::create([
            'user_id' => $event->user ? $event->user->id : null,
            'email' => $event->credentials['email'] ?? null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'status' => 'failed',
        ]);
    }
}
