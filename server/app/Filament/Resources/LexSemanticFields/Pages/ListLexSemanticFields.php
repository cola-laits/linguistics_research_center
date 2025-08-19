<?php

namespace App\Filament\Resources\LexSemanticFields\Pages;

use App\Filament\Resources\LexSemanticFields\LexSemanticFieldResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexSemanticFields extends ListRecords
{
    protected static string $resource = LexSemanticFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
