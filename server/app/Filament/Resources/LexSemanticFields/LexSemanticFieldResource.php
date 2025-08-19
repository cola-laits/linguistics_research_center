<?php

namespace App\Filament\Resources\LexSemanticFields;

use App\Filament\Resources\LexSemanticFields\Pages\CreateLexSemanticField;
use App\Filament\Resources\LexSemanticFields\Pages\EditLexSemanticField;
use App\Filament\Resources\LexSemanticFields\Pages\ListLexSemanticFields;
use App\Filament\Resources\LexSemanticFields\Schemas\LexSemanticFieldForm;
use App\Filament\Resources\LexSemanticFields\Tables\LexSemanticFieldsTable;
use App\Models\LexSemanticField;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LexSemanticFieldResource extends Resource
{
    use Translatable;

    protected static ?string $model = LexSemanticField::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Semantic Fields';
    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'abbr';

    protected static ?string $pluralModelLabel = 'Semantic Fields';
    protected static ?string $label = 'Semantic Field';

    public static function form(Schema $schema): Schema
    {
        return LexSemanticFieldForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexSemanticFieldsTable::configure($table);
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
            'index' => ListLexSemanticFields::route('/'),
            'create' => CreateLexSemanticField::route('/create'),
            'edit' => EditLexSemanticField::route('/{record}/edit'),
        ];
    }
}
