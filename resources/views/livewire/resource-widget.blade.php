<div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div class="grid gap-y-2">
        <div wire:click="load" class="cursor-pointer flex items-center justify-between gap-x-2">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ \Illuminate\Support\Str::plural(class_basename($model)) }}
            </span>

            <div wire:loading class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Loading...
            </div>
        </div>

        @if (isset($entries) && count($entries) > 0)
            <livewire:resource-entry :entries="$entries" :model="$model"></livewire:resource-entry>
        @endif

        @foreach ($entries as $entry)
{{--            @php--}}
{{--                $reflection = new ReflectionClass($entry);--}}
{{--            @endphp--}}

{{--            @if($reflection->hasProperty('childModel'))--}}
{{--                <livewire:resource-widget :model="app($entry::$childModel)"></livewire:resource-widget>--}}
{{--            @else--}}
{{--                <p>{{ \Illuminate\Support\Str::plural(class_basename($model)) }}</p>--}}
{{--            @endif--}}

        @endforeach
    </div>
</div>
