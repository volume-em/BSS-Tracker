<div class="bg-gray-800 h-full w-full flex items-center justify-center">
    <div class="w-1/2">
        <h1 class="text-2xl text-center mb-14 text-white">BSS Tracker Setup</h1>

        <form wire:submit="create">
            {{ $this->form }}
        </form>

        <x-filament-actions::modals />
    </div>
</div>
