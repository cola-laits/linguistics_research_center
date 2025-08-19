<?php

namespace App\Filament\Resources\LexPartOfSpeeches\Pages;

use App\Filament\Resources\LexPartOfSpeeches\LexPartOfSpeechResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexPartOfSpeeches extends ListRecords
{
    protected static string $resource = LexPartOfSpeechResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
