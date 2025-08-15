<?php

namespace App\Filament\Resources\EieolLanguages\Pages;

use App\Filament\Resources\EieolLanguages\EieolLanguageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEieolLanguage extends EditRecord
{
    protected static string $resource = EieolLanguageResource::class;

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
