@if(bss_update_available())
    <x-filament-widgets::widget class="fi-filament-info-widget">
        <x-filament::section class="bg-orange-400 dark:bg-orange-600">
            <div class="flex items-center gap-x-3">
                <div class="flex-1">
                    <div class="flex items-center gap-1">
                        <x-filament::icon
                            icon="heroicon-m-exclamation-triangle"
                            class="h-5 w-5"
                        ></x-filament::icon>

                        <p>Update Available</p>
                    </div>

                    <p class="mt-2 text-xs">
                        An update to BSS Tracker is available. Refer to the documentation for upgrade instructions.
                    </p>
                </div>
            </div>
        </x-filament::section>
    </x-filament-widgets::widget>
@else
    <x-filament-widgets::widget class="fi-filament-info-widget hidden">
        <x-filament::section class="hidden"></x-filament::section>
    </x-filament-widgets::widget>
@endif
