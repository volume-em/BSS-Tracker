<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

    @if(\App\Models\Setting::where('setting', 'disable_password_login')->where('value', '1')->count() === 0)
            <x-filament-panels::form wire:submit="authenticate">
                {{ $this->form }}

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-filament-panels::form>
    @endif

    @if(\App\Models\Setting::where('setting', 'use_oauth')->where('value', '1')->count() > 0)
            @if(\App\Models\Setting::where('setting', 'oauth_button_text')->count() > 0)
                <a href="{{ route('oauth.redirect') }}" class="px-1.5 py-2 text-center rounded-md bg-green-700 font-bold text-sm hover:bg-green-600">{{ \App\Models\Setting::where('setting', 'oauth_button_text')->first()->value }}</a>
            @else
                <a href="{{ route('oauth.redirect') }}" class="px-1.5 py-2 text-center rounded-md bg-green-700 font-bold text-sm hover:bg-green-600">Sign in using OAuth</a>
            @endif
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
</x-filament-panels::page.simple>
