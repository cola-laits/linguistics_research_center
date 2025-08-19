<?php

namespace App\Filament\Resources\LexLanguages\Pages;

use App\Filament\Resources\LexLanguages\LexLanguageResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class CreateLexLanguage extends CreateRecord
{
    use Translatable;

    protected static string $resource = LexLanguageResource::class;

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
