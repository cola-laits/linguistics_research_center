<?php

namespace App\Filament\Resources\LexEtymas\Pages;

use App\Filament\Resources\LexEtymas\LexEtymaResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateLexEtyma extends CreateRecord
{
    use Translatable;

    protected static string $resource = LexEtymaResource::class;

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
