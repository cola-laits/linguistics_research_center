<?php

namespace App\Filament\Resources\EieolSeries\Pages;

use App\Filament\Resources\EieolSeries\EieolSeriesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEieolSeries extends ListRecords
{
    protected static string $resource = EieolSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
