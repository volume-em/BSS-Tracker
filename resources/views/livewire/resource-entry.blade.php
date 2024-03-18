<div>
    @php
        $columns = app('App\\Filament\\Resources\\' . class_basename(get_class($model)) . 'Resource')::$dashboardColumns;
    @endphp

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    @foreach ($columns as $column)
                        <th scope="col" class="px-6 py-3">
                            {{ $column }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach ($entries as $entry)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 hover:bg-white" wire:click="load_{{strtolower(class_basename($model))}}({{ $entry->id }})">
                    @foreach ($columns as $column)
                        <td class="px-6 py-4">
                            {{ $entry->$column }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
