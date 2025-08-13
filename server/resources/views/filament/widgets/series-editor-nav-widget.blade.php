<x-filament-widgets::widget>
    <x-filament::section>

        <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight flex items-center">
            <x-filament::icon icon="heroicon-m-book-open" class="w-[1em] h-[1em] shrink-0 me-2"/> Series Administration
        </h1>
        <p class="mb-4">
            <a href="/guides/eieol_author" target="_new" class="underline flex items-center">
                <x-filament::icon icon="heroicon-m-link" class="w-[1em] h-[1em] shrink-0"/> Author Guide
            </a>
        </p>

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3"></th>
                <th scope="col" class="px-6 py-3">Title</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($serieses as $series)
                <tr class="bg-neutral-100 even:bg-neutral-200 hover:bg-neutral-300 dark:bg-neutral-900 dark:even:bg-neutral-800 dark:hover:bg-neutral-700">
                    <td class="px-6 py-3">
                        <x-filament::button
                            outlined="true"
                            icon="heroicon-m-arrow-top-right-on-square"
                            href="/admin2/eieol_series/{{ $series->id }}/edit"
                            tag="a"
                        >
                            Edit
                        </x-filament::button>
                    </td>
                    <td class="px-6 py-3">{{ $series->title }}</td>
                </tr>
            @endforeach
            </tbody>

        </table>

    </x-filament::section>
</x-filament-widgets::widget>
