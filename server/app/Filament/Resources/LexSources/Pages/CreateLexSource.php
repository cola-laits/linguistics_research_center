<?php

namespace App\Filament\Resources\LexSources\Pages;

use App\Filament\Resources\LexSources\LexSourceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLexSource extends CreateRecord
{
    protected static string $resource = LexSourceResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
