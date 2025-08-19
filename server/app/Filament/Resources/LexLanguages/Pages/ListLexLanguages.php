<?php

namespace App\Filament\Resources\LexLanguages\Pages;

use App\Filament\Resources\LexLanguages\LexLanguageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexLanguages extends ListRecords
{
    protected static string $resource = LexLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
