<?php

namespace App\Filament\Resources\LexSemanticFields\Pages;

use App\Filament\Resources\LexSemanticFields\LexSemanticFieldResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditLexSemanticField extends EditRecord
{
    use Translatable;

    protected static string $resource = LexSemanticFieldResource::class;

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
