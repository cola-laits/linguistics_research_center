<?php

namespace App\Filament\Resources\LexPartOfSpeeches\Pages;

use App\Filament\Resources\LexPartOfSpeeches\LexPartOfSpeechResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateLexPartOfSpeech extends CreateRecord
{
    use Translatable;

    protected static string $resource = LexPartOfSpeechResource::class;

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
