<?php

namespace App\Filament\Resources\LexPartOfSpeeches;

use App\Filament\Resources\LexPartOfSpeeches\Pages\CreateLexPartOfSpeech;
use App\Filament\Resources\LexPartOfSpeeches\Pages\EditLexPartOfSpeech;
use App\Filament\Resources\LexPartOfSpeeches\Pages\ListLexPartOfSpeeches;
use App\Filament\Resources\LexPartOfSpeeches\Schemas\LexPartOfSpeechForm;
use App\Filament\Resources\LexPartOfSpeeches\Tables\LexPartOfSpeechesTable;
use App\Models\LexPartOfSpeech;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LexPartOfSpeechResource extends Resource
{
    use Translatable;

    protected static ?string $model = LexPartOfSpeech::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Parts of Speech';
    protected static ?int $navigationSort = 11;
    protected static ?string $pluralModelLabel = 'Parts of Speech';
    protected static ?string $label = 'Part of Speech';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return LexPartOfSpeechForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexPartOfSpeechesTable::configure($table);
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
            'index' => ListLexPartOfSpeeches::route('/'),
            'create' => CreateLexPartOfSpeech::route('/create'),
            'edit' => EditLexPartOfSpeech::route('/{record}/edit'),
        ];
    }
}
