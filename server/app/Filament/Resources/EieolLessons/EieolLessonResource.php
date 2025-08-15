<?php

namespace App\Filament\Resources\EieolLessons;

use App\Filament\Resources\EieolLessons\Pages\CreateEieolLesson;
use App\Filament\Resources\EieolLessons\Pages\EditEieolLesson;
use App\Filament\Resources\EieolLessons\Pages\ListEieolLessons;
use App\Filament\Resources\EieolLessons\Schemas\EieolLessonForm;
use App\Filament\Resources\EieolLessons\Tables\EieolLessonsTable;
use App\Models\EieolLesson;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EieolLessonResource extends Resource
{
    protected static ?string $model = EieolLesson::class;

    protected static ?string $navigationLabel = 'Lessons';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-m-chat-bubble-bottom-center-text';
    protected static string|null|\UnitEnum $navigationGroup = 'EIEOL';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return EieolLessonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EieolLessonsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEieolLessons::route('/'),
            'create' => CreateEieolLesson::route('/create'),
            'edit' => EditEieolLesson::route('/{record}/edit'),
        ];
    }
}
