<?php

namespace App\Filament\Resources\LexSemanticCategories\Pages;

use App\Filament\Resources\LexSemanticCategories\LexSemanticCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexSemanticCategories extends ListRecords
{
    protected static string $resource = LexSemanticCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
