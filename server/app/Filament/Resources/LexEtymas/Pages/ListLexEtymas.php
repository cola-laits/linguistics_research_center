<?php

namespace App\Filament\Resources\LexEtymas\Pages;

use App\Filament\Resources\LexEtymas\LexEtymaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLexEtymas extends ListRecords
{
    protected static string $resource = LexEtymaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
