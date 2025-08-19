<?php

namespace App\Filament\Resources\LexLanguageSubFamilies\Pages;

use App\Filament\Resources\LexLanguageSubFamilies\LexLanguageSubFamilyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditLexLanguageSubFamily extends EditRecord
{
    use Translatable;

    protected static string $resource = LexLanguageSubFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
