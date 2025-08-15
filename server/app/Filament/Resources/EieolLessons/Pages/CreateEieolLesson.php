<?php

namespace App\Filament\Resources\EieolLessons\Pages;

use App\Filament\Resources\EieolLessons\EieolLessonResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEieolLesson extends CreateRecord
{
    protected static string $resource = EieolLessonResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
