<?php

namespace App\Filament\Resources\LexLanguageSubFamilies\Pages;

use App\Filament\Resources\LexLanguageSubFamilies\LexLanguageSubFamilyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexLanguageSubFamilies extends ListRecords
{
    protected static string $resource = LexLanguageSubFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
