<?php

namespace App\Filament\Resources\BookSections\Pages;

use App\Filament\Resources\BookSections\BookSectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookSection extends CreateRecord
{
    protected static string $resource = BookSectionResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
