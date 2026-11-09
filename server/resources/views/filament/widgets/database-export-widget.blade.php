<x-filament-widgets::widget>
    <x-filament::section
        heading="Database export"
        description="Download the database structure and data with passwords and authentication tokens removed. Names and email addresses are included."
    >
        <form method="POST" action="{{ route('admin.database-export') }}">
            @csrf

            <x-filament::button type="submit" icon="heroicon-o-arrow-down-tray">
                Download sanitized SQL
            </x-filament::button>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
