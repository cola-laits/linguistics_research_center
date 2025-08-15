<?php

namespace App\Filament\Resources\EieolLessons\Pages;

use App\Filament\Resources\EieolLessons\EieolLessonResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEieolLesson extends EditRecord
{
    protected static string $resource = EieolLessonResource::class;

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
