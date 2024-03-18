<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BssTrackerAuthentication extends Middleware
{
    /**
     * @param  array<string>  $guards
     */
    protected function authenticate($request, array $guards)
    {
        if (! file_exists(base_path('.bss-setup'))) return;

        $guard = Filament::auth();

        if (! $guard->check()) {
            if (\App\Models\Setting::where('setting', '=', 'require_authentication')->where('value', '=', '1')->count() > 0) {
                return $this->unauthenticated($request, $guards);
            }
        }

        $this->auth->shouldUse(Filament::getAuthGuard());

        /** @var Model $user */
        $user = $guard->user();

        $panel = Filament::getCurrentPanel();

        abort_if(
            $user instanceof FilamentUser ?
                (! $user->canAccessPanel($panel)) :
                (config('app.env') !== 'local'),
            403,
        );
    }

    protected function redirectTo($request): ?string
    {
        return '/login';
    }
}
