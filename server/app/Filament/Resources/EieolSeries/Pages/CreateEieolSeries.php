<?php

namespace App\Filament\Resources\EieolSeries\Pages;

use App\Filament\Resources\EieolSeries\EieolSeriesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEieolSeries extends CreateRecord
{
    protected static string $resource = EieolSeriesResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
