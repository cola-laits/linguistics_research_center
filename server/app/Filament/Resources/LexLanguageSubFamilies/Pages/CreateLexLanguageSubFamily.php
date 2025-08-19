<?php

namespace App\Filament\Resources\LexLanguageSubFamilies\Pages;

use App\Filament\Resources\LexLanguageSubFamilies\LexLanguageSubFamilyResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateLexLanguageSubFamily extends CreateRecord
{
    use Translatable;

    protected static string $resource = LexLanguageSubFamilyResource::class;

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
