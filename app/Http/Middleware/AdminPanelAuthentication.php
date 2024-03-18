<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdminPanelAuthentication extends Middleware
{
    /**
     * @param  array<string>  $guards
     */
    protected function authenticate($request, array $guards)
    {
        if (! file_exists(base_path('.bss-setup'))) return;

        $guard = Filament::auth();

        $this->auth->shouldUse(Filament::getAuthGuard());

        /** @var Model $user */
        $user = $guard->user();

        $panel = Filament::getCurrentPanel();

        if (! auth()->check()) {
            throw new AuthenticationException(
                'Unauthenticated.', $guards, '/admin/login'
            );
        }

        if (auth()->user()->role !== 2) {
            throw new AuthenticationException(
                'Unauthenticated.', $guards, '/'
            );
        }

        abort_if(
            $user instanceof FilamentUser ?
                (! $user->canAccessPanel($panel)) :
                (config('app.env') !== 'local'),
            403,
        );
    }

    protected function redirectTo($request): ?string
    {
        return '/admin';
    }
}
