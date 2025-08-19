<?php

namespace App\Filament\Resources\LexSemanticCategories\Pages;

use App\Filament\Resources\LexSemanticCategories\LexSemanticCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditLexSemanticCategory extends EditRecord
{
    use Translatable;

    protected static string $resource = LexSemanticCategoryResource::class;

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
