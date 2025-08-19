<?php

namespace App\Filament\Resources\LexLanguageFamilies\Pages;

use App\Filament\Resources\LexLanguageFamilies\LexLanguageFamilyResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateLexLanguageFamily extends CreateRecord
{
    use Translatable;

    protected static string $resource = LexLanguageFamilyResource::class;

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
