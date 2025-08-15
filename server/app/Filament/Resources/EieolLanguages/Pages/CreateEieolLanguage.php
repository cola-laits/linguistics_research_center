<?php

namespace App\Filament\Resources\EieolLanguages\Pages;

use App\Filament\Resources\EieolLanguages\EieolLanguageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEieolLanguage extends CreateRecord
{
    protected static string $resource = EieolLanguageResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
