<?php

namespace App\Filament\Resources\LexSources\Pages;

use App\Filament\Resources\LexSources\LexSourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexSources extends ListRecords
{
    protected static string $resource = LexSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
