<?php

namespace App\Filament\Resources\LexReflexes;

use App\Filament\Resources\LexReflexes\Pages\CreateLexReflex;
use App\Filament\Resources\LexReflexes\Pages\EditLexReflex;
use App\Filament\Resources\LexReflexes\Pages\ListLexReflexes;
use App\Filament\Resources\LexReflexes\Schemas\LexReflexForm;
use App\Filament\Resources\LexReflexes\Tables\LexReflexesTable;
use App\Models\LexReflex;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LexReflexResource extends Resource
{
    use Translatable;

    protected static ?string $model = LexReflex::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeft;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Reflexes';
    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'gloss';

    protected static ?string $pluralModelLabel = 'Reflexes';
    protected static ?string $label = 'Reflex';

    public static function form(Schema $schema): Schema
    {
        return LexReflexForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexReflexesTable::configure($table);
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
            'index' => ListLexReflexes::route('/'),
            'create' => CreateLexReflex::route('/create'),
            'edit' => EditLexReflex::route('/{record}/edit'),
        ];
    }
}
