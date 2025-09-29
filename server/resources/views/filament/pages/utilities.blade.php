<x-filament::page>
    <div class="space-y-4">
        <p class="text-base text-gray-700 dark:text-gray-200">
            Utility tools appear here.
        </p>
    </div>

    <div class="p-4 bg-gray-200 dark:bg-gray-700">
        <p class="text-2xl">Lexicon CSV Uploads</p>
        <p class="m-2">
        <x-filament::button wire:click="mountAction('runLanguageUpload')">
            Upload Lexicon Languages
        </x-filament::button>
        </p>
        <p class="m-2">
        <x-filament::button wire:click="mountAction('runSemanticsUpload')">
            Upload Lexicon Semantics
        </x-filament::button>
        </p>
        <p class="m-2">
            Etyma upload still under development...
            <x-filament::button disabled="true" wire:click="mountAction('runEtymaUpload')">
                Upload Lexicon Etyma
            </x-filament::button>
        </p>
        <p class="m-2">
        <x-filament::button wire:click="mountAction('runReflexesUpload')">
            Upload Lexicon Reflexes
        </x-filament::button>
        </p>
    </div>

    <x-filament-actions::modals />
</x-filament::page>
