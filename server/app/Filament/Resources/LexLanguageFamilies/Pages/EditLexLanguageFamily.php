<?php

namespace App\Filament\Resources\LexLanguageFamilies\Pages;

use App\Filament\Resources\LexLanguageFamilies\LexLanguageFamilyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditLexLanguageFamily extends EditRecord
{
    use Translatable;

    protected static string $resource = LexLanguageFamilyResource::class;

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
