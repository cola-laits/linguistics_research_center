<?php

namespace App\Filament\Resources\EieolLanguages\Pages;

use App\Filament\Resources\EieolLanguages\EieolLanguageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEieolLanguages extends ListRecords
{
    protected static string $resource = EieolLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
