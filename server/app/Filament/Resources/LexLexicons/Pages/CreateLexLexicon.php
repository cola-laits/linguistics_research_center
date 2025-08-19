<?php

namespace App\Filament\Resources\LexLexicons\Pages;

use App\Filament\Resources\LexLexicons\LexLexiconResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateLexLexicon extends CreateRecord
{
    use Translatable;

    protected static string $resource = LexLexiconResource::class;

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
