<?php

namespace App\Filament\Resources\BookSections\Pages;

use App\Filament\Resources\BookSections\BookSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBookSection extends EditRecord
{
    protected static string $resource = BookSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
