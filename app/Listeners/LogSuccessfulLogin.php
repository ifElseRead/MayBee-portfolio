<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Request;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Record the successful login attempt
        LoginLog::create([
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'status' => 'success',
        ]);
    }
}
