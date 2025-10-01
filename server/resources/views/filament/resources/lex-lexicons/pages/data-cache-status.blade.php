<x-filament::page>
    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $this->record->name }} &mdash; Data Cache Status
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                Overview of cached English reflex data for this lexicon.
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white/80 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/60">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Reflexes</p>
                <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ number_format($reflexCount) }}
                </p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Count of reflex entries associated with this lexicon.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white/80 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/60">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cached Entries (English)</p>
                <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ number_format($dataCacheCount) }}
                </p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Cached (English-language) rows being displayed by the Lexicon's 'data' page.
                </p>
            </div>
        </div>
    </div>
</x-filament::page>
