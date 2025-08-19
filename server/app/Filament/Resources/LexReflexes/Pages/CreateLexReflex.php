<?php

namespace App\Filament\Resources\LexReflexes\Pages;

use App\Filament\Resources\LexReflexes\LexReflexResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateLexReflex extends CreateRecord
{
    use Translatable;

    protected static string $resource = LexReflexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
