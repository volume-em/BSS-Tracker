<div class="bg-gray-950 h-full w-full flex items-center justify-center">
    <div class="w-1/2">
        <h1 class="text-2xl text-center mb-14 text-white">Reset Password</h1>

        @if ($expired)
            <p class="text-white text-center text-xl">This password reset token has expired</p>
        @else
            <form wire:submit="create" class="p-4 rounded">
                {{ $this->form }}

                <button class="bg-teal-500 text-white px-3 py-1.5 rounded mt-5 font-bold">
                    Set Password
                </button>
            </form>

            <x-filament-actions::modals />
        @endif
    </div>
</div>
