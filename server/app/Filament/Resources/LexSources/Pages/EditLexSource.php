<?php

namespace App\Filament\Resources\LexSources\Pages;

use App\Filament\Resources\LexSources\LexSourceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLexSource extends EditRecord
{
    protected static string $resource = LexSourceResource::class;

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
