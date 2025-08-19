<?php

namespace App\Filament\Resources\LexLexicons\Pages;

use App\Filament\Resources\LexLexicons\LexLexiconResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexLexicons extends ListRecords
{
    protected static string $resource = LexLexiconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
