<?php

namespace App\Filament\Resources\LexReflexes\Pages;

use App\Filament\Resources\LexReflexes\LexReflexResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexReflexes extends ListRecords
{
    protected static string $resource = LexReflexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
