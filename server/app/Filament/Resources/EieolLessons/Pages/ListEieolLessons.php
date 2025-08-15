<?php

namespace App\Filament\Resources\EieolLessons\Pages;

use App\Filament\Resources\EieolLessons\EieolLessonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEieolLessons extends ListRecords
{
    protected static string $resource = EieolLessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
