<?php

namespace App\Filament\Resources\LexSemanticFields\Pages;

use App\Filament\Resources\LexSemanticFields\LexSemanticFieldResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateLexSemanticField extends CreateRecord
{
    use Translatable;

    protected static string $resource = LexSemanticFieldResource::class;

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
