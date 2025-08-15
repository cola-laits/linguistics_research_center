<?php

namespace App\Filament\Resources\EieolSeries\Pages;

use App\Filament\Resources\EieolSeries\EieolSeriesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEieolSeries extends EditRecord
{
    protected static string $resource = EieolSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
