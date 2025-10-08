<?php

namespace App\Filament\Resources\EieolLessons\Schemas;

use App\Filament\Forms\Components\TinyMceRichText;
use App\Models\EieolLesson;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EieolLessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('series_id')
                    ->relationship('series', 'title')
                    ->required(),
                Select::make('language_id')
                    ->relationship('language', 'language')
                    ->required(),
                TextInput::make('title')
                    ->default(null),
                TextInput::make('order')
                    ->required()
                    ->numeric(),
                TinyMceRichText::make('intro_text')
                    ->hiddenOn('create')
                    ->default(null)
                    ->profile('eieol_lesson')
                    ->lrcCharSequences(fn(EieolLesson $lesson) => $lesson?->language?->getTinyMceCharmapConfig())
                    ->lrcLanguages(fn(EieolLesson $lesson) => $lesson?->getTinyMceLanguages())
                    ->contentCss('/css/lrcstyle.css')
                    ->columnSpanFull(),
                TinyMceRichText::make('lesson_text')
                    ->hiddenOn('create')
                    ->default(null)
                    ->profile('eieol_lesson')
                    ->lrcCharSequences(fn(EieolLesson $lesson) => $lesson?->language?->getTinyMceCharmapConfig())
                    ->lrcLanguages(fn(EieolLesson $lesson) => $lesson?->getTinyMceLanguages())
                    ->contentCss('/css/lrcstyle.css')
                    ->columnSpanFull(),
                TinyMceRichText::make('lesson_translation')
                    ->hiddenOn('create')
                    ->default(null)
                    ->profile('eieol_lesson')
                    ->lrcCharSequences(fn(EieolLesson $lesson) => $lesson?->language?->getTinyMceCharmapConfig())
                    ->lrcLanguages(fn(EieolLesson $lesson) => $lesson?->getTinyMceLanguages())
                    ->contentCss('/css/lrcstyle.css')
                    ->columnSpanFull(),
                TextEntry::make('rich_editor_warning')
                    ->label('Page editors available on save')
                    ->state('Choose a language and save this Lesson to enable to page-content editors.')
                    ->visibleOn('create')
            ]);
    }
}
