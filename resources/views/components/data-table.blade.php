<!-- Data Table Card -->
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ $title ?? __('Data') }}
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-gray-700 dark:text-gray-300 display" id="{{ $tableId ?? 'dataTable' }}" data-list-url="{{ $listUrl ?? '#' }}">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    {{ $headers }}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <!-- Data will be loaded here -->
            </tbody>
        </table>
    </div>
</div>
