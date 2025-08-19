<?php

namespace App\Filament\Resources\LexLanguageFamilies\Pages;

use App\Filament\Resources\LexLanguageFamilies\LexLanguageFamilyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexLanguageFamilies extends ListRecords
{
    protected static string $resource = LexLanguageFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
