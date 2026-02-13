<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Events\Dispatcher;

class AuditLogSubscriber
{
    public function handleLogin(Login $event)
    {
        $this->log($event->user, 'Login', 'User Logged In', 'success');
    }

    public function handleLogout(Logout $event)
    {
        $this->log($event->user, 'Logout', 'User Logged Out', 'success');
    }

    public function handleFailed(Failed $event)
    {
        // For failed login, user might be null. We use input email.
        $this->log($event->user, 'Login Failed', 'Login attempt failed', 'failed', 'Invalid credentials');
    }

    protected function log($user, $action, $desc, $status, $reason = null)
    {
        $request = request();
        $ua = $request ? $request->userAgent() : 'System';
        $ip = $request ? $request->ip() : '127.0.0.1';

        // Parse UA (Duplicated logic for simplicity, could be refactored)
        $browser = 'Unknown';
        if (str_contains($ua, 'Chrome')) $browser = 'Chrome';
        elseif (str_contains($ua, 'Firefox')) $browser = 'Firefox';
        elseif (str_contains($ua, 'Safari')) $browser = 'Safari';
        elseif (str_contains($ua, 'Edge')) $browser = 'Edge';

        $os = 'Unknown';
        if (str_contains($ua, 'Windows')) $os = 'Windows';
        elseif (str_contains($ua, 'Mac')) $os = 'MacOS';
        elseif (str_contains($ua, 'Linux')) $os = 'Linux';
        elseif (str_contains($ua, 'Android')) $os = 'Android';
        elseif (str_contains($ua, 'iPhone')) $os = 'iOS';

        $device = (str_contains($ua, 'Mobile') || str_contains($ua, 'Android') || str_contains($ua, 'iPhone')) 
            ? 'Mobile' : 'Desktop';

        AuditLog::create([
            'user_id' => $user?->id,
            'user_name' => $user->name ?? request('email') ?? 'Guest',
            'user_role' => $user?->role?->name ?? 'guest',
            'action' => $action,
            'module' => 'Authentication',
            'model_type' => $user ? get_class($user) : null,
            'model_id' => $user?->id,
            'description' => $desc,
            'status' => $status,
            'failure_reason' => $reason,
            'ip_address' => $ip,
            'user_agent' => $ua,
            'browser' => $browser,
            'os' => $os,
            'device_type' => $device,
            'source' => ($request && $request->wantsJson()) ? 'api' : 'web',
            'created_at' => now(),
        ]);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
            Failed::class => 'handleFailed',
        ];
    }
}