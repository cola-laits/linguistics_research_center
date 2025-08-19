<?php

namespace App\Filament\Resources\LexSemanticCategories\Pages;

use App\Filament\Resources\LexSemanticCategories\LexSemanticCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateLexSemanticCategory extends CreateRecord
{
    use Translatable;

    protected static string $resource = LexSemanticCategoryResource::class;

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
