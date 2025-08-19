<?php

namespace App\Filament\Resources\LexSources;

use App\Filament\Resources\LexSources\Pages\CreateLexSource;
use App\Filament\Resources\LexSources\Pages\EditLexSource;
use App\Filament\Resources\LexSources\Pages\ListLexSources;
use App\Filament\Resources\LexSources\Schemas\LexSourceForm;
use App\Filament\Resources\LexSources\Tables\LexSourcesTable;
use App\Models\LexSource;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LexSourceResource extends Resource
{
    protected static ?string $model = LexSource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Sources';
    protected static ?int $navigationSort = 8;

    protected static ?string $recordTitleAttribute = 'display';

    protected static ?string $pluralModelLabel = 'Sources';
    protected static ?string $label = 'Source';

    public static function form(Schema $schema): Schema
    {
        return LexSourceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexSourcesTable::configure($table);
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
            'index' => ListLexSources::route('/'),
            'create' => CreateLexSource::route('/create'),
            'edit' => EditLexSource::route('/{record}/edit'),
        ];
    }
}
